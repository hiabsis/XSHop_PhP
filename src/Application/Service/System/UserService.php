<?php
/**
 * User: 无畏泰坦
 * Date: 2021.12.15 16:55
 * Describe
 */

namespace Application\Service\System;

use Application\Domain\System\User;
use Application\Exception\ModelException;
use Application\Exception\ServiceException;
use Application\Model\UserModelInterfaceInterface;
use Application\Service\BaseService;
use Application\Service\UserServiceInterface;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Created on 2021.12.15 16:55
 * Created by 无畏泰坦
 * Describe
 */
class UserService extends  BaseService implements UserServiceInterface
{
    private $userModel;
    public function __construct(UserModelInterfaceInterface $userModel)
    {
        $this->userModel = $userModel;
    }



    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 17:43
     * Describe 分页查询菜单
     * @param array $query
     * @return array
     */
    #[ArrayShape(['total' => "int", 'data' => "array"])] public function listUserByPage(array $query = []): array
    {
        $total = $this->userModel->countUser($query);
        $pageParams = $this->getPageParams($query,$total);
        $data = $this->listUserByPage($query);
        return ['total' => $total,'data' => $data];
    }



    public function updateMenu(array $updateUser, int $userId): bool
    {
       return  $this->userModel->updateMenuById($updateUser,$userId);
    }

    public function removeMenu(array $ids): bool
    {
       return $this->userModel->removeMenu($ids);
    }

    public function saveUser(array $user): int
    {
       return $this->userModel->saveUser($user);
    }

    public function getUser(string $username, string $password):array
    {
        return  $this->userModel->getUser(['username'=>$username,'password'=>$password]);
    }


}
