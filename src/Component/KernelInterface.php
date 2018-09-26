<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/9/25
 * Time: 6:25 PM
 */

namespace Lvinkim\SwordKernel\Component;


use Swoole\Http\Request;
use Swoole\Http\Response;

interface KernelInterface
{

    /**
     * KernelInterface constructor.
     * @param array $setting
     */
    public function __construct(array $setting);

    /**
     * 在 onWorkerStart 回调事件中的处理函数
     * @param int $workerId
     * @return mixed
     */
    public function dispatchWorkerStart(int $workerId);

    /**
     * 在 onRequest 回调事件中的处理函数
     * @param Request $request
     * @param Response $response
     * @param \swoole_table $table
     * @return mixed
     */
    public function dispatchRequest(Request $request, Response $response, \swoole_table $table);

}