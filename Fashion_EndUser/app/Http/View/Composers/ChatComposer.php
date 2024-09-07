<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use FashionCore\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use FashionCore\Interfaces\IUserRepository;
use FashionCore\Interfaces\IConversationRepository;
use FashionCore\Interfaces\INotificationRepository;

class ChatComposer
{
    public $userRepo;
    public $conversationRepo;

    public function __construct(IUserRepository $userRepo, IConversationRepository $conversationRepo)
    {
        $this->userRepo = $userRepo;
        $this->conversationRepo = $conversationRepo;
    }

    public function compose(View $view)
    {
        $this->handle($view);
    }

    public function handle($view){
        $conversation = $this->conversationRepo->buildQuery(['user_id'=> Auth::id()])->with('messages.user')->first();

        if($conversation){
            $view->with('conversation', $conversation);  
        }
        else{
            $view->with('conversation', null);  
        }
        
    }
}
