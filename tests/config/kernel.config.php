<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/9/25
 * Time: 8:24 PM
 */

return (function () {

    return [
        "kernel" => "\Lvinkim\SwordKernel\Kernel",
        "vendor" => dirname(__DIR__) . "/../vendor/autoload.php",
        "settings" => __DIR__ . "/settings.config.php",
        "swoole" => [
//            "worker_num" => 4,
//            "dispatch_mode" => 3,
//            "max_request" => 3,
        ],
        "server" => [
            "host" => "0.0.0.0",
            "port" => "8080",
        ],
        "tableSize" => 1024,
        "tableColumns" => [
            ["name" => "json", "type" => swoole_table::TYPE_STRING, "size" => 1024]
        ],
    ];

})();
