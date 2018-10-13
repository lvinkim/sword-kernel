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
use Swoole\Table;

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
     * @param Table $table
     * @return mixed
     */
    public function dispatchWorkerStart(int $workerId, Table $table);

    /**
     * 在 onRequest 回调事件中的处理函数
     * @param Request $request
     * @param Response $response
     * @param Table $table
     * @return mixed
     */
    public function dispatchRequest(Request $request, Response $response, Table $table);

}