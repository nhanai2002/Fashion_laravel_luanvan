<?php

namespace App\Http\Controllers;

use FashionCore\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use FashionCore\Interfaces\ISizeRepository;

class SizeController extends Controller
{
    protected $sizeRepo;
    public function __construct(ISizeRepository $sizeRepo)
    {
        $this->sizeRepo = $sizeRepo;
    }

    /**
     * @permission view_size
     */
    public function index(){
        return view('size/index',[
            'title' => 'Danh sách kích thước',
            'sizes' => $this->sizeRepo->getAll(),
        ]);
    }

     /**
     * @permission create_size
     */
    public function create(){
        return view('size/add',[
            'title' => 'Thêm kích thước',
        ]);
    }

    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required'
        ],[
            'name' => 'Vui lòng nhập kích thước'
        ]);

        try{
            $attributes = $request->all();
            $this->sizeRepo->add($attributes);
            Session::flash('success','Thêm thành công!');
        }
        catch(\Exception $e){
            Session::flash('error', 'Đã xảy ra lỗi');
            Log::error('Đã xảy ra lỗi: ' . $e->getMessage());
            return redirect()->back();
        }
        return redirect('admin/size/index');
    }

    /**
     * @permission delete_size
     */
    public function destroy(Request $request): JsonResponse
    {
        $result = $this->sizeRepo->delete($request->id);
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
     * @permission edit_size
     */
    public function show(Size $size){
        return view('size/edit',[
            'title' => 'Chỉnh sửa màu',
            'size'=> $size
        ]);
    }

    public function update(Request $request, Size $size){
        $this->validate($request,[
            'name' => 'required'
        ],[
            'name' => 'Vui lòng nhập kích thước'
        ]);
        $this->sizeRepo->update($size->id, $request->all());
        return redirect('admin/size/index');
    }

}
