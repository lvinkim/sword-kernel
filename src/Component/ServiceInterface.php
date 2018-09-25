<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/9/25
 * Time: 9:14 PM
 */

namespace Lvinkim\SwordKernel\Component;


use Symfony\Component\DependencyInjection\Container;

interface ServiceInterface
{
    /**
     * $container 包含了所有已实例化的 Service 对象和 Action 对象
     * ActionInterface constructor.
     * @param Container $container
     * @param $settings array
     */
    public function __construct(Container $container, array $settings);
}