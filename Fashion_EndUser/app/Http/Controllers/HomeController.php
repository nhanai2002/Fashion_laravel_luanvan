<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use FashionCore\Helpers\Helper;
use FashionCore\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use FashionCore\Interfaces\ISizeRepository;
use FashionCore\Interfaces\IColorRepository;
use FashionCore\Interfaces\IOrderRepository;
use FashionCore\Interfaces\IProductRepository;
use FashionCore\Interfaces\ICategoryRepository;
use FashionCore\Interfaces\IOrderItemRepository;
use FashionCore\Interfaces\IWarehouseItemRepository;

class HomeController extends Controller
{   protected $productRepo;
    protected $categoryRepo;
    protected $warehouseItemRepo;
    protected $colorRepo;
    protected $sizeRepo;
    protected $orderRepo;
    protected $orderItemRepo;

    public function __construct(IProductRepository $productRepo, ICategoryRepository $categoryRepo, 
    IWarehouseItemRepository $warehouseItemRepo, IColorRepository $colorRepo, ISizeRepository $sizeRepo, IOrderRepository $orderRepo, IOrderItemRepository $orderItemRepo)
    {
        $this->productRepo = $productRepo;
        $this->categoryRepo = $categoryRepo;
        $this->warehouseItemRepo = $warehouseItemRepo;
        $this->colorRepo = $colorRepo;
        $this->sizeRepo = $sizeRepo;
        $this->orderRepo = $orderRepo;
        $this->orderItemRepo = $orderItemRepo;
    }

    public function index(Request $request){
        $search = $request->input('search');
        $products = Product::where('status', 1)->whereHas('warehouse_items');
        $products= Product::distinct()
            ->select('products.id','products.name')
            ->rightJoin('warehouse_items', function ($join) {
                $join->on('products.id', '=', 'warehouse_items.product_id')
                ; 
        })->where('products.status', 1);
        if(!empty($search)){
            $products = $products->where(function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                      ->orWhere('code', 'like', '%'.$search.'%');
            });
        }
        $products = $products->orderBy('sell_price','asc')->limit(12)->get();
        return view('/home/index',[
            'title' => 'Trang chủ',
            'products' => $products
        ]);
    }

    public function detail($id, $slug = ''){
        $product = $this->productRepo->buildQuery(['id' => $id])
        ->with(['warehouse_items', 'ratings']) // Thêm 'ratings' để lấy đánh giá
        ->first();
        $list_products = Product::where('status', 1)->whereHas('warehouse_items')->limit(8);
        if($product == null || $product->warehouse_items->count() == 0){
            return redirect()->back();
        }
        $ratings = $product->ratings;
        
        // Tính tổng số đánh giá
        $totalRatings = $ratings->count();
    
        // Tính điểm đánh giá trung bình
        $averageRating = $totalRatings > 0 ? $ratings->avg('rating') : 0;
        return view('home/detail',[
            'title' => 'Chi tiết sản phẩm',
            'product' => $product,
            'list_products' => $list_products->get(),
            'warehouse_items' => $product->warehouse_items,
            'sizes'=>$this->sizeRepo->getAll(),
            'colors' => $this->colorRepo->getAll(),
            'ratings' => $product->ratings,
            'averageRating' => $averageRating,
            'totalRatings' => $totalRatings
        ]);

    }

    public function updatePriceDetail(Request $request) :JsonResponse
    {
        $data = $this->warehouseItemRepo->buildQuery([
            'product_id' => $request->product_id,
            'color_id' => $request->color_id,
            'size_id' => $request->size_id
            ])->first();
        
        $haveSizes = $this->warehouseItemRepo->buildQuery([
                'product_id' => $request->product_id,
                'color_id' => $request->color_id
            ])->pluck('size_id')->toArray();
        $haveColors = $this->warehouseItemRepo->buildQuery([
                'product_id' => $request->product_id,
                'size_id' => $request->size_id
            ])->pluck('color_id')->toArray();
        if($data){
            return response()->json([
                'error' => false,
                'price' => price($data->sell_price,  $data->sale_price, $data->quantity),
                'haveSizes' => $haveSizes,
                'haveColors' => $haveColors,
            ]);   
        }
        return response()->json([
            'error' => true
        ]);
    }

    public function orderHistory($status = 0){
        $status = (int) $status;

        $orders = $this->orderRepo->buildQuery(['user_id' => Auth::id()])->with([
            'order_items',
            'order_items.warehouse_item',
            'order_items.warehouse_item.product',
            'ratings'  
        ]);
        if($status != 0){
            $orders = $orders->where('order_status', $status - 1)->get();
        }
        if($status == 0){
            $orders = $orders->orderByDesc('order_day')->get();
        }


        foreach ($orders as $order) {
            $order->isReviewed = $order->ratings->where('user_id', Auth::id())->isNotEmpty();
        }

        return view('/home/order-history',[
            'title' => 'Lịch sử đơn hàng',
            'orders' => $orders,
            'active'=> $status != 0 ? $status : 0
        ]);
    }




    // Hiển thị sản phẩm trong từng danh mục
    public function showCategories($id, $slug = ''){
        $category = $this->categoryRepo->getCategory($id)->first();
        if (!$category) {
            return redirect()->back();
        }
        $products = $this->productRepo->buildQuery(['category_id' => $id])
        ->with('warehouse_items')->paginate(6);

        return view('home/category',[
            'title' => 'Danh mục',
            'products' => $products,
            'category' => $category,
        ]);
    }
    //lọc sản phẩm 
    public function filterProducts(Request $request){
        $search = $request->input('search');
        $products= Product::distinct()
            ->select('products.id','products.name')
            ->rightJoin('warehouse_items', function ($join) {
                $join->on('products.id', '=', 'warehouse_items.product_id')
                ; 
        })->where('products.status', 1);

        if(!empty($search)){
            $products = $products->where(function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                      ->orWhere('code', 'like', '%'.$search.'%');
            });
        }     
        // Lọc theo giá
        if ($request->has('price')) {
            $prices = $request->input('price');
            $products->where(function ($query) use ($prices) {
                $query->where(function ($query) use ($prices) {
                    foreach ($prices as $priceRange) {
                        // tách chuỗi $priceRange thành min và max
                        [$min, $max] = explode('-', $priceRange);
                        // lọc điều kiện nằm từ min đến max
                        $query->orWhere(function ($query) use ($min, $max) {
                            $query->where(function ($query) use ($min, $max) {
                                $query->whereBetween('warehouse_items.sale_price', [$min, $max])
                                      ->orWhere(function ($query) use ($min, $max) {
                                          $query->where('warehouse_items.sale_price', '=', 0)
                                                ->whereBetween('warehouse_items.sell_price', [$min, $max]);
                                      });
                            });
                        });
                    }
                });
            });
        }
        // Lọc theo màu
        if ($request->has('color')) {
            $colors = $request->input('color');
            $products->whereIn('warehouse_items.color_id', $colors);
        }
        // Lọc theo kích thước
        if ($request->has('size')) {
            $sizes = $request->input('size');
            $products->whereIn('warehouse_items.size_id', $sizes);
        }
        // sắp xếp tăng/giảm theo giá
        if ($request->orderby) {
            $orderby = $request->orderby;
            switch ($orderby) {
                case 'asc':
                    $products->orderByRaw('COALESCE(NULLIF(warehouse_items.sale_price, 0), warehouse_items.sell_price) ASC');
                    break;
                case 'desc':
                    $products->orderByRaw('COALESCE(NULLIF(warehouse_items.sale_price, 0), warehouse_items.sell_price) DESC');
                    break;
                default:
                    $products->orderBy('products.id', 'ASC');
                    break;
            }
        }
        return view('/home/store', [
            'title' => 'Cửa hàng',
            'products' => $products->paginate(12)->onEachSide(0),
            'sizes' => $this->sizeRepo->getAll(),
            'colors' => $this->colorRepo->getAll(),
        ]);
    }

}


