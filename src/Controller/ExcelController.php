<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelController extends AbstractController
{
    #[Route('/excel', name: 'app_excel')]
    public function index(): Response
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        // Add data to the spreadsheet
        $spreadsheet->getActiveSheet()
            ->setCellValue('A1', 'Hello')
            ->setCellValue('B1', 'World');

        // Create a writer to save the spreadsheet to a file
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');

        // Create a temporary file to store the XLSX file
        $filename = tempnam(sys_get_temp_dir(), 'excel');
        $writer->save($filename);

        // Return the file as a response
        $response = new BinaryFileResponse($filename);
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'example.xlsx');

        return $response;
//        return $this->render('excel/index.html.twig', [
//            'controller_name' => 'ExcelController',
//        ]);
    }
}
