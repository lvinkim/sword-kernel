<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/10/13
 * Time: 1:36 PM
 */

namespace Lvinkim\SwordKernel\Component;


use Symfony\Component\DependencyInjection\Container;

interface WorkerMiddlewareInterface
{
    /**
     * $container 包含了所有已实例化的 Service 对象
     * WorkerMiddlewareInterface constructor.
     * @param Container $container
     */
    public function __construct(Container $container);

    /**
     * @param $settings
     */
    public function __invoke($settings);

    /**
     * 执行顺序，值越大，优先级越高
     * @return int
     */
    public function priority(): int;
}