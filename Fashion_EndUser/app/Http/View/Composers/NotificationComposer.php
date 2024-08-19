<?php

namespace App\Http\View\Composers;

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

    public function __construct(INotificationRepository $notiRepo, IUserRepository $userRepo)
    {
        $this->notiRepo = $notiRepo;
        $this->userRepo = $userRepo;
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
            $view->with('getNotications', $notifications);  
            $view->with('countNotification', $countNotification);    
        }
    }
}
