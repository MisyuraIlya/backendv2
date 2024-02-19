<?php

namespace App\Service;

use App\ApiResource\XlxDocuments;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Mpdf\Mpdf;

class XlxDocumentService
{
    public function __construct()
    {
    }

    public function GetPdfOrder(XlxDocuments $data)
    {
        $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir().DIRECTORY_SEPARATOR.'mpdf']);
        $formated_date = date("Y-m-d_H:i:s");
        $html ='<div style="text-align: center;">
                <img style="width:100px;height: auto;display:inline-block;" src="https://digitrade.com.ua/ceremonitea/src/img/logo.png"/>
              </div>';
        $html .= '<h1 style="text-align: center;line-height:24px;margin-bottom:0;">' . $data->documentType .$data->userExtId . '</h1><br>';
        $html .= '<h2 style="text-align: center;margin:0;line-height:24px;">אסמכתא: #' . $data->documentNumber . '</h2><br>';
        $html .= "<table cellpadding='10' cellspacing='10' style='width:100%;border-bottom: 1px solid black;'>
                <tr style='outline: thin solid'>
                  <td style='font-weight: bold'>#</td>
                  <td style='font-weight: bold'>תמונה</td>
                  <td style='font-weight: bold'>מס' קטלוג</td>
                  <td style='font-weight: bold'>ברקוד</td>
                  <td style='font-weight: bold'>תיאור פריט</td>
                  <td style='font-weight: bold'>כמות</td>
                  <td style='font-weight: bold'>נטו ליחידה</td>
                  <td style='font-weight: bold'>סה״כ</td>
                 </tr>
              ";
        if (!empty($data->erpData->documents)) {
            foreach ($data->erpData->documents as $key => $line) {

                $html .= '<tr style="outline: thin solid;">
                      <td>' . $line->totalPrice . '</td>
                      <td>';
                $fileExists = file_exists(__DIR__ . $line->image);
                if($fileExists){
                    $html .= '<img style="width:50px;height: auto;" src="' . __DIR__ . $line->img. '"/></div>' . '</td>';
                }else{
                    $html .= '</td>';
                }

                $html .=    '<td>' . $line->sku . '</td>
                       <td>' . $line->barcode . '</td>
                       <td>' . $line->title . '</td>
                       <td>' . $line->quantity . '</td>
                       <td>' . number_format($line->price , 2, '.', "") . '</td>
                       <td>' . number_format($line->totalPrice , 2, '.', "") . '</td>
                    </tr>';

            }
        }

        $html .= '</table>';
        $html .= "<div style='text-align: left;'>
                  <div style='display:inline-block;'>
                    <span style='font-weight: bold'>סה״כ ללא מע״מ:</span>
                    <span style='border:1px solid black; padding:8px 16px;'>" .number_format($data->erpData->totalPriceBeforeTax  ,2, '.', '')  . "</span>
                  </div>
                  <div style='display:inline-block;'>
                    <span style='font-weight: bold'>סה״כ לתשלום:</span>
                    <span style='border:1px solid black; padding:8px 16px;'>" .number_format($data->erpData->totalPrice  ,2, '.', '') . "</span>
                  </div>
                </div>";



        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        $mpdf->SetDirectionality('rtl');
        $mpdf->WriteHTML($html);
        $fxls =  $formated_date . '_order_'. $data->documentNumber . '.pdf';
        $mpdf->Output($fxls,'F');

        return $fxls;
    }


    public function GetPdfKartesset()
    {

    }

    public function GetExcelOrder(XlxDocuments $data)
    {

        $formated_date = date("Y-m-d_H:i:s");
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', '#')
            ->setCellValue('B1', 'תמונה')
            ->setCellValue('C1', "מס' קטלוג")
            ->setCellValue('D1', "ברקוד")
            ->setCellValue('E1', 'תיאור פריט')
            ->setCellValue('F1', 'כמות')
            ->setCellValue('G1', 'נטו ליחידה')
            ->setCellValue('H1', 'סה״כ');

        $num = 0;
        if (!empty($result->data->products)) {
            $num = 2;
            foreach ($data->erpData->documents as $key => $line) {
                $singlePrice = $line->price;
                if($line->discount){
                    $singlePrice = $line->price - ($line->price * $line->discount / 100);
                }
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A'.$num, $line->quantity)
                    ->setCellValueExplicit('C'.$num, $line->sku,
                        \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('D'.$num, $line->barcode,
                        \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValue('E'.$num, $line->title)
                    ->setCellValue('F'.$num, $line->quantity)
                    ->setCellValue('G'.$num, number_format($singlePrice , 2, '.', ""))
                    ->setCellValue('H'.$num, number_format($line->totalPrice , 2, '.', ""));

                if($line->image){
                    $spreadsheet->getActiveSheet()->getCell('B'.$num)->getHyperlink()->setUrl($line->image);

                }

                $num++;
            }
        }

        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('G'.$num, "סה״כ ללא מע״מ:")
            ->setCellValue('H'.$num, number_format($data->erpData->totalPriceBeforeTax ,2, '.', ''));
        $num++;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('G'.$num, "סה״כ לתשלום:")
            ->setCellValue('H'.$num, $data->erpData->totalPrice);

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);

        $styleArray = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ]
        ];

        $spreadsheet->getActiveSheet()->getStyle('A1:H'.$num )->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('A1:H'.$num)->getFont()->setSize(18);
        $spreadsheet->getActiveSheet()
            ->getStyle('A1:H1')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('5d94a2');

        $spreadsheet->getActiveSheet()->getStyle("A1:H1")->getFont()->setBold(true)
            ->getColor()
            ->setRGB('FFFFFF');

        $spreadsheet->getActiveSheet()->setRightToLeft(true);
        $spreadsheet->getActiveSheet()->setTitle('Order');

        $writer = new Xlsx($spreadsheet);
        $fxls = $formated_date . '_order_'.$data->documentNumber.'.xlsx';
        $writer->save($fxls);
        return $fxls;
    }

    public function GetExcelKartesset()
    {

    }


}

