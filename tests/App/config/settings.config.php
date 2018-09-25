<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/9/25
 * Time: 8:29 PM
 */

return (function () {

    date_default_timezone_set('Asia/Shanghai');

    (new Dotenv\Dotenv(dirname(__DIR__) . '/'))->load();

    "prod" === getenv("ENV") ? error_reporting(0) : null;

    return [
        "app" => "sword-kernel",
        "env" => getenv("ENV"),
        "projectDir" => dirname(__DIR__),
        "namespace" => "Lvinkim\SwordKernel\Tests\App",
        "routes" => require __DIR__ . "/routes.config.php",
    ];

})();
