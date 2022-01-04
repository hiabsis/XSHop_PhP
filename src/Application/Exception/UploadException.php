<?php



namespace Application\Exception;

use Throwable;

/**
 * 参数校验失败
 */
class UploadException extends BaseException
{

    /**
     * 上传资源未找到
     */
    const ERROR_UPLOAD_FAILD = 300001;

    /**
     * 保存失败
     */
    const ERROR_UPLOAD_SAVE_FAILD = 300002;

    /**
     * 上传目录不存在或不可写入
     */
    const ERROR_UPLOAD_DIR_NOT_FOUND = 300003;

    /**
     * 非法上传文件
     */
    const ERROR_UPLOAD_ILLEGAL = 300004;

    /**
     * 上传文件大小不符
     */
    const ERROR_UPLOAD_SIZE_FAILD = 300005;

    /**
     * 上传文件MIME类型不允许
     */
    const ERROR_UPLOAD_MINI_FAILD = 300006;

    protected $errorInfo = [
        300001 => "上传资源未找到",
        300002 => "保存失败",
        300003 => "上传目录不存在或不可写入",
        300004 => "非法上传文件",
        300005 => "上传文件大小不符",
        300006 => "上传文件MIME类型不允许",

    ];
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $info = $this->errorInfo[$code];
        parent::__construct($info, $code, $previous);
    }

    public function getErrorInfo(): array
    {
        $error =  parent::getErrorInfo();
        $error['msg'] .= ' '. $this->getMessage();
        return  $error;
     }
    public function getLoggerInfo(): array
    {
        $error =  parent::getErrorInfo();
        $error['msg'] .= ' '. $this->getMessage();
        return  $error;
    }
}





