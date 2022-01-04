<?php
/**
 * User: 无畏泰坦
 * Date: 2021.12.15 17:11
 * Describe
 */

namespace Application\Model\Impl;

use Application\Domain\System\User;
use Application\Model\UserModelInterfaceInterface;
use Medoo\Medoo;
use PDO;

/**
 * Created on 2021.12.15 17:11
 * Created by 无畏泰坦
 * Describe
 */
class UserModel extends BaseModel implements UserModelInterfaceInterface
{
  public function __construct(PDO $conn, Medoo $medoo)
  {
      parent::__construct($conn, $medoo);
      $this->tableName = 'sys_user';
  }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.15 17:12
     * Describe 获取一天用户记录
     * @param User $user
     * @return User
     */
    public function getOneByUser(User $user): User
    {
        $where = [];
        $binds = [];
        foreach ($user as $k => $v) {
            if (!empty(trim($v))) {
                switch ($this->toCamelCase($k)) {
                    case 'id':
                    case 'username':
                    case 'password':
                        $where[] = " `$k` = ? ";
                        $binds[] = $v;
                }
            }
        }

        $data  = $this->findByCondition(where:$where,binds:$binds,page: 1,size:1,clazz:User::class );
        if (!empty($data)){
            return $data[0];
        }else{
            return  new User();
        }

    }
}
