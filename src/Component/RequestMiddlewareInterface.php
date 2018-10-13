<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/10/13
 * Time: 2:44 PM
 */

namespace Lvinkim\SwordKernel\Component;

use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Table;
use Symfony\Component\DependencyInjection\Container;

interface RequestMiddlewareInterface
{
    /**
     * $container 包含了所有已实例化的 Service 对象
     * RequestMiddlewareInterface constructor.
     * @param Container $container
     */
    public function __construct(Container $container);

    /**
     * @param Request $request
     * @param Response $response
     * @param $settings
     * @param Table $table
     */
    public function before(Request $request, Response $response, $settings, Table $table);

    /**
     * @param Request $request
     * @param Response $response
     * @param $settings
     * @param Table $table
     */
    public function after(Request $request, Response $response, $settings, Table $table);

    /**
     * 执行顺序，值越大，优先级越高
     * @return int
     */
    public function priority(): int;
}