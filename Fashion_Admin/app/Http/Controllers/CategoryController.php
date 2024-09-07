<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use FashionCore\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use FashionCore\Interfaces\ICategoryRepository;

class CategoryController extends Controller
{
    protected $categoryRepo;
    
    public function __construct(ICategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    /**
     * @permission view_category
    */
    public function index(){
        return view('category/index',[
            'title' => 'Danh sách danh mục',
            'list' => $this->categoryRepo->getAll(),
        ]);
    }

    /**
     * @permission create_category
     */
    public function create(){
        return view('/category/add',[
            'title' => 'Thêm danh mục mới',
            'list' => $this->categoryRepo->getParent()
        ]);
    }

    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required'
        ],[
            'name' => 'Vui lòng nhập tên danh mục'
        ]);

        try{
            $this->categoryRepo->add($request->all());
            Session::flash('success','Thêm danh mục thành công!');
        }
        catch(\Exception $e){
            Session::flash('error', 'Đã xảy ra lỗi');
            Log::error('Đã xảy ra lỗi: ' . $e->getMessage());
            return redirect()->back();
        }
        return redirect('admin/category/index');
    }


    /**
     * @permission delete_category
     */

    public function destroy(Request $request): JsonResponse
    {
        $result = $this->categoryRepo->delete($request->id);
        if($result){
            return response()->json([
                'error' => false,
                'message' => 'Xóa thành công danh mục'
            ]);
        }
        return response()->json([
            'error' => true,
        ]);
    }


    /**
     * @permission edit_category
     */
    public function show(Category $category){
        return view('category/edit',[
            'title' => 'Chỉnh sửa danh mục: ' . $category->name,
            'category' => $category,
            'list'=> $this->categoryRepo->getParent()
        ]);
    }

    public function update(Request $request, Category $category){
        $this->validate($request,[
            'name' => 'required'
        ],[
            'name' => 'Vui lòng nhập tên danh mục'
        ]);

        if($request->input('parent_id') != $category->id){
            $this->categoryRepo->update($category->id, $request->all());
        }
        return redirect('admin/category/index');

    }
}
