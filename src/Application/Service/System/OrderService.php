<?php
/**
 * @author     ：无畏泰坦
 * @date       ：Created in 2022.01.21 21:34
 * @description：
 * @modified By：
 * @version:     1.0
 */

namespace Application\Service\System;

use Application\Constant\ErrorEnum;
use Application\Domain\Product\Product;
use Application\Exception\CommonException;
use Application\Model\ApiMenuModelInterface;
use Application\Model\ApiModelInterface;
use Application\Model\Impl\ProductModel;
use Application\Model\MenuModelInterface;
use Application\Model\OrderModel;
use Application\Model\ProductModelInterface;
use Application\Model\UserModelInterface;
use Application\Service\ApiServiceInterface;
use Application\Service\BaseService;
use Application\Service\OrderServiceInterface;
use Application\Service\Product\ProductService;
use Application\Service\ProductServiceInterface;
use Application\Service\UserServiceInterface;

class OrderService extends BaseService implements OrderServiceInterface
{
    /**
     * @var
     */
    private $productModel;
    /**
     * @var OrderModel
     */
    private $orderModel;

    /**
     * @var
     */
    private $userModel;
    private $productService;

    private $menuModel;
    public function __construct(ProductServiceInterface $productService,OrderModel $orderModel,ProductModelInterface $productModel,UserModelInterface $userModel)
    {
        $this->productModel = $productModel;
        $this->userModel = $userModel;
        $this->orderModel = $orderModel;
        $this->productService = $productService;
    }

    public function listOrderByPage(array $query = []): array
    {
        $total = $this->orderModel->countOrder($query);
        $pageParams = $this->getPageParams($query,$total);
        $orders = $this->orderModel->findOrder(queryCondition: $query,limit: $pageParams);
        $orderMap = [];
        foreach ($orders as $order){
            $product = $this->productModel->getProductById($order['product_id']);
            $orderMap[$order['order_id']][] = [
                'product_id' =>   $order['product_id'],
                'name' => $product->name,
                'file_path' => $this->productService->getProductShowImgAccessPath($product->id),
                'price' => $order['price'],
                'number' => $order['number'],
                'create_time'=>$order['create_time'],
                'status' => $order['status'],
                'order_id' => $order['order_id'],
                'id' => $order['id'],
            ];
        }
        $respOrder = [];
        foreach ($orderMap as $key => $order){
            $total = 0;
            $respOrder[] = [
                'order_id' => $key,
                'products' => $order,
                'create_time'=>$order[0]['create_time'],
                'status' => $order[0]['status'],
            ];
        }
        return ['total' => $total,'data' => $respOrder];
    }

    public function updateOrder(array $updateApi, int $apiId,array $menuIds): bool
    {
        if (!empty($menuIds)){
            $this->apiMenuModel->removeApiMenu(deleteCondition: ['api_id'=>$apiId]);
            $apiMenus =  [];
            foreach ($menuIds as $menuId){
                $apiMenu = [];
                $apiMenu['api_id'] = $apiId;
                $apiMenu['menu_id'] = $menuId;
                $apiMenus[] = $apiMenu;
            }
            $this->apiMenuModel->saveApiMenu($apiMenus);
        }
        return  $this->apiModel->updateApiById($updateApi,$apiId);
    }

    public function removeOrder(array $ids): bool
    {
        return  $this->apiModel->removeApiByIds($ids);
    }

    public function saveOrder(int $userId, array $products,int $addressId,$token): int
    {

        $user = $this->userModel->getUser(['id'=>$userId]);
        $totalScore = 0;
        // 检查库存
        $updateProducts = [];
        // 生成订单
        $orders = [];
        $order_id = time();
        foreach ($products as $item) {
           $product =  $this->productModel->getProductById($item['id']);
           // 商品不存在 系统异常
           if (empty($product)){
               throw new CommonException(errorInfo: ErrorEnum::$ERROR_40002);
           }
           $totalScore += $product->price * $item['checkNum'];
           // 库存不足
           if ($product->price < $item['checkNum']){
               throw new CommonException(errorInfo: ErrorEnum::$ERROR_40003);
           }
            $orders[] = [
                'order_id' => $order_id,
                'number' =>$item['checkNum'],
                'product_id' => $product->id,
                'price' => $product->price,
                'address_id' =>$addressId,
                'user_id' => $userId
            ];
            $p =  new Product();
           $p->id = $product->id;
           $p->number = $product->number - $item['checkNum'];
           $updateProducts[] = $p;
        }
        // 检查积分
        if ($user['score'] < $totalScore){
            throw new CommonException(errorInfo: ErrorEnum::$ERROR_40001);
        }
        // 减积分
       $updateUser = [
           'score' => $user['score'] - $totalScore
       ];
        $this->userModel->updateUserById($updateUser,$userId);
        $this->userModel->updateUserScore($user['score'] - $totalScore,$userId,$token);


        // 减库存
        foreach ($updateProducts as  $item){
            $this->productModel->updateProduct($item);

        }
        $this->orderModel->saveOrder($orders);
        return  true;
    }

    public function getOrder(array $query): array
    {
        return $this->apiModel->getApi(queryCondition: $query);
    }

}