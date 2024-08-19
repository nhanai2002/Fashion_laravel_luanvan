<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use FashionCore\Models\Coupon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use FashionCore\Interfaces\ICouponRepository;

class CouponController extends Controller
{
    protected $couponRepo;
    public function __construct(ICouponRepository $couponRepo){
        $this->couponRepo = $couponRepo;
    }

     /**
     * @permission view_coupon
     */
    public function index(){
        return view('/coupon/index',[
            'title' => 'Danh sách coupon',
            'coupons' => $this->couponRepo->getAll()
        ]);
    }

     /**
     * @permission create_coupon
     */
    public function create(){
        return view('/coupon/add',[
            'title' => 'Tạo coupon'
        ]);
    }

    public function store(Request $request){
        $this->validate($request,[
            'code' => 'required',
            'name' => 'required',
            'quantity' => 'required',
            'description' => 'required',
            'value' => 'required',
            'type' => 'required',
            'time_start' => 'required',
            'time_end' => 'required',
        ],[
            'code' => 'Vui lòng nhập mã giảm giá',
            'name' => 'Vui lòng nhập tên giảm giá',
            'quantity' => 'Vui lòng nhập số lượng',
            'description' => 'Vui lòng nhập mô tả',
            'value' => 'Vui lòng nhập giá trị',
            'type' => 'Vui lòng chọn loại giảm giá',
            'time_start' => 'Vui lòng nhập thời gian bắt đầu',
            'time_end' => 'Vui lòng nhập thời gian kết thúc',
        ]);
        try{
            $this->couponRepo->add($request->all());
            Session::flash('success','Tạo coupon mới thành công!');
        }
        catch(\Exception $e){
            Session::flash('error', 'Đã xảy ra lỗi');
            Log::error('Đã xảy ra lỗi: ' . $e->getMessage());
            return redirect()->back();
        }
        return redirect('admin/coupon/index');   
    }

     /**
     * @permission delete_coupon
     */
    public function destroy(Request $request) :JsonResponse
    {
        $result = $this->couponRepo->delete($request->id);
        if($result){
            return response()->json([
                'error' => false,
                'message' => 'Xóa thành công!'
            ]);
        }
        return response()->json([
            'error' => true,
        ]);
    }

     /**
     * @permission edit_coupon
     */
    public function show(Coupon $coupon){
        return view('/coupon/edit',[
            'title' => 'Chỉnh sửa coupon',
            'coupon'=> $coupon
        ]);
    }

    public function update(Request $request, Coupon $coupon){
        $currentDate = now();
        if ($coupon->time_end && $coupon->time_end < $currentDate) {
            return redirect()->back()->withErrors(['message' => 'Mã giảm giá đã hết hạn, không thể sửa']);
        }
        try{
            $this->couponRepo->update($coupon->id, $request->all());
            Session::flash('success','Cập nhật thành công!');
        }
        catch(\Exception $e){
            Session::flash('error', 'Đã xảy ra lỗi');
            Log::error('Đã xảy ra lỗi: ' . $e->getMessage());
        }
        return redirect('admin/coupon/index');   
    }
}
