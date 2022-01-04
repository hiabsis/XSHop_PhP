<?php

namespace Application\Helper;


use Application\Constant\SystemConstants;
use Application\Exception\UploadException;
use Psr\Http\Message\UploadedFileInterface;
use Slim\Psr7\UploadedFile;

/**
 * 文件上次辅助类
 */
class UploadHelper
{
    //文件上传目录
    private static $SAVE_DIR = "#";
    //上传文件大小限制
    private static $IMG_MAX_SIZE = 1024*1024*3;
    private static $MINE_IMG_TYPE = array('image/jpeg', 'image/png', 'image/gif');//允许上传的文件类型

    public static function setSaveDit($path){
        self::$SAVE_DIR = $path;
    }
    public static function moveUploadedFile(UploadedFileInterface $uploadedFile)
    {
        $directory = self::$SAVE_DIR;
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        // see http://php.net/manual/en/function.random-bytes.php
        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);
        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.31 13:10
     * Describe 获取文件访问路径
     * @param  $path
     */
    public static function getFileAccessPath( $path ): string
    {
        if ($path === null){
            return '#';
        }
        return  SystemConstants::$FILE_ACCESS_PATH_PREFIX.$path;
    }
    public static function saveImg(UploadedFileInterface $file)
    {
        $fileName = $file->getClientFilename() === 'blob'?'1.png':$file->getClientFilename();
        if (empty($file)) {
            throw new UploadException('', UploadException::ERROR_UPLOAD_FAILD);
        }
        //判断是否从浏览器端成功上传到服务器端
        if ($file->getError() === UPLOAD_ERR_OK) {
            //上传类型判断
            $flag = $file->getClientMediaType();
            if (!in_array($file->getClientMediaType(), self::$MINE_IMG_TYPE, true)) {
                throw new UploadException('', UploadException::ERROR_UPLOAD_MINI_FAILD);
            }
            //判断文件大小
            if ($file->getSize() > self::$IMG_MAX_SIZE) {
                throw new UploadException('文件大小超出', UploadException::ERROR_UPLOAD_SIZE_FAILD);
            }
            //获取存放上传文件的目录
            $sub_path =  date('Ymd') . '/';

            $path = SystemConstants::$PRODUCT_ROOT.SystemConstants::$FILE_ACCESS_PATH_PREFIX .$sub_path;
            if (!is_dir($path) && !mkdir($path, 0755, true) && !is_dir($path)) {
                throw new UploadException('保存图片失败，文件目录不存在', UploadException::ERROR_UPLOAD_DIR_NOT_FOUND);
            }
            //文件重命名,由当前日期 + 随机数 + 后缀名
            $file_name = date('YmdHis') . uniqid('', true) . strrchr($fileName, '.');
            $saveFilePath = $path .$file_name;
            $file->moveTo($saveFilePath);
            # 移动成功
            return array(
                'url' => $sub_path . $file_name,
                'mine' => $file->getClientMediaType(),
                'fileName' => $fileName,
                'fileSize' => $file->getSize()
            );


        } else {
            # 上传到临时文件夹失败，根据其错误号设置错误号
            throw new UploadException('非法上传', UploadException::ERROR_UPLOAD_ILLEGAL);

        }
    }


}
