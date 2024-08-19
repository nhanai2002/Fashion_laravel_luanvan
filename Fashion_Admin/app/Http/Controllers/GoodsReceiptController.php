<?php

namespace App\Http\Controllers;

use Dompdf\Options;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use FashionCore\Models\GoodsReceipt;
use FashionCore\Models\WarehouseItem;
use FashionCore\Interfaces\IProductRepository;
use FashionCore\Interfaces\IGoodsReceiptRepository;
use FashionCore\Interfaces\IWarehouseItemRepository;
use FashionCore\Interfaces\IGoodsReceiptDetailRepository;

class GoodsReceiptController extends Controller
{
    protected $productRepo;
    protected $warehouseItemRepo;
    protected $goodsReceiptRepo;
    protected $goodsReceiptDetailRepo;

    public function __construct(IWarehouseItemRepository $warehouseItemRepo, IProductRepository $productRepo, IGoodsReceiptRepository $goodsReceiptRepo, IGoodsReceiptDetailRepository $goodsReceiptDetailRepo)
    {
        $this->productRepo = $productRepo;
        $this->warehouseItemRepo = $warehouseItemRepo;
        $this->goodsReceiptRepo = $goodsReceiptRepo;
        $this->goodsReceiptDetailRepo = $goodsReceiptDetailRepo;
    }


    /**
     * @permission view_goods_receipt
     */
    public function index(){
        return view('/goods-receipt/index',[
            'title' => 'Danh sách sản phẩm đã nhập',
            'list' => GoodsReceipt::orderBy('input_day', 'desc')->get(),
        ]);
    }

     /**
     * @permission detail_goods_receipt
     */
    public function detail(GoodsReceipt $item){
        return view('/goods-receipt/detail',[
            'title' => 'Danh sách sản phẩm đã nhập',
            'main' => $item,
            'details' => $this->goodsReceiptDetailRepo->buildQuery([
                'goods_receipt_id' => $item->id
            ])->get(),
        ]);
    }

    /**
     * @permission export_goods_receipt
     */
    public function exportPDF(GoodsReceipt $item){
        $details = $this->goodsReceiptDetailRepo->buildQuery(['goods_receipt_id' => $item->id])->get();
        $pdf = PDF::loadView('/export/view_pdf',[
            'title' => 'Phiếu nhập',
            'details' => $details,
            'main' => $item,
            'type'=>0
        ])->setPaper('a4')->setWarnings(false);
        return $pdf->download($item->code . '.pdf');
    }

    /**
     * @permission delete_goods_receipt
     */
    public function destroy(Request $request): JsonResponse
    {
        $result = $this->goodsReceiptRepo->delete($request->id);
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
}
