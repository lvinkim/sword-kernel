<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 26/09/2018
 * Time: 8:50 PM
 */

namespace Lvinkim\SwordKernel\Tests\App\Action;


use Lvinkim\SwordKernel\Component\ActionInterface;
use Lvinkim\SwordKernel\Component\ActionResponse;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Symfony\Component\DependencyInjection\Container;

class UpdateAction implements ActionInterface
{

    /**
     * $container 包含了所有已实例化的 Service 对象和 Action 对象
     * ActionInterface constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {

    }

    /**
     * Action 类入口函数
     * @param Request $request
     * @param Response $response
     * @param array $settings
     * @param \swoole_table $table
     * @return ActionResponse
     */
    public function __invoke(Request $request, Response $response, array $settings, \swoole_table $table): ActionResponse
    {
        $table->set("time", ["json" => time()]);

        return new ActionResponse(json_encode([
            "workerId" => $settings["workerId"] ?? "",
            "time" => $table->get("time"),
        ]));
    }
}