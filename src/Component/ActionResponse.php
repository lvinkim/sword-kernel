<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/9/25
 * Time: 9:13 PM
 */

namespace Lvinkim\SwordKernel\Component;


class ActionResponse
{
    private $statusCode = 200;

    /**
     * header 的 content type
     * @var string
     */
    private $contentType = "application/json;charset=utf-8";

    /**
     * http 报文 body
     * @var string
     */
    private $body = "{}";

    /**
     * ActionResponse constructor.
     * @param string $body
     * @param string $contentType
     * @param int $statusCode
     */
    public function __construct(string $body = "{}", $contentType = "application/json;charset=utf-8", $statusCode = 200)
    {
        $this->setBody($body);
        $this->setContentType($contentType);
        $this->setStatusCode($statusCode);
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * @param string $contentType
     */
    public function setContentType(string $contentType): void
    {
        $this->contentType = $contentType;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }
}