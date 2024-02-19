<?php

namespace App\Controller;

use App\helpers\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FtpService;
class FtpFileUploader extends AbstractController
{

    #[Route('/ftpFileUploader', name: 'ftp_uploader', methods: ['POST'])]
    public function ftp(Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true);
            $fileName = $data['fileName'];
            $sourceVps3 = $data['sourceVps3'];
            $sourceVps1 = $data['sourceVps1'];
            $ftpUplaodFilePath = (new FtpService(
                $_ENV['VPS_1_FOLDER'],
                $sourceVps3
            ))->uploadFile("media/$sourceVps3/$fileName","$sourceVps1/$fileName");

            return $this->json((new ApiResponse($ftpUplaodFilePath,'file uploaded'))->OnSuccess());
        } catch(\Exception $e) {
            dd($e->getMessage());
        }
    }
}