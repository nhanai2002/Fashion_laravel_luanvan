<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Exports\ExportTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exports\WarehouseItemExport;
use App\Imports\WarehouseItemImport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use FashionCore\Models\WarehouseItem;
use Illuminate\Support\Facades\Session;
use FashionCore\Interfaces\ISizeRepository;
use FashionCore\Interfaces\IColorRepository;
use FashionCore\Interfaces\IProductRepository;
use FashionCore\Interfaces\IGoodsReceiptRepository;
use FashionCore\Interfaces\IWarehouseItemRepository;
use FashionCore\Interfaces\IGoodsReceiptDetailRepository;

class WarehouseItemController extends Controller
{
    protected $warehouseItemRepo;
    protected $productRepo;
    protected $colorRepo;
    protected $sizeRepo;
    protected $goodsReceiptRepo;
    protected $goodsReceiptDetailRepo;

    public function __construct(IProductRepository $productRepo, IWarehouseItemRepository $warehouseItemRepo,
                IColorRepository $colorRepo, ISizeRepository $sizeRepo, IGoodsReceiptRepository $goodsReceiptRepo, IGoodsReceiptDetailRepository $goodsReceiptDetailRepo)
    {
        $this->warehouseItemRepo = $warehouseItemRepo;
        $this->productRepo = $productRepo;
        $this->colorRepo = $colorRepo;
        $this->sizeRepo = $sizeRepo;
        $this->goodsReceiptRepo = $goodsReceiptRepo;
        $this->goodsReceiptDetailRepo = $goodsReceiptDetailRepo;
    }

    /**
     * @permission view_warehouse
     */
    public function index(){
        return view('/warehouse-item/index', [
            'title' => 'Danh sách sản phẩm đã nhập',
            'list' => $this->warehouseItemRepo->getAll()->groupBy('product_id')
        ]);
    }

     /**
     * @permission create_warehouse
     */
    public function create(){
        return view('/warehouse-item/add',[
            'title' => 'Nhập kho',
            'products' => $this->productRepo->getAll(),
            'colors' => $this->colorRepo->getAll(),
            'sizes' => $this->sizeRepo->getAll(),
        ]);
    }

    public function store(Request $request){
        try{
            DB::beginTransaction();
            
            $productIds = $request->input('product_id');
            $colorIds = $request->input('color_id');
            $sizeIds = $request->input('size_id');
            $basePrices = $request->input('base_price');
            $quantities = $request->input('quantity');
            $currentDateTime = Carbon::now();

            // phiếu nhập
            $result = $this->goodsReceiptRepo->add([
                'code' => 'GR_' . $currentDateTime->format('YmdHis'),
                'input_day' => $request->input('input_day'),
                'total' => 0,
                'user_id' => Auth::id()
            ]);
            $total = 0;
            for($i = 0; $i < count($productIds); $i++){
                // kho
                $check = $this->warehouseItemRepo->buildQuery([
                    'product_id' => $productIds[$i],
                    'size_id' => $sizeIds[$i],
                    'color_id' => $colorIds[$i]
                ])->first();
                if($check){
                    $check->quantity += $quantities[$i];
                    $check->save();

                    // chi tiết phiếu nhập
                    $this->goodsReceiptDetailRepo->add([
                        'base_price' => $basePrices[$i],
                        'quantity' => $quantities[$i],
                        'warehouse_item_id' => $check->id,
                        'goods_receipt_id' => $result->id,
                    ]);
                }
                else{
                    $goods = $this->warehouseItemRepo->add([
                        'quantity' => $quantities[$i],
                        'product_id' => $productIds[$i],
                        'color_id' => $colorIds[$i],
                        'size_id' => $sizeIds[$i],
                    ]); 
                        // chi tiết phiếu nhập
                    $this->goodsReceiptDetailRepo->add([
                        'base_price' => $basePrices[$i],
                        'quantity' => $quantities[$i],
                        'warehouse_item_id' => $goods->id,
                        'goods_receipt_id' => $result->id,
                    ]);   
                }
                $total += $quantities[$i]*$basePrices[$i];
            }

            if($total != 0){
                $this->goodsReceiptRepo->update($result->id, ['total' => $total]);
            }
            DB::commit();
            Session::flash('success','Nhập hàng thành công!');
            return redirect('admin/warehouse-item/index');
        }
        catch(\Exception $e){
            DB::rollBack();
            Session::flash('error', 'Đã xảy ra lỗi');
            Log::error('Đã xảy ra lỗi: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * @permission delete_warehouse
     */
    public function destroy(Request $request): JsonResponse
    {
        $result = $this->warehouseItemRepo->delete($request->id);
        if($result){
            return response()->json([
                'error' => false,
                'message' => 'Xóa thành công'
            ]);
        }
        return response()->json([
            'error' => true,
        ]);
    }

     /**
     * @permission edit_warehouse
     */
    public function show(WarehouseItem $item){
        return view('/warehouse-item/edit',[
            'title' => 'Chỉnh sửa hàng nhập',
            'item' => $item
        ]);
    }

    public function update(Request $request, WarehouseItem $item){
        if($request->input('sale_price') ){
            if($request->input('sale_price') >= $request->input('sell_price')){
                Session::flash('error','Giá khuyến mãi phải nhỏ hơn giá bán!');
                return redirect()->back();
            }  
        }
        $sell_price = $request->input('sell_price') ?? 0;
        $sale_price = $request->input('sale_price') ?? 0;
        $this->warehouseItemRepo->update($item->id, [
            'sell_price' => $sell_price,
            'sale_price' => $sale_price
        ]);
        return redirect('admin/warehouse-item/index');
    }

    /**
     * @permission export_warehouse
     */
    public function exportExcel(){
        $file_name = Carbon::now()->format('YmdHis');
        return Excel::download(new WarehouseItemExport, $file_name .'.xlsx');
    }

    /**
     * @permission import_warehouse
     */
    public function importExcel(Request $request){
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);
        try {
            DB::beginTransaction();
            Excel::import(new WarehouseItemImport, $request->file('file'));
            DB::commit();
            return redirect()->back()->with('success', 'Import thành công!');
        }catch (\Exception $e) {
            DB::rollBack();
            Log::info('Lỗi' . $e->getMessage());
            Log::info('Dòng' . $e->getLine());
            return redirect()->back()->with('error', 'Đã xảy ra lỗi');
        }
    }

    public function downloadImportTemplate()
    {
        return Excel::download(new ExportTemplate, 'import_template.xlsx');
    }

    
}
