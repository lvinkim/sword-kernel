<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/9/25
 * Time: 8:25 PM
 */

namespace Lvinkim\SwordKernel;


use Lvinkim\SwordKernel\Component\ActionInterface;
use Lvinkim\SwordKernel\Component\ActionResponse;
use Lvinkim\SwordKernel\Component\KernelInterface;
use Lvinkim\SwordKernel\Component\RequestMiddlewareInterface;
use Lvinkim\SwordKernel\Component\WorkerMiddlewareInterface;
use Lvinkim\SwordKernel\Processor\LoadProcessor;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Table;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Kernel implements KernelInterface
{
    private $container;
    private $settings;

    /**
     * @var WorkerMiddlewareInterface[]
     */
    private $workerMiddleware = [];

    /**
     * @var RequestMiddlewareInterface[]
     */
    private $requestMiddleware = [];

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
     * @throws \Exception
     */
    public function dispatchWorkerStart(int $workerId)
    {
        $this->settings["workerId"] = $workerId;

        $loadProcessor = new LoadProcessor($this->container);
        $loadProcessor->load($this->settings);

        $this->collectMiddleware();

        foreach ($this->workerMiddleware as $middleware) {
            $middleware->__invoke($this->settings);
        }
    }

    /**
     * 在 onRequest 回调事件中的处理函数
     * @param Request $request
     * @param Response $response
     * @param Table $table
     * @return mixed|void
     */
    public function dispatchRequest(Request $request, Response $response, Table $table)
    {
        $pathInfo = $request->server["path_info"] ?? "/";
        $routes = $this->settings["routes"];
        $actionClass = $routes[$pathInfo] ?? false;

        try {

            foreach ($this->requestMiddleware as $middleware) {
                $middleware->before($request, $response, $this->settings, $table);
            }

            try {
                /** @var ActionInterface $classObject */
                $classObject = $this->container->get($actionClass);
                $actionResponse = $classObject->__invoke($request, $response, $this->settings, $table);
            } catch (\Throwable $exception) {
                $actionResponse = new ActionResponse(json_encode(["message" => $exception->getMessage()]));
                $actionResponse->setStatusCode(500);
            }

            foreach ($this->requestMiddleware as $middleware) {
                $middleware->after($request, $response, $this->settings, $table, $actionResponse);
            }

        } catch (\Throwable $exception) {
            $actionResponse = new ActionResponse(json_encode(["message" => $exception->getMessage()]));
            $actionResponse->setStatusCode(500);
        }

        $response->status($actionResponse->getStatusCode());
        $response->header("Content-Type", $actionResponse->getContentType());

        $contentBlocks = str_split($actionResponse->getBody(), 2046 * 1024);
        foreach ($contentBlocks as $block) {
            $response->write($block);
        }
        $response->end();
    }

    /**
     * @throws \Exception
     */
    private function collectMiddleware()
    {
        $this->workerMiddleware = [];
        foreach ($this->container->getServiceIds() as $serviceId) {
            if (is_subclass_of($serviceId, WorkerMiddlewareInterface::class)) {
                /** @var WorkerMiddlewareInterface $middleware */
                $middleware = $this->container->get($serviceId);
                $this->workerMiddleware[$middleware->priority()] = $middleware;
            }
        }
        krsort($this->workerMiddleware, SORT_NUMERIC);

        $this->requestMiddleware = [];
        foreach ($this->container->getServiceIds() as $serviceId) {
            if (is_subclass_of($serviceId, RequestMiddlewareInterface::class)) {
                /** @var RequestMiddlewareInterface $middleware */
                $middleware = $this->container->get($serviceId);
                $this->requestMiddleware[$middleware->priority()] = $middleware;
            }
        }
        krsort($this->requestMiddleware, SORT_NUMERIC);
    }
}