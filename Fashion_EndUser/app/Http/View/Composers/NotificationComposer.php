<?php

namespace App\Http\View\Composers;

use FashionCore\Interfaces\IConversationRepository;
use Illuminate\View\View;
use FashionCore\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use FashionCore\Interfaces\IUserRepository;
use FashionCore\Interfaces\INotificationRepository;

class NotificationComposer
{
    public $notiRepo;
    public $userRepo;
    public $conversationRepo;

    public function __construct(INotificationRepository $notiRepo, IUserRepository $userRepo, IConversationRepository $conversationRepo)
    {
        $this->notiRepo = $notiRepo;
        $this->userRepo = $userRepo;
        $this->conversationRepo = $conversationRepo;
    }

    public function compose(View $view)
    {
        $this->handle($view);
    }

    public function handle($view){
        $userId = Auth::id();
        $user = $this->userRepo->buildQuery(['id'=> $userId])->with('notifications')->first();
        if($user){
            // sortByDesc trên collection
            $notifications = $user->notifications
            ->filter(function($notification) {
                return !is_null($notification->date_received);
            })->sortByDesc('date_received');
            $countNotification = $notifications->count();

            // trả về id của hội thoại luôn
            $conversation = $this->conversationRepo->buildQuery(['user_id'=> $user->id])->first();
            if($conversation){
                $view->with('conversation_id', $conversation->id);  
            }
            $view->with('getNotications', $notifications);  
            $view->with('countNotification', $countNotification);    
        }
    }
}
