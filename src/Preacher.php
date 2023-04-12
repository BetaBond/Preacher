<?php

namespace Colorful\Preacher;

use Closure;
use JetBrains\PhpStorm\Pure;

/**
 * 傳教士 - [Preacher]
 *
 * @author beta
 */
class Preacher
{
    
    /**
     * 成功響應狀態碼
     *
     * @var int
     */
    const RESP_CODE_SUCCEED = 200;
    
    /**
     * 警告響應狀態碼
     *
     * @var int
     */
    const RESP_CODE_WARN = 400;
    
    /**
     * 失敗響應狀態碼
     *
     * @var int
     */
    const RESP_CODE_FAIL = 500;
    
    /**
     * 鑒權響應狀態碼
     *
     * @var int
     */
    const RESP_CODE_AUTH = 401;
    
    /**
     * 訪問被拒絕狀態碼
     *
     * @var int
     */
    const RESP_CODE_ACCESS_DENIED = 403;
    
    /**
     * 響應狀態碼鍵名稱
     *
     * @var string
     */
    const DEFAULT_KEY_CODE = 'code';
    
    /**
     * 響應消息鍵名稱
     *
     * @var string
     */
    const DEFAULT_KEY_MSG = 'msg';
    
    /**
     * 默認的 [json] 選項
     *
     * @var int
     */
    const DEFAULT_JSON_OPTIONS = JSON_PARTIAL_OUTPUT_ON_ERROR;
    
    /**
     * 默認的 [HTTP] 狀態碼
     *
     * @var int
     */
    const DEFAULT_HTTP_STATUS = 200;
    
    /**
     * 消息的生命周期
     *
     * @var Closure
     */
    private static Closure $messageActivity;
    
    /**
     * 響應碼
     *
     * @var int
     */
    private int $code;
    
    /**
     * 響應消息
     *
     * @var string
     */
    private string $msg;
    
    /**
     * 響應數據
     *
     * @var array
     */
    private array $data;
    
    /**
     * 使用消息生命周期
     *
     * @param  Closure  $closure
     */
    public static function useMessageActivity(Closure $closure): void
    {
        static::$messageActivity = $closure;
    }
    
    /**
     * 驗證並返回預設
     *
     * @param  bool  $allow
     * @param  mixed  $pass
     * @param  mixed  $noPass
     * @param  callable|null  $handle
     * @return mixed
     */
    public static function allow(
        bool $allow,
        mixed $pass,
        mixed $noPass,
        callable $handle = null,
    ): mixed {
        if (!empty($handle)) {
            call_user_func($handle);
        }
        
        return $allow ? $pass : $noPass;
    }
    
    /**
     * 返回基礎的默認信息
     *
     * @return self
     */
    #[Pure]
    public static function base(): self
    {
        return new self();
    }
    
    /**
     * 等同於 [setMsg()]
     *
     * @param  string  $msg
     * @return self
     */
    #[Pure]
    public static function msg(
        string $msg
    ): self {
        return new self(msg: $msg);
    }
    
    /**
     * 等同於 [setCode()]
     *
     * @param  int  $code
     * @return Preacher
     */
    #[Pure]
    public static function code(
        int $code
    ): self {
        return new self(code: $code);
    }
    
    /**
     * 同時設置 [msg] 和 [code]
     *
     * @param  int  $code
     * @param  string  $msg
     * @return Preacher
     */
    #[Pure]
    public static function msgCode(
        int $code,
        string $msg
    ): self {
        return new self(code: $code, msg: $msg);
    }
    
    /**
     * 等同於 [setPaging]
     *
     * @param  int  $page
     * @param  int  $prePage
     * @param  int  $total
     * @param  array  $data
     * @return self
     */
    public static function paging(
        int $page,
        int $prePage,
        int $total,
        array $data
    ): self {
        return (new self())->setPaging(
            $page,
            $prePage,
            $total,
            $data
        );
    }
    
    /**
     * 等同於 [setReceipt]
     *
     * @param  object  $data
     * @return self
     */
    public static function receipt(object $data): self
    {
        return (new self())->setReceipt($data);
    }
    
    /**
     * 等同於 [setRows]
     *
     * @param  array  $data
     * @return Preacher
     */
    public static function rows(array $data): self
    {
        return (new self())->setRows($data);
    }
    
    /**
     * 構造函數
     *
     * @param  int  $code
     * @param  string  $msg
     */
    private function __construct(
        int $code = self::RESP_CODE_SUCCEED,
        string $msg = ''
    ) {
        $this->code = $code;
        $this->msg = $msg;
        $this->data = [];
    }
    
    /**
     * 設置響應狀態碼
     *
     * @param  int  $code
     * @return self
     */
    public function setCode(int $code): self
    {
        $this->code = $code;
        
        return $this;
    }
    
    /**
     * 獲取響應狀態碼
     *
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }
    
    /**
     * 設置響應消息
     *
     * @param  string  $msg
     * @return self
     */
    public function setMsg(string $msg): self
    {
        $messageActivity = static::$messageActivity;
        $this->msg = $messageActivity($msg);
        
        return $this;
    }
    
    /**
     * 返回響應消息
     *
     * @return string
     */
    public function getMsg(): string
    {
        return $this->msg;
    }
    
    
    /**
     * 設置分頁信息
     *
     * @param  int  $page
     * @param  int  $prePage
     * @param  int  $total
     * @param  array  $data
     * @return self
     */
    public function setPaging(
        int $page,
        int $prePage,
        int $total,
        array $data
    ): self {
        $this->data['paging'] = (object) [
            'page' => $page,
            'prePage' => $prePage,
            'total' => $total,
            'rows' => $data,
        ];
        
        return $this;
    }
    
    /**
     * 獲取分頁信息
     *
     * @return object
     */
    public function getPaging(): object
    {
        return $this->data['paging'];
    }
    
    /**
     * 設置回執信息
     *
     * @param  object  $data
     * @return static
     */
    public function setReceipt(object $data): static
    {
        $this->data['receipt'] = $data;
        
        return $this;
    }
    
    /**
     * 返回回執信息
     *
     * @return object
     */
    public function getReceipt(): object
    {
        return $this->data['receipt'];
    }
    
    /**
     * 設置行數據
     *
     * @param  array  $data
     * @return static
     */
    public function setRows(array $data): static
    {
        $this->data['rows'] = $data;
        
        return $this;
    }
    
    /**
     * 獲取行數據
     *
     * @return array
     */
    public function getRows(): array
    {
        return $this->data['rows'];
    }
    
    /**
     * 判斷是否是成功的
     *
     * @return bool
     */
    public function isSucceed(): bool
    {
        return $this->code === self::RESP_CODE_SUCCEED;
    }
    
    /**
     * 導出響應
     *
     * @return Export
     */
    #[Pure]
    public function export(): Export
    {
        return new Export(array_merge([
            self::DEFAULT_KEY_CODE => $this->getCode(),
            self::DEFAULT_KEY_MSG => $this->getMsg(),
        ], $this->data));
    }
    
}