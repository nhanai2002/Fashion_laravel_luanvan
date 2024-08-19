<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use FashionCore\Interfaces\IUserRepository;

class PermissionComposer
{
    public $userRepo;
    public function __construct(IUserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function compose(View $view)
    {
        $userId = Auth::id();
        if($userId){
            $user = $this->userRepo->buildQuery(['id'=>$userId])->first();
            $getPermissions = $user->role_id === 1 ? ['*'] : $this->userRepo->getUserPermissions($userId);
            $view->with('check_permissions', $getPermissions);    
        }
    }
}
