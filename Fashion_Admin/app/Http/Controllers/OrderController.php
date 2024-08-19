<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use FashionCore\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use FashionCore\Interfaces\ISizeRepository;
use FashionCore\Interfaces\IUserRepository;
use FashionCore\Interfaces\IColorRepository;
use FashionCore\Interfaces\IOrderRepository;
use FashionCore\Interfaces\IProductRepository;
use FashionCore\Interfaces\IOrderItemRepository;
use FashionCore\Interfaces\IWarehouseItemRepository;

class OrderController extends Controller
{
    protected $orderRepo;
    protected $orderItemRepo;
    protected $userRepo;
    protected $productRepo;
    protected $colorRepo;
    protected $sizeRepo;
    protected $warehouseItemRepo;

    public function __construct(IOrderRepository $orderRepo, IOrderItemRepository $orderItemRepo, IUserRepository $userRepo,
        IProductRepository $productRepo, IColorRepository $colorRepo, ISizeRepository $sizeRepo, IWarehouseItemRepository $warehouseItemRepo)
    {
        $this->orderRepo = $orderRepo;
        $this->orderItemRepo = $orderItemRepo;
        $this->userRepo = $userRepo;
        $this->productRepo = $productRepo;
        $this->colorRepo = $colorRepo;
        $this->sizeRepo = $sizeRepo;
        $this->warehouseItemRepo = $warehouseItemRepo;
    }

      /**
     * @permission view_order
     */
    public function index(){
        return view('order/index',[
            'title' => 'Danh sách đơn hàng',
            'orders' => $this->orderRepo->getOrders(),
        ]);
    }

     /**
     * @permission edit_order
     */
    public function show(Order $order){
        return view('order/edit',[
            'title' => 'Cập nhật đơn hàng',
            'order'=> $order
        ]);
    }

    public function update(Request $request, Order $order): JsonResponse{
        try{
            $status = $request->input('status');
            if($status == 0){
                $order = $this->orderRepo->buildQuery(['id' => $order->id])->with([
                    'order_items',
                    'order_items.warehouse_item'
                ])->first();
                foreach($order->order_items as $item){
                    $warehouse = $this->warehouseItemRepo->buildQuery(['id' => $item->warehouse_item_id])->first();
                    $warehouse->quantity += $item->quantity;
                    $warehouse->save();
                }
                $order->order_status = 0;
                $order->save();
            }
            else{
                $order->order_status = $status + 1;
            }
            $order->save();    
            return response()->json([
                'error' => false,
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'error' => true,
            ]);
        }
    }


      /**
     * @permission export_order
     */
    public function exportPDF(Order $order){
        $details = $this->orderItemRepo->buildQuery(['order_id' => $order->id])->get();
        $pdf = PDF::loadView('/export/view_pdf',[
            'title' => 'Đơn hàng',
            'details' => $details,
            'main' => $order,
            'type'=>1
        ])->setPaper('a4')->setWarnings(false);

        return $pdf->download($order->code . '.pdf');
    }
}
