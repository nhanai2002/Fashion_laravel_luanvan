<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Exports\ReportExport;
use FashionCore\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use FashionCore\Interfaces\IUserRepository;
use FashionCore\Interfaces\IOrderRepository;
use FashionCore\Interfaces\IProductRepository;

class ReportController extends Controller
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
    
    /**
     * @permission view_report
     */
    public function index(Request $request){
        $start_time = $request->input('start_time') ? Carbon::parse($request->input('start_time')) : null;
        $end_time = $request->input('end_time') ? Carbon::parse($request->input('end_time')) : null;    
        $keyword = $request->input('keyword');

        $query = OrderItem::whereHas('order', function ($query) {
            $query->where('order_status', 4);
        })
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->join('warehouse_items', 'order_items.warehouse_item_id', '=', 'warehouse_items.id')
        ->join('products', 'warehouse_items.product_id', '=', 'products.id') 
        ->join('categories', 'products.category_id', '=', 'categories.id');
        if($start_time && $end_time){
            $query->whereBetween('orders.order_day', [$start_time, $end_time]);
        }
        if($keyword && $keyword != ''){
            $query->where(function ($q) use ($keyword) {
                $q->where('products.code', 'like', "%{$keyword}%")
                  ->orWhere('products.name', 'like', "%{$keyword} %")
                  ->orWhere('products.name', 'like', "{$keyword} %")
                  ->orWhere('products.name', 'like', "% {$keyword}")
                  ->orWhere('products.name', '=', $keyword);
            });
        }
        $results = $query->groupBy('products.code','products.name', 'categories.name', 'orders.order_day')
        ->selectRaw('products.code as product_code,
            products.name as product_name, 
            categories.name as category_name, 
            SUM(order_items.quantity) as total_sold, 
            SUM(order_items.quantity * order_items.price) as total_revenue,
            MONTH(orders.order_day) as month, 
            YEAR(orders.order_day) as year')
            ->orderBy(DB::raw('YEAR(orders.order_day)'), 'desc')
            ->orderBy(DB::raw('MONTH(orders.order_day)'), 'desc')
            ->get();
        return view('report/index',[
            'title' => 'Báo cáo doanh thu',
            'results' => $results
        ]);
    }

      /**
     * @permission export_report
     */
    public function exportExcel(Request $request){
        $start_time = $request->input('start_time') ? Carbon::parse($request->input('start_time')) : null;
        $end_time = $request->input('end_time') ? Carbon::parse($request->input('end_time')) : null;
        $keyword = $request->input('keyword');
        $file_name = Carbon::now()->format('YmdHis');
        return Excel::download(new ReportExport($start_time, $end_time, $keyword), $file_name .'.xlsx');
    }
}
