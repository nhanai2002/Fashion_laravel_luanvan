<?php

namespace App\Exports;

use FashionCore\Models\Size;
use FashionCore\Models\Color;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportTemplate implements WithHeadings, WithEvents
{
    public function headings(): array
    {
        return [
            'Mã sản phẩm',
            'Mã màu',
            'Mã kích thước',
            'Số lượng',
            'Giá gốc',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Thiết lập kích thước cột cho bảng chính
                $sheet->getColumnDimension('A')->setWidth(30);
                $sheet->getColumnDimension('B')->setWidth(15);
                $sheet->getColumnDimension('C')->setWidth(15);
                $sheet->getColumnDimension('D')->setWidth(20);
                $sheet->getColumnDimension('E')->setWidth(30);
             
                $sheet->getStyle('A1:E1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '333'], // Màu chữ 
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'color' => ['rgb' => '4CAF50'], // Màu nền xanh lá cây
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);



                // Color
                $colors = Color::all();

                // Bắt đầu ở cột H, dòng 2
                $startColumn = 'H';
                $startRow = 2;

                // Tiêu đề
                $sheet->setCellValue("{$startColumn}{$startRow}", 'Mã');
                $sheet->setCellValue(chr(ord($startColumn) + 1) . "{$startRow}", 'Tên màu');

                $startRow++;

                foreach ($colors as $color) {
                    $sheet->setCellValue("{$startColumn}{$startRow}", $color->id);
                    $sheet->setCellValue(chr(ord($startColumn) + 1) . "{$startRow}", $color->name);
                    $startRow++;
                }

                // Thiết lập style
                $sheet->getStyle("{$startColumn}2:" . chr(ord($startColumn) + 1) . ($startRow - 1))->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);


                // Size
                $sizes = Size::all();

                // cột bắt đầu
                $sizeStartColumn = chr(ord($startColumn) + 3); // Cột K
                $sizeStartRow = 2;

                // Tiêu đề 
                $sheet->setCellValue("{$sizeStartColumn}{$sizeStartRow}", 'Mã');
                $sheet->setCellValue(chr(ord($sizeStartColumn) + 1) . "{$sizeStartRow}", 'Kích thước');

                $sizeStartRow++;

                foreach ($sizes as $size) {
                    $sheet->setCellValue("{$sizeStartColumn}{$sizeStartRow}", $size->id);
                    $sheet->setCellValue(chr(ord($sizeStartColumn) + 1) . "{$sizeStartRow}", $size->name);
                    $sizeStartRow++;
                }

                // Thiết lập style
                $sheet->getStyle("{$sizeStartColumn}2:" . chr(ord($sizeStartColumn) + 1) . ($sizeStartRow - 1))->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);
            },
        ];
    }

}
