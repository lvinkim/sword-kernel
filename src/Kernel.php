<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/9/25
 * Time: 8:25 PM
 */

namespace Lvinkim\SwordKernel;


use Lvinkim\SwordKernel\Component\KernelInterface;
use Lvinkim\SwordKernel\Processor\LoadProcessor;
use Lvinkim\SwordKernel\Processor\RequestProcessor;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Kernel implements KernelInterface
{
    private $container;
    private $settings;

    /**
     * Kernel constructor.
     * @param $settings
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
        $this->container = new ContainerBuilder();
    }

    /**
     * 在 onWorkerStart 回调事件中的处理函数
     * @param int $workerId
     * @return mixed|void
     */
    public function dispatchWorkerStart(int $workerId)
    {
        $this->settings["workerId"] = $workerId;

        $loadProcessor = new LoadProcessor($this->container);
        $loadProcessor->onEvent($this->settings);
    }

    /**
     * 在 onRequest 回调事件中的处理函数
     * @param Request $request
     * @param Response $response
     * @param \swoole_table $table
     * @return mixed|void
     */
    public function dispatchRequest(Request $request, Response $response, \swoole_table $table)
    {
        $requestProcessor = new RequestProcessor($this->container);
        $actionResponse = $requestProcessor->onEvent($request, $response, $this->settings, $table);

        if (!$actionResponse->isSent()) {
            $response->header("Content-Type", $actionResponse->getContentType());
            $response->end($actionResponse->getBody());
        }
    }

    /**
     * @return ContainerBuilder
     */
    public function getContainer(): ContainerBuilder
    {
        return $this->container;
    }

    /**
     * @return mixed
     */
    public function getSettings()
    {
        return $this->settings;
    }
}