<?php
/**
 * Collect: 无畏泰坦
 * Date: 2021.12.15 16:55
 * Describe
 */

namespace Application\Service\System;

use Application\Constant\CartConstance;
use Application\Constant\ErrorEnum;
use Application\Domain\Product\Product;
use Application\Domain\System\Collect;
use Application\Exception\CommonException;
use Application\Exception\ModelException;
use Application\Exception\ServiceException;
use Application\Model\CartModelInterface;
use Application\Model\CollectMenuModelInterface;
use Application\Model\CollectionModelInterface;
use Application\Model\Impl\ProductModel;
use Application\Service\BaseService;
use Application\Service\CartServiceInterface;
use Application\Service\CollectionServiceInterface;
use Application\Service\Product\ProductService;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Created on 2021.12.15 16:55
 * Created by 无畏泰坦
 * Describe
 */
class CartService extends  BaseService implements CartServiceInterface
{

    private $cartModel;

    private $productService;
    /**
     * @var CollectionModelInterface
     */
    private $productModel;
    public function __construct(CartModelInterface $cartModel, ProductModel $productModel,ProductService $productService)
    {
        $this->cartModel = $cartModel;
        $this->productModel = $productModel;
        $this->productService = $productService;
    }



    /**
     * Collect: 无畏泰坦
     * Date: 2022.01.05 17:43
     * Describe  分页查询用户收藏
     * @param array $query
     * @return array
     */
    #[ArrayShape(['total' => "int", 'data' => "array"])] public function listCartByPage(array $query = []): array
    {
        $cartProduct = [];
        $total = $this->cartModel->countCart($query);
        $pageParams = $this->getPageParams($query,$total);
         $carts = $this->cartModel->findCart(queryCondition: $query,limit: $pageParams);


        if (!empty($carts)){
            foreach ($carts as &$cart){
                try {
                    $product = $this->productModel->getProductById($cart['product_id']);
                    $product->file_path = $this->productService->getProductShowImgAccessPath($product->id);
                    $product->cartId= $cart['id'];
                    $product->cartNumber= $cart['number'];
                    $cartProduct[] = $product;
                    $product->check = $cart['check'] === 1;
                }catch (\Exception $exception){
                    $product = new Product();
                    $product->file_path = "#";
                    $product->cartId= $cart['id'];
                    $product->cartNumber= 0;
                    $cartProduct[] = $product;
                    $product->check = $cart['check'] === 1;
                }

            }
        }
        return ['total' => $total,'data' => $cartProduct];
    }

    private function getCart(int $cartId):Product
    {
        $cart = $this->cartModel->getCart(query: ['id'=>$cartId]);
        $product = $this->productModel->getProductById($cart['product_id']);
        $product->file_path = $this->productService->getProductShowImgAccessPath($product->id);
        $product->cartId= $cart['id'];
        $product->cartNumber= $cart['number'];
        $product->check = $cart['check'] === 1;
        return $product;
    }

    /**
     * Collect: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe
     * @param int $collectId
     * @param int $userId
     * @return bool
     */
    public function removeCart(int  $cartId,int $userId): bool
    {
        $collect = $this->cartModel->getCart(query: ['user_id'=>$userId,'id'=>$cartId]);
        if (empty($collect)){
           return true;
        }
       return $this->cartModel->removeCart([$cartId]);
    }
    /**
     * Collect: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe 数据保存
     * @param array $cart
     * @return int
     */
    public function saveCart(array $cart): array
    {
        $collect = $this->cartModel->getCart(query: ['product_id'=>$cart['product_id'],'user_id'=>$cart['user_id']]);
        if (empty($collect)){
             $id = $this->cartModel->saveCart($cart);
             $cart = $this->getCart($id);
             return [
                 'code' => CartConstance::$SAVING_SUCCESE,
                 'cart' => $cart
             ];

        }

        if ($collect['number'] > CartConstance::$MAX_ALLWING_NUM){


        }

        ++$collect['number'];
        $this->cartModel->updateCart(update: ['number'=>$collect['number']],cartId:$collect['id'] );
        return [
            'code' => CartConstance::$ADD_SUCCESE,
        ];
    }

    public function updateCart(array $cart, int $cartId,int $userId): int
    {
        $queryCart = $this->cartModel->getCart(query: ['id'=>$cartId]);
        if ($queryCart['user_id'] !== $userId){
            // 修改其他用户的购物车信息
            throw  new CommonException(errorInfo: ErrorEnum::$ERROR_20003);
        }
        if ($cart['number'] > CartConstance::$MAX_ALLWING_NUM){
            return CartConstance::$NOT_ALLOWING;
        }
        $this->cartModel->updateCart(update: $cart,cartId: $cartId);
        return CartConstance::$ADD_SUCCESE;


    }


}
