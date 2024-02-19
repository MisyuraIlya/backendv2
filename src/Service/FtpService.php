<?php

namespace App\Service;

class FtpService
{
    private $ftpServer;
    private $ftpUsername;
    private $ftpPassword;
    private $ftpConnection;

    private $projectFolder;
    CONST LOCAL_PATH = '../../public/';
    private $publicPath;

    public function __construct(
        string $projectFolder,
        string $entireProjectFolder,
    )
    {
        $this->ftpServer =  $_ENV['VPS_1_SERVER'];
        $this->ftpUsername = $_ENV['VPS_1_USERNAME'];
        $this->ftpPassword = $_ENV['VPS_1_PASSWORD'];

        $this->publicPath = 'https://'.$projectFolder.'/'.$entireProjectFolder;
        $this->projectFolder = 'www/'.$projectFolder;
        $this->connect();
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    public function uploadFile(string $localFilePath,string $remoteFilePath)
    {
        if (!$this->ftpConnection) {
            throw new \Exception("FTP connection not established. Call connect() first.");
        }

        $upload = ftp_put(
            $this->ftpConnection,
            $this->projectFolder . '/' .$remoteFilePath,
            $localFilePath,
            FTP_BINARY
        );

        if (!$upload) {
            throw new \Exception("Failed to upload file: {$localFilePath} to {$remoteFilePath}");
        }

        return $this->publicPath . '/' .$remoteFilePath;
    }

    public function uploadAllImagesFromResizedFolder()
    {
        $resizedFolderPath =  __DIR__.'/../../public/imagesResized';
        if (!is_dir($resizedFolderPath)) {
            throw new \Exception("The 'imagesResized' folder does not exist locally.");
        }
        $uploadedFiles = [];
        $localFiles = scandir($resizedFolderPath);
        foreach ($localFiles as $file) {
            if ($file !== '.' && $file !== '..') {
                $localFilePath = $resizedFolderPath . '/' .$file;
                $remoteFilePath =  $file;
                $remoteUrl = $this->uploadFile($localFilePath, $remoteFilePath);
                $uploadedFiles[] = $remoteUrl;
            }
        }
        return $uploadedFiles;
    }

    private function connect()
    {
        $this->ftpConnection = ftp_connect($this->ftpServer);
        if (!$this->ftpConnection) {
            throw new \Exception("Could not connect to FTP server: {$this->ftpServer}");
        }

        $login = ftp_login($this->ftpConnection, $this->ftpUsername, $this->ftpPassword);
        if (!$login) {
            throw new \Exception("FTP login failed for user: {$this->ftpUsername}");
        }

        ftp_pasv($this->ftpConnection, true);
    }

    private function disconnect()
    {
        if ($this->ftpConnection) {
            ftp_close($this->ftpConnection);
        }
    }

}