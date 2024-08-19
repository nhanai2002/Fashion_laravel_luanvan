<?php

namespace App\Exports;

use FashionCore\Models\Size;
use FashionCore\Models\User;
use FashionCore\Models\Color;
use FashionCore\Models\Product;
use FashionCore\Models\WarehouseItem;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WarehouseItemExport implements FromCollection, WithHeadings, WithColumnWidths,  WithStyles
{

    public function collection()
    {
        $list = WarehouseItem::all();
        $result = $list->map(function ($item) {
            $product = Product::find($item->product_id);
            return [
                $product ? $product->code : 'N/A',
                $product ? $product->name : 'N/A',
                Color::where('id', $item->color_id)->value('name') ?? 'N/A',
                Size::where('id', $item->size_id)->value('name') ?? 'N/A',
                $item->quantity,
                $item->sell_price,
                $item->sale_price,
            ];
        });

        return $result;
    }

    public function headings(): array
    {
        return [
            'Mã sản phẩm',
            'Tên sản phẩm',
            'Màu sắc',
            'Kích thước',
            'Số lượng',
            'Giá bán',
            'Giá khuyến mãi',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 100,
            'C' => 20,
            'D' => 20,
            'E' => 15,
            'F' => 20,
            'G' => 20,
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
            'alignment' => ['horizontal' => 'left'],
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
        // return [
        //     // Căn lề trái cho tất cả các ô
        //     1 => ['alignment' => ['horizontal' => 'center']], // Căn lề trái cho hàng đầu tiên (tiêu đề)

        //     // Thay đổi màu sắc tiêu đề
        //     1 => [
        //         'font' => [
        //             'bold' => true,
        //         ],
        //         'fill' => [
        //             'fillType' => 'solid',
        //             'color' => ['rgb' => '32CD32'], // Màu xanh lá cây
        //         ],
        //         'alignment' => [
        //             'horizontal' => 'center', // Căn lề trái cho tiêu đề
        //         ],
        //     ],
        // ];
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
