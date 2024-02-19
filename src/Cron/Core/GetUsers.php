<?php

namespace App\Cron\Core;
use App\Entity\Error;
use App\Entity\User;
use App\Enum\UsersTypes;
use App\Erp\Core\ErpManager;
use App\Repository\ErrorRepository;
use App\Repository\UserRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetUsers
{

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly UserRepository $repository,
        private readonly ErrorRepository $errorRepository,
    )
    {}

    private function SyncChildren(array $childrens, User $parent)
    {
        foreach ($childrens as $itemRec) {
            $user = $this->repository->findOneByExIdAndPhone($itemRec->userExId, $itemRec->phone);
            if($itemRec->userExId) {
                if(empty($user)){
                    $user = new User();
                    $user->setExtId($itemRec->userExId);
                    $user->setPhone($itemRec->phone);
                    $user->setCreatedAt(new \DateTimeImmutable());
                    $user->setIsRegistered(false);
                    $user->setParent($parent);
                }
                $user->setRoles(UsersTypes::USER);
                $user->setRole(UsersTypes::USER);
                $user->setIsBlocked($itemRec->isBlocked);
                $user->setUpdatedAt(new \DateTimeImmutable());
                $user->setName($itemRec->name);
                $user->setIsAllowOrder(true);
                $user->setIsAllowAllClients(false);
                $user->setMaxCredit($itemRec->maxCredit);
                $user->setMaxObligo($itemRec->maxObligo);
                $user->setPayCode($itemRec->payCode);
                $user->setPayDes($itemRec->payDes);
                $user->setHp($itemRec->hp);
                $user->setTaxCode($itemRec->taxCode);
                $this->repository->createUser($user, true);
            }
        }
    }
    public function sync()
    {
        $response = (new ErpManager($this->httpClient, $this->errorRepository))->GetUsers();
        foreach ($response->users as $itemRec) {
            $user = $this->repository->findOneByExIdAndPhone($itemRec->userExId, $itemRec->phone);
            if($itemRec->userExId) {
                if(empty($user)){
                    $user = new User();
                    $user->setExtId($itemRec->userExId);
                    $user->setPhone($itemRec->phone);
                    $user->setCreatedAt(new \DateTimeImmutable());
                    $user->setIsRegistered(false);
                }
                $user->setRoles(UsersTypes::USER);
                $user->setRole(UsersTypes::USER);
                $user->setIsBlocked($itemRec->isBlocked);
                $user->setUpdatedAt(new \DateTimeImmutable());
                $user->setName($itemRec->name);
                $user->setIsAllowOrder(true);
                $user->setIsAllowAllClients(false);
                $user->setMaxCredit($itemRec->maxCredit);
                $user->setMaxObligo($itemRec->maxObligo);
                $user->setPayCode($itemRec->payCode);
                $user->setPayDes($itemRec->payDes);
                $user->setHp($itemRec->hp);
                $user->setTaxCode($itemRec->taxCode);
                $this->repository->createUser($user, true);

                $this->SyncChildren($itemRec->subUsers,$user);
            }
        }
    }
}