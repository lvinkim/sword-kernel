<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 24/09/2018
 * Time: 10:41 PM
 */


return (function () {

    return [
        "/" => \Lvinkim\SwordKernel\Tests\App\Action\IndexAction::class,
        "/update" => \Lvinkim\SwordKernel\Tests\App\Action\UpdateAction::class,
    ];

})();
