<?php

namespace App\Controller;

use App\helpers\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AgentProfileController extends AbstractController
{
    #[Route('/agentProfile/{agentId}', name: 'app_agent_profile')]
    public function index($agentId): Response
    {
        $this->agendId = $agentId;

        $res = [
            "totalPriceMonth" => 0,
            "totalPriceYear" => 0,
            "averageBasket"  => 0,
            "totalClients" => 0,

            "totalPriceDay" => 0,
            "totalDayCount" => 100,

            "totalMissions" => 0,
            "targetPrecent" => 0,
        ];

        return $this->json($res);

    }
}
