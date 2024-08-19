<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use FashionCore\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use FashionCore\Interfaces\IRoleRepository;
use FashionCore\Interfaces\IUserRepository;
use FashionAdmin\Events\SendAllNotificationEvent;
use FashionAdmin\Events\SendRoleNotificationEvent;
use FashionAdmin\Events\SendUserNotificationEvent;
use FashionCore\Interfaces\INotificationRepository;

class NotificationController extends Controller
{
    protected $notiRepo;
    protected $roleRepo;
    protected $userRepo;

    public function __construct(INotificationRepository $notiRepo, IRoleRepository $roleRepo, IUserRepository $userRepo)
    {
        $this->notiRepo = $notiRepo;
        $this->roleRepo = $roleRepo;
        $this->userRepo = $userRepo;
    }

    public function index(){
        return view('notification/index',[
            'title' => 'Danh sách thông báo',
            'notifications' => $this->notiRepo->getNotifications(),
        ]);
    }

    public function create(){
        return view('notification/add',[
            'title' => 'Tạo thông báo mới',
            'roles' => $this->roleRepo->getAll(),
            'users' => $this->userRepo->getAll(),

        ]);
    }

    public function store(Request $request){
        $this->validate($request,[
            'title' => 'required',
            'message' => 'required'
        ],[
            'title' => 'Vui lòng nhập tiêu đề',
            'message' => 'Vui lòng nhập nội dung'
        ]);

        try{
            $type = (int)$request->input('type');
            if($type == 1){
                 // Gửi đến nhóm quyền
                 $notification = $this->notiRepo->add([
                    'title' => $request->input('title'),
                    'message' => $request->input('message'),
                    'type' => 1,
                ]);
                $roleIds = $request->input('role_ids', []);
                $userIds = User::whereIn('role_id', $roleIds)->pluck('id');
                $notification->users()->attach($userIds);
            }
            else if($type == 2){
                // Gửi đến cá nhân
                $notification = $this->notiRepo->add([
                    'title' => $request->input('title'),
                    'message' => $request->input('message'),
                    'type' => 2,
                ]);
                $userIds = $request->input('user_ids', []);
                $notification->users()->attach($userIds);
            }
            else{
                 // Gửi đến tất cả
                 $notification = $this->notiRepo->add([
                    'title' => $request->input('title'),
                    'message' => $request->input('message'),
                    'type' => 0,
                ]);
            }
            Session::flash('success','Tạo mới thành công!');
        }
        catch(\Exception $e){
            Session::flash('error', 'Đã xảy ra lỗi');
            Log::error('Đã xảy ra lỗi: ' . $e->getMessage());
            return redirect()->back();
        }
        return redirect('admin/notification/index');
    }


    public function sendNotification(Request $request) : JsonResponse
    {
        try{
            DB::beginTransaction();
            $notification = $this->notiRepo->buildQuery(['id' => $request->id])->with('users')->first();
            if($notification){
                if($notification->date_received){
                    return response()->json([
                        'error' => true,
                        'message' => 'Thông báo này đã được gửi, không thể gửi lại!'
                    ]);
                }
                $notification->date_received = Carbon::now();
                $notification->save();
                if($notification->type == 1 || $notification->type == 2){
                    foreach ($notification->users as $user) {
                        event(new SendUserNotificationEvent($notification, $user->id));
                    }
                }
                else{
                    event(new SendAllNotificationEvent($notification));
                }
                DB::commit();
                return response()->json([
                    'error' => false,
                    'message' => 'Gửi thành công!'
                ]);
            }
        }
        catch(Exception $e){
            DB::rollback();
            return response()->json([
                'error' => true,
                'message' => 'Đã xảy ra lỗi!'
            ]);
        }
    }



}
