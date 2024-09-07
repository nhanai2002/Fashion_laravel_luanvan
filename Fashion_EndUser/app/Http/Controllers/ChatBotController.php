<?php

namespace App\Http\Controllers;

use FashionCore\Models\Size;
use FashionCore\Models\User;
use Illuminate\Http\Request;
use FashionCore\Models\Message;
use FashionCore\Models\Product;
use Illuminate\Support\Facades\Log;
use FashionCore\Models\Conversation;
use FashionAdmin\Jobs\SendMessageJob;
use FashionCore\Models\WarehouseItem;
use FashionAdmin\Events\MessageSentEvent;

class ChatBotController extends Controller
{
    public function handle(Request $request)
    {
        try{
            $start = microtime(true);
            $botman = app('botman');
            $userId = $request->input('user_id');
            $botman->hears('{message}', function($bot, $message) use ($userId) {
                $user = User::select('id','name')->where('id', $userId)->first();

                $conversation = Conversation::firstOrCreate(
                    ['user_id' => $userId],
                );
                if (!$conversation->is_taken_over) {
                    if(!$conversation->is_welcomed){
                        $botResponse = 'Xin chào, đây là tin nhắn tự động, chúng tôi sẽ phản hồi sớm nhất có thể ';
    
                        $messageBot = [
                            'content' => $botResponse,
                            'sender_id' => null, 
                            'created_at' => now()
                        ];
        
                        //$bot->reply($botResponse);
        
                        broadcast(new MessageSentEvent($conversation->id, $messageBot, null));
                        $conversation->is_welcomed = true;
                        $conversation->save();
                        dispatch(new SendMessageJob([
                            'content' => $botResponse,
                            'conversation_id' => $conversation->id,
                            'sender_id' => null, 
                        ]));
                    }
                    $messageUser =[
                        'content' => $message,
                        'sender_id' => $userId,
                        'created_at' => now()
                    ];
                    broadcast(new MessageSentEvent($conversation->id, $messageUser, $user));
                    dispatch(new SendMessageJob([
                        'content' => $message,
                        'conversation_id' => $conversation->id,
                        'sender_id' => $userId,
                    ]));

                    $getFormBot = $this->getBotResponse($message);
                    $messageBot =[
                        'content' => $getFormBot,
                        'sender_id' => null,
                        'created_at' => now()
                    ];
                    // nếu có nền tảng chat
                    //$bot->reply($botResponse);
                    broadcast(new MessageSentEvent($conversation->id, $messageBot, null));
                    dispatch(new SendMessageJob([
                        'content' => $getFormBot,
                        'conversation_id' => $conversation->id,
                        'sender_id' => null,
                    ]));
                } 
                // nếu có nền tảng chat
                // else {
                //     $bot->reply('Admin hiện đang trả lời bạn.');
                // }
                else{
                    $messageUser = [
                        'content' => $message,
                        'sender_id' => $userId,
                        'created_at' => now()
                    ];
        
                    broadcast(new MessageSentEvent($conversation->id, $messageUser, $user));   
                    dispatch(new SendMessageJob([
                        'content' => $message,
                        'conversation_id' => $conversation->id,
                        'sender_id' => $userId,
                    ]));
 
                }
      
            });
    
            $botman->fallback(function($bot){
                $bot->reply('Xin lỗi, tôi không hiểu câu hỏi của bạn.');
            });
            $botman->listen();
            $end = microtime(true);
            $duration = $end - $start;
            Log::info('Time taken to send message: ' . $duration . ' seconds');
            return response()->json([
                'error' => false,
                'message' => []
            ]);
        }
        catch (\Exception $e) {
            Log::info($e->getMessage());
        }
    }


    protected function getBotResponse($message)
    {
        if (stripos($message, 'cao') !== false || stripos($message, 'nặng') !== false) {
            $message = preg_replace_callback('/(\d+)m(\d+)/i', function($matches) {
                return ($matches[1] * 100 + $matches[2]); // Chuyển đổi 1m70 thành 170 cm
            }, $message);
            $message = preg_replace('/m(\d+)/i', '$1', $message); // Chuyển đổi m70 thành 70 cm
            $message = preg_replace('/(\d+)\s*cm/i', '$1', $message);
            $message = preg_replace('/(\d+)\s*kg/i', '$1', $message);


            $pattern = '/cao\s*(\d+)\s*(?:nặng|và\s*nặng)?\s*(\d+)/i';
            if (preg_match($pattern, $message, $matches)) {
                
                $height = $matches[1]; 
                $weight = $matches[2];
                
                $productType = stripos($message, 'quần') !== false ? 'quần' : (stripos($message, 'áo') !== false ? 'áo' : 'cả hai');
    
                $sizeRecommendation = $this->getSize($height, $weight, $productType);
                Log::info( $sizeRecommendation );
                return 'Dựa trên chiều cao ' . $height . ' cm và cân nặng ' . $weight . ' kg, kích thước ' . $productType . ' bạn nên chọn là: ' . $sizeRecommendation;
            }
        }

        if (preg_match('/size|kích\s+thước/i', $message)) {
            $pattern = '/(?:size|kích\s+thước)\s*(.+)|(.+)\s*(?:size|kích\s+thước)/i';
            if (preg_match($pattern, $message, $matches)) {
                $productName = trim($matches[1] ?? $matches[2]);
                $productInfo = Product::where('name', 'like', '%' . $productName . '%')->first();
                if ($productInfo) {
                    $sizeIds = WarehouseItem::where('product_id', $productInfo->id)->pluck('size_id')->unique();
                    $sizes = Size::whereIn('id', $sizeIds)->get(['id', 'name']);
                    $sizeNames = $sizes->pluck('name')->implode(', ');
                    return 'Sản phẩm '.$productInfo->name. ' còn các size sau: ' . $sizeNames;
                } else {
                    return 'Sản phẩm không được tìm thấy.';
                }
            } else {
                return 'Xin lỗi, tôi không hiểu yêu cầu của bạn về sản phẩm.';
            }
        }
        return 'Cảm ơn bạn đã quan tâm!';
    }

    protected function getSize($height, $weight, $productType)
    {
        $pantsSize = 'M';
        $shirtSize = 'M'; 
        if ($productType === 'quần' || $productType === 'cả hai') {
            if ($height >= 170) {
                $pantsSize = 'L';
            } 
            elseif ($height >= 160 && $height < 170) {
                $pantsSize = 'M';
            }
            elseif ($height < 160) {
                $pantsSize = 'S';
            }
        }
    
        if ($productType === 'áo' || $productType === 'cả hai') {
            if ($height >= 170 || $weight >= 60) {
                $shirtSize = 'L';
            } elseif ($height >= 160 && $height < 170 || $weight > 60) {
                $shirtSize = 'M';
            } 
            elseif ($height < 160 || $weight < 60) {
                $pantsSize = 'S';
            }
        }
    
        $response = '';
        if ($productType === 'quần' || $productType === 'cả hai') {
            $response .= 'Quần: ' . $pantsSize . '. ';
        }
        if ($productType === 'áo' || $productType === 'cả hai') {
            $response .= 'Áo: ' . $shirtSize . '.';
        }
    
        return $response;
    }
}
