<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/9/25
 * Time: 9:12 PM
 */

namespace Lvinkim\SwordKernel\Component;


use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Table;
use Symfony\Component\DependencyInjection\Container;

interface ActionInterface
{
    /**
     * $container 包含了所有已实例化的 Service 对象和 Action 对象
     * ActionInterface constructor.
     * @param Container $container
     */
    public function __construct(Container $container);

    /**
     * Action 类入口函数
     * @param Request $request
     * @param Response $response
     * @param array $settings
     * @param Table $table
     * @return ActionResponse
     */
    public function __invoke(Request $request, Response $response, array $settings, Table $table): ActionResponse;
}