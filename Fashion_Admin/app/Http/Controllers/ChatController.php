<?php

namespace App\Http\Controllers;

use FashionCore\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use FashionAdmin\Jobs\SendMessageJob;
use FashionAdmin\Events\MessageSentEvent;
use FashionCore\Interfaces\IUserRepository;
use FashionCore\Interfaces\IMessageRepository;
use FashionAdmin\Events\ChatStoppedTakingOverEvent;
use FashionCore\Interfaces\IConversationRepository;
use FashionCore\Interfaces\IHistoryTakeoverRepository;

class ChatController extends Controller
{
    protected $userRepo;
    protected $conversationRepo;
    protected $handoverRepo;
    protected $messageRepo;
    
    public function __construct(IUserRepository $userRepo, IConversationRepository $conversationRepo, IMessageRepository $messageRepo, IHistoryTakeoverRepository $handoverRepo)
    {
        $this->userRepo = $userRepo;
        $this->conversationRepo = $conversationRepo;
        $this->messageRepo = $messageRepo;
        $this->handoverRepo = $handoverRepo;
    }

    // admin gửi
    public function sendMessage(Request $request)
    {
        $conversation = $this->conversationRepo->buildQuery(['id'=>$request->conversation_id])->first();
        $user = $this->userRepo->buildQuery(['id'=>Auth::id()])->select('id','name')->first();
        if ($conversation && $conversation->is_taken_over) {
            $message = [
                'content' => $request->message,
                'sender_id' => Auth::id(),
                'created_at' => now()
            ];
            broadcast(new MessageSentEvent($conversation->id, $message, $user));
            dispatch(new SendMessageJob([
                'content' => $request->message,
                'conversation_id' => $conversation->id,
                'sender_id' => Auth::id(),
            ]));
        }else{
            $newConversation = $this->conversationRepo->add([
                'is_taken_over' => true,
                'user_id' => $request->user_id
            ]);
            $newMessage = [
                'content' => $request->message,
                'sender_id' => Auth::id(),
                'created_at' => now()
            ];
            broadcast(new MessageSentEvent($newConversation->id, $newMessage, $user));
            dispatch(new SendMessageJob([
                'content' => $request->message,
                'conversation_id' => $conversation->id,
                'sender_id' => Auth::id(),
            ]));

        }
        return response()->json([
            'error' => false,
        ]);
    }

    public function getConversation(User $user)
    {  
        $conversation = $this->conversationRepo->buildQuery(['user_id' => $user->id])
        ->with(['user:id,name','messages.user'])
        ->first();
        if(!$conversation){
            $conversation = $this->conversationRepo->add([
                'user_id' => $user->id,
                'is_taken_over' => true,
                'is_welcomed' => true
            ]);
        }
        $response = [
            'messages' => $conversation ? $conversation->messages : [],
            'user' => $user,     
            'conversation' => $conversation,
        ];

        if($conversation){
            $humanTakingOver = $this->handoverRepo->buildQuery([
                'conversation_id'=>$conversation->id,
                'stopped_at'=> null
                ])->latest('started_at')
                ->with('user:id,name')
                ->first();  
            if($humanTakingOver){
                $response['humanTakingOver'] = $humanTakingOver;
            }  
        }
        return response()->json($response); 

    }
    
    public function changeStatusTakeOver(Request $request){
        try{
            $conversation = $this->conversationRepo->buildQuery(['id' => $request->id])->first();

            $status = $request->stt;
    
            $humanTakingOver = $this->handoverRepo->buildQuery(['stopped_at'=> null])->latest('started_at')->with('user:id,name')->first();

            if($humanTakingOver && $humanTakingOver->take_over_by != Auth::id()){
                return response()->json([
                    'error' => true,
                    'message' => 'Hiện '. $humanTakingOver->user->name . ' đang tiếp quản cuộc trò chuyện này',
                ]);
            }

            // tiếp quản
            if($status == 1 && !$conversation->is_taken_over && !$humanTakingOver){
                $this->conversationRepo->update($request->id,['is_taken_over' => 1]);
                $this->handoverRepo->add([
                    'conversation_id'=> $conversation->id,
                    'take_over_by' => Auth::id(),
                    'started_at' => now(),
                ]);
                return response()->json([
                    'error' => false,
                    'is_taken_over' => 1
                ]);
            }
            else if($status == 0 && $conversation->is_taken_over){
                $this->conversationRepo->update($request->id,['is_taken_over' => 0]);
                $humanTakingOver->stopped_at = now();
                $humanTakingOver->save();
                return response()->json([
                    'error' => false,
                    'is_taken_over' => 0
                ]);
            }
            return response()->json([
                'error' => false,
            ]);    
        }
        catch (\Exception $e){
            Log::info($e->getMessage());
            Log::info($e->getLine());
            return response()->json([
                'error' => true,
                'message' => 'Lỗi: ' . $e->getMessage()
            ]);
        }
    }
}
