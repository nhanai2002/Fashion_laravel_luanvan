<?php

namespace App\Exports;

use FashionCore\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExport implements FromCollection, WithHeadings, WithColumnWidths,  WithStyles
{
    public $start_time;
    public $end_time;
    public $keyword;
    
    public function __construct($start_time, $end_time, $keyword)
    {
        $this->start_time = $start_time;
        $this->end_time = $end_time;
        $this->keyword = $keyword;
    }
    public function collection()
    {
        $query = OrderItem::whereHas('order', function ($query) {
            $query->where('order_status', 4);
        })
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->join('warehouse_items', 'order_items.warehouse_item_id', '=', 'warehouse_items.id')
        ->join('products', 'warehouse_items.product_id', '=', 'products.id') 
        ->join('categories', 'products.category_id', '=', 'categories.id');

        if ($this->start_time && $this->end_time) {
            $query->whereBetween('orders.order_day', [$this->start_time, $this->end_time]);
        }

        if ($this->keyword && $this->keyword != '') {
            $query->where(function ($q) {
                $q->where('products.code', 'like', "%{$this->keyword}%")
                ->orWhere('products.name', 'like', "%{$this->keyword} %")
                ->orWhere('products.name', 'like', "{$this->keyword} %")
                ->orWhere('products.name', 'like', "% {$this->keyword}")
                ->orWhere('products.name', '=', $this->keyword);
            });
        }

        $rows = $query->groupBy('products.code','products.name', 'categories.name', 'orders.order_day')
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

        $result = $rows->map(function ($item) {
            return [
                $item->category_name ,
                $item->product_code ,
                $item->product_name ,
                $item->total_sold ,
                $item->total_revenue ,
                ];
            });
    
            return $result;
    }

    public function headings(): array
    {
        return [
            'Danh mục',
            'Mã sản phẩm',
            'Tên sản phẩm',
            'Lượt bán',
            'Doanh thu',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 40,
            'B' => 40,
            'C' => 100,
            'D' => 10,
            'E' => 50,

        ];
    }

    public function styles(Worksheet $sheet)
    {
    
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $sheet->getStyle("A2:{$highestColumn}{$highestRow}")->applyFromArray([
                    'alignment' => ['horizontal' => 'center'],
            ]);
        $sheet->getStyle('B')->applyFromArray([
            'alignment' => ['horizontal' => 'center'],
            ]);

        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => 'solid',
                'color' => ['rgb' => '32CD32'], // Màu xanh lá cây
            ],
            'alignment' => [
                'horizontal' => 'center', // Căn lề giữa cho tiêu đề
            ],
        ]);
        // Thay đổi màu nền cho các hàng dữ liệu xen kẽ
        $this->applyRowColors($sheet, $highestRow);

    }

    private function applyRowColors(Worksheet $sheet, $highestRow)
    {
        $colors = ['D9EAD3', 'C6E2B3']; // Màu xanh lá cây xen kẽ

        for ($row = 2; $row <= $highestRow; $row++) {
            $colorIndex = ($row % 2) ? 0 : 1; // Chọn màu dựa trên chỉ số hàng
            $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                'fill' => [
                    'fillType' => 'solid',
                    'color' => ['rgb' => $colors[$colorIndex]],
                ],
            ]);
        }
    }

}
