<?php

namespace App\Erp\Core\Dto;

use App\Enum\DocumentsType;

class DocumentDto
{
    public $documentNumber;
    public DocumentsType $documentType;
    public $userName;
    public $userExId;
    public $agentExId;
    public $agentName;
    public $status;
    public $createdAt;
    public $updatedAt;
    public $total;


}