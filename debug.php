<?php
#Debug组件
require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\Debug\Debug;

#开启debug组件
#enable() 方法注册了一个error handler，一个exception handler以及 一个特殊的class loader。
Debug::enable();

?>