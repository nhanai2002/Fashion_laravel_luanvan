<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use FashionCore\Models\Color;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;
use FashionCore\Interfaces\IColorRepository;

class ColorController extends Controller
{
    protected $colorRepo;
    public function __construct(IColorRepository $colorRepo)
    {
        $this->colorRepo = $colorRepo;
    }

    /**
     * @permission view_color
     */
    public function index(){
        return view('color/index',[
            'title' => 'Danh sách màu sắc',
            'colors' => $this->colorRepo->getAll(),
        ]);
    }

    /**
     * @permission create_color
     */
    public function create(){
        return view('color/add',[
            'title' => 'Thêm màu sắc',
        ]);
    }

    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required',
        ],[
            'name' => 'Vui lòng nhập tên màu',
        ]);
        try{
            $this->colorRepo->add($request->all());
            Session::flash('success','Thêm thành công!');
        }
        catch(\Exception $e){
            Session::flash('error', 'Đã xảy ra lỗi');
            return redirect()->back();
        }
        return redirect('/admin/color/index');

    }

    /**
     * @permission delete_color
     */
    public function destroy(Request $request): JsonResponse
    {
        $result = $this->colorRepo->delete($request->id);
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
     * @permission edit_color
     */
    public function show(Color $color){
        return view('color/edit',[
            'title' => 'Chỉnh sửa màu',
            'color'=> $color
        ]);
    }

    public function update(Request $request, Color $color){
        $this->validate($request,[
            'name' => 'required'
        ],[
            'name' => 'Vui lòng nhập tên màu'
        ]);
        $this->colorRepo->update($color->id, $request->all());

        return redirect('admin/color/index');
    }
}
