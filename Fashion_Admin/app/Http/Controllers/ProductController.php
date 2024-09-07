<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use FashionCore\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use FashionCore\Interfaces\IImageRepository;
use FashionCore\Interfaces\IProductRepository;
use FashionCore\Interfaces\ICategoryRepository;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProductController extends Controller
{
    protected $productRepo;
    protected $categoryRepo;
    protected $imageRepo;
    public function __construct(IProductRepository $productRepo, ICategoryRepository $categoryRepo, IImageRepository $imageRepo)
    {
        $this->productRepo = $productRepo;
        $this->categoryRepo = $categoryRepo;
        $this->imageRepo = $imageRepo;
    }   
    
    /**
     * @permission view_product
     */
    public function index(Request $request){
        $search = $request->input('search');
        $query = Product::query();
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', '%' . $search . '%')
                  ->orWhere('name', 'like', '%' . $search . '%');
            });
        }
        $products = $query->orderByDesc('created_at')->get();
        return view('product/index', [
            'title' => 'Danh sách sản phẩm',
            'products' => $products
        ]);
    }


    /**
    * @permission create_product
    */
    public function create(){
        return view('/product/add',[
            'title' => 'Thêm sản phẩm',
            'categories' => $this->categoryRepo->getAll(),
        ]);
    }

    public function store(Request $request){
        $this->validate($request,[
            'code' => 'required',
            'name' => 'required',
            'category_id' => 'required',
            'description' => 'required',
        ],[
            'code' => 'Vui lòng nhập mã sản phẩm',
            'name' => 'Vui lòng nhập tên sản phẩm',
            'category_id' => 'Bạn phải chọn danh mục',
            'description' => 'Vui lòng nhập mô tả',
        ]);
        $images = $request->file('images');
        if($request->hasFile('images')){
            foreach ($images as $image) {
                $validator = Validator::make(
                    ['image' => $image],
                    ['image' => 'image|mimes:jpeg,png,jpg|max:2048']
                );
    
                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput();
                }
            }    
        }

        try{
            DB::beginTransaction();
            $product_name = $request->input('name');
            $product = $this->productRepo->add([
                'code' => $request->input('code'),
                'name' => $product_name,
                'description' => $request->input('description'),
                'status' => 0,
                'category_id' => $request->input('category_id'),
                'slug' => Str::slug($product_name, '-')
            ]);
            if($product){
                if($request->hasFile('images')){
                    foreach($request->file('images') as $file)
                    {
                        // upload serve 
                        //$name = $file->getClientOriginalName();
                        //$file->move(public_path().'/images/', $name); 
                         
                        //up lên cloudinary
                        $uploadedResult = Cloudinary::upload($file->getRealPath())->getSecurePath();            
                        $this->imageRepo->add(['url' => $uploadedResult, 'product_id' => $product->id]);
                    }
                }   
                DB::commit();
                Session::flash('success','Thêm sản phẩm thành công!');     
                return redirect('/admin/product/index');
            }
            DB::rollBack();
            Session::flash('error', 'Thêm thất bại');
            return redirect()->back();
        }
        catch(\Exception $e){
            DB::rollBack();

            Session::flash('error', 'Đã xảy ra lỗi');
            Log::error('Đã xảy ra lỗi: ' . $e->getMessage());
            Session::flash('error', 'Đã xảy ra lỗi');
            return redirect()->back();
        }
    }

    public function getCodeProduct() :JsonResponse 
    {
        return response()->json([
            'code' => Str::random(10)
        ]);
    }

    
    /**
    * @permission delete_product
    */
    public function destroy(Request $request) : JsonResponse
    {
        try{
            $getImgs = $this->imageRepo->buildQuery(['product_id'=> $request->id])->get();
            if($getImgs->count() > 0){
                foreach($getImgs as $item){
                    // $file_path = public_path('images/'.$item->url);
                    // if(file_exists($file_path)){
                    //     unlink($file_path);
                    // }
                    $rs = $this->imageRepo->delete($item->id);
                }
            }
            $result = $this->productRepo->delete($request->id);
            if($result){
                return response()->json([
                    'error' => false,
                    'message' => 'Xóa thành công'
                ]);
            }
            return response()->json([
                'error' => true
            ]);
        }
        catch (\Exception $e){
            return response()->json([
                'error' => true
            ]);
        }
    }

    
    /**
    * @permission edit_product
    */
    public function show(Product $product){
        $id = $product->id;
        $productCacheKey = "product_details_admin_{$id}";
        $categoryCacheKey = "category_admin";
        
        $product = Cache::remember($productCacheKey, 60 , function () use($id){
            return $this->productRepo->buildQuery(['id' => $id])
            ->first();
        });
        $categories = Cache::remember($categoryCacheKey, 60, function () {
            return $this->categoryRepo->getAll();
        });
        return view('/product/edit',[
            'title' => 'Sửa sản phẩm',
            'categories' =>$categories,
            'product' => $product,
            'images'=> $product->images(),
        ]);
    }

    public function update(Request $request, Product $product){
        try{
            DB::beginTransaction();
            $product_name = $request->input('name');
            $product = $this->productRepo->update($product->id, [
                'name' => $product_name,
                'description' => $request->input('description'),
                'status' => 0,
                'category_id' => $request->input('category_id'),
                'slug' => Str::slug($product_name, '-')
            ]);
            if($product){
                if($request->hasFile('images')){
                    $getImgs = $this->imageRepo->buildQuery(['product_id'=> $product->id])->get();
                    if($getImgs->count() > 0){
                        foreach($getImgs as $item){
                            // $file_path = public_path('images/'.$item->url);
                            // if(file_exists($file_path)){
                            //     unlink($file_path);
                            // }
                            $rs = $this->imageRepo->delete($item->id);
                        }
                    }    
                    foreach($request->file('images') as $file)
                    {
                        // $name = time().'_'.$file->getClientOriginalName();
                        // $file->move(public_path().'/images/', $name); 
                        $uploadedResult =Cloudinary::upload($file->getRealPath())->getSecurePath();
                        $this->imageRepo->add(['url' => $uploadedResult, 'product_id' => $product->id]);
                    }
                }        
            }
            Cache::forget("product_details_{$product->id}");
            Cache::forget("product_details_admin_{$product->id}");
            DB::commit();
            Session::flash('success','Cập nhật thành công!');
        }
        catch(\Exception $e){
            DB::rollback();
            Session::flash('error', 'Đã xảy ra lỗi');
            Log::error('Đã xảy ra lỗi: ' . $e->getMessage());
            return redirect()->back();
        }
        return redirect('/admin/product/index');
    }

    /**
    * @permission change_status_product
    */
    public function active(Request $request){
        $status = $this->productRepo->buildQuery(['id'=> $request->id])->pluck('status')->first();
        $result = $this->productRepo->update($request->id, ['status'=>!$status]);
        if($result){
            return response()->json([
                'error' => false,
            ]);
        }
        return response()->json([
            'error' => true,
        ]);

    }

}
