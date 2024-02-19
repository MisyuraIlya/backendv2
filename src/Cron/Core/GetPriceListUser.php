<?php

namespace App\Cron\Core;

use App\Entity\Error;
use App\Entity\PriceListUser;
use App\Entity\User;
use App\Erp\Core\ErpManager;
use App\Repository\ErrorRepository;
use App\Repository\PriceListRepository;
use App\Repository\PriceListUserRepository;
use App\Repository\UserRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetPriceListUser
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly ErrorRepository $errorRepository,
        private readonly UserRepository $userRepository,
        private readonly PriceListRepository $priceListRepository,
        private readonly PriceListUserRepository $priceListUserRepository,
    )
    {
    }

    public function sync()
    {
//        try {
            $response = (new ErpManager($this->httpClient,$this->errorRepository))->GetPriceListUser();
            foreach ($response->priceLists as $itemRec){
                $user = $this->userRepository->findAllExtIdsUsers($itemRec->userExId);
                if($user){
                    foreach ($user as $userRec) {
                        assert($userRec instanceof User);
                        $priceList = $this->priceListRepository->findOneByExtId($itemRec->priceListExId);
                        if(!empty($priceList)){
                            $priceListUser = $this->priceListUserRepository->findOneByUserIdAndPriceListId($userRec->getId(),$priceList->getId());
                            if(empty($priceListUser)){
                                $priceListUser = new PriceListUser();
                                $priceListUser->setUserId($userRec);
                                $priceListUser->setPriceListId($priceList);
                                $this->priceListUserRepository->save($priceListUser,true);
                            }
                        }
                    }
                }

            }
//        } catch (\Exception $e) {
//            $error = new Error();
//            $error->setFunctionName('cron price list user');
//            $error->setDescription($e->getMessage());
//            $this->errorRepository->createError($error, true);
//        }

    }
}