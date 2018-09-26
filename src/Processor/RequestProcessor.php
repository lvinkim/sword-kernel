<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 24/09/2018
 * Time: 9:07 PM
 */

namespace Lvinkim\SwordKernel\Processor;


use Lvinkim\SwordKernel\Component\ActionInterface;
use Lvinkim\SwordKernel\Component\ActionResponse;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Symfony\Component\DependencyInjection\Container;

class RequestProcessor
{
    private $container;

    /**
     * RequestProcessor constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $settings
     * @param \swoole_table $table
     * @return ActionResponse
     */
    public function onEvent(Request $request, Response $response, $settings, \swoole_table $table): ActionResponse
    {
        $pathInfo = $request->server["path_info"] ?? "/";

        $routes = $settings["routes"];

        $actionClass = $routes[$pathInfo] ?? false;

        try {
            /** @var ActionInterface $classObject */
            $classObject = $this->container->get($actionClass);
            $actionResponse = $classObject->__invoke($request, $response, $settings, $table);
        } catch (\Exception $exception) {
            $actionResponse = new ActionResponse(json_encode(["message" => $exception->getMessage()]));
        }

        return $actionResponse;
    }


}