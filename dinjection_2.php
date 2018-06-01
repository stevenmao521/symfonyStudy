<?php
error_reporting(E_ALL & ~E_NOTICE);
#先了解容器概念
#DependencyInjection组件 依赖注入组件
require_once __DIR__.'/vendor/autoload.php';
#容器
use Symfony\Component\Debug\Debug;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
#以Yaml格式导入
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
#开启Debug
Debug::enable();

#以配置文件方式来设置容器
$path = __DIR__.'/config/';
$container = new ContainerBuilder();
$loader = new YamlFileLoader($container, new FileLocator($path));
$loader->load('service.yml');

class Mailer
{
    private $trans;
    public function __construct($trans) {
        $this->trans = $trans;
    }
    public function getMsg() {
        echo $this->trans;
    }
}

class MailerBox
{
    protected $mailer;
    
    public function setMailer(\Mailer $mailer) {
        $this->mailer = $mailer;
    }
    public function getMailer() {
        return $this->mailer;
    }
}
$box = $container->get('box');
$box_mailer = $box->getMailer();
$box_mailer->getMsg();

?>