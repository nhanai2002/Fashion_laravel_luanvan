<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use FashionCore\Models\Order;
use FashionCore\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use FashionCore\Interfaces\IUserRepository;
use FashionCore\Interfaces\IOrderRepository;
use FashionCore\Interfaces\IProductRepository;

class HomeController extends Controller
{
    protected $productRepo;
    protected $userRepo;
    protected $orderRepo;

    public function __construct(IProductRepository $productRepo, IUserRepository $userRepo, IOrderRepository $orderRepo)
    {
        $this->productRepo = $productRepo;
        $this->userRepo = $userRepo;
        $this->orderRepo = $orderRepo;
    }   
    

    public function index(){
        $productsCount = $this->productRepo->getAll()->count();
        $customersCount = $this->userRepo->buildQuery(['role_id'=> 2])->get()->count(); 
        $orderTotal = $this->orderRepo->buildQuery(['order_status'=>4])
            ->get()
            ->sum(function ($order) {
                return $order->total - 15000;
        });

        $totals = Order::where('order_status', 4)
        ->whereYear('order_day', '=', date('Y'))
        ->groupBy(DB::raw('MONTH(order_day)'))
        ->orderBy(DB::raw('MONTH(order_day)'))
        ->selectRaw('MONTH(order_day) as month, SUM(total - 15000) as total')
        ->get();

        $totalResults = array_fill(0, 12, 0);

        foreach ($totals as $item) {
            $totalResults[$item->month - 1] = $item['total'];
        }
        $roundedTotals = array_map(function($value) {
            return round($value, 2);
        }, $totalResults);


        $totalSold = OrderItem::
        whereHas('order', function ($query) {
            $query->where('order_status', 4);
        })
        ->whereYear('order_items.created_at', '=', date('Y'))
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->groupBy(DB::raw('MONTH(orders.order_day)'))
        ->orderBy(DB::raw('MONTH(orders.order_day)'))
        ->selectRaw('MONTH(orders.order_day) as month, SUM(order_items.quantity) as total')
        ->get();
        $totalSoldResult = array_fill(0, 12, [
            'month' => 0,
            'total' => 0,
        ]);
    
        foreach ($totalSold as $item) {
            $totalSoldResult[$item->month - 1] = [
                'month' => $item->month,
                'total' => $item->total,
                'order_day' => Order::where('order_status', 4)
                    ->whereYear('order_day', '=', date('Y'))
                    ->whereMonth('order_day', '=', $item->month)
                    ->first()
            ];
        }


        $topProducts = OrderItem::query()
        ->whereHas('order', function ($query) {
            $query->where('order_status', 4);
        })
        ->leftJoin('warehouse_items', 'warehouse_items.id', 'order_items.warehouse_item_id')
        ->leftJoin('products','products.id', 'warehouse_items.product_id')
        ->groupBy('products.id', 'products.code', 'products.name')
        ->select('products.id as ProductId','products.code as code', 'products.name as name', DB::raw('SUM(order_items.quantity) as totalSold'), DB::raw('SUM(order_items.total) as productRevenue'))
        ->orderByDesc('TotalSold')
        ->get();
        
        $topUsers = Order::query()
        ->where('order_status', 4)
        ->select('users.id as UserId','users.username as username', DB::raw('COUNT(orders.id) as Purchases'), DB::raw('SUM(orders.total - 15000) as Total'))
        ->leftJoin('users', 'users.id', '=', 'orders.user_id')
        ->groupBy('users.id', 'users.username')
        ->orderByDesc('Total')
        ->take(5)
        ->get();

        return view('home/index',[
            'title' => 'Trang quản trị Admin',
            'productsCount'=>$productsCount,
            'customersCount'=>$customersCount,
            'orderTotal'=>$orderTotal,
            'totals'=>$roundedTotals,
            'totalSolds'=>$totalSoldResult,
            'topProducts' => $topProducts,
            'topUsers'=>$topUsers
        ]);
    }

}
