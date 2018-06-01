<?php

require_once __DIR__.'/vendor/autoload.php';
error_reporting(E_ALL & ~E_NOTICE);
# 缓存组件 cache
/*
Item（元素）
存有键值对的一个信息单元，键是信息的唯一识别，值是信息的内容；
Pool（池）
缓存元素的逻辑宝库。所有缓存操作（存储元素，寻找元素，等等）都通过pool来操作。如果需要，程序可以定义很多pool；
Adapter（适配器）
它实现了真正的缓存架构，用于把信息存到文件系统、数据库，等等。
组件提供了若干个“即拿即用”的adapter用于常见的缓存后端 (Redis, APCu, 等等)。
*/
#内建适配器
use Symfony\Component\Cache\Adapter\FilesystemAdapter;


$step = $_GET['step'];
$act = $_GET['act'];

#1基本用法
if ($step == 1) {
    $cache = new FilesystemAdapter();
    if ($act == 'add') {//缓存key
        #创建元素
        $key1 = $cache->getItem('key1');
        $key1->set('maozi1');
        $cache->save($key1);
    } elseif ($act == 'del') {//删除key
        $cache->deleteItem('key1');
    }

    #取出元素
    $key1_out = $cache->getItem('key1');
    if (!$key1_out->isHit()) {
        echo "key不存在";
    } else {
        echo $key1_out->get();
    }
}

#2.缓存数组
if ($step == 2) {
    $cache = new FilesystemAdapter();
    
    $arr_keys = $cache->getItem('arr_key');
    #存数组
    $arr_keys->set(array(
        'k1' => 'maozi1',
        'k2' => 'maozi2'
    ));
    $cache->save($arr_keys);
    #打印key
    print_r($arr_keys->getKey());
    #打印值
    print_r($arr_keys->get());
}

#3.缓存过期元素
if ($step == 3) {
    
    #缓存日期
    #$latestNews->expiresAfter(DateInterval::createFromDateString('1 hour'));
    #$mostPopularNews->expiresAt(new \DateTime('tomorrow'));
    
    $cache = new FilesystemAdapter();
    
    if ($act == 'add') {
        $ex_key = $cache->getItem('ex_key');
        #10秒失效
        $ex_key->expiresAfter(10);
        $ex_key->set('ex1', 'news1');
        $cache->save($ex_key);
    } elseif ($act == 'get') {
        $key = $cache->getItem('ex_key');
        if (!$key->isHit()) {
            echo "缓存key过期,再缓存10秒";
            $key->expiresAfter(10);
            $key->set('ex1', 'news1');
            $cache->save($key);
        } else {
            echo $key->get();
        }
    }
}

#删除缓存池全部缓存
$cache->clear();