<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Notification;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;

class SendNotificationProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly NotificationRepository $notificationRepository
    )
    {
        $this->DomainImage = $_ENV['DOMAIN_IMAGE'] ;
        $this->identifier = $_ENV['NOTIFICATION_IDENTIFIER'] ;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        // Handle the state
        $this->handleSendPush($data->identifier, $data->values, $data->notificationId);
    }

    private function handleSendPush(int $identifier, array $values, int $notificationId)
    {
        $notification = $this->notificationRepository->findOneById($notificationId);
        $userIds = [];
        if($identifier === 1) {
            $users = $this->userRepository->findAll();
            foreach ($users as $userRec){
                $userIds[] = $userRec->getId();
            }
        }

        $json = $this->PrepareSendApi($userIds, $notification);
        $response = $this->SendOneSignalApi($json);
        if(isset($response->data) && $response->data) {
            return $response->data;
        }
        return $response;
    }

    private function PrepareSendApi(array $userExIds, Notification $notificationId )
    {
        $obj = new \stdClass();
        $obj->classPoint = 'OneSignal';
        $obj->funcName = 'sendByArrayUsers';
        $obj->clientName = $this->identifier;
        $obj->userExIds = $userExIds;
        $obj->title = $notificationId->getTitle();
        $obj->description = $notificationId->getDescription();
        $obj->link = $notificationId->getLink();
        $obj->sendTo = null;
        $obj->img = $this->DomainImage .'notifications/'.$notificationId->getImage()->filePath;
        $obj->video = null;
        $obj->public =  null;
        $obj->unpublished = null;
        $obj->type = null;
        $obj->isRead = null;
        $obj->isFlag = null;

        return json_encode($obj);
    }

    private function SendOneSignalApi($data)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://digitrade.host/helpers/onesignal/src/index.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        //var_dump($response);

        if (curl_errno($curl)) {
            // Handle cURL error
            $error = curl_error($curl);
            curl_close($curl);
            //var_dump($error);
        }
        curl_close($curl);
        return json_decode($response);

    }
}
