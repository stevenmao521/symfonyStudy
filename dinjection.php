<?php
error_reporting(E_ALL & ~E_NOTICE);
#先了解容器概念
#DependencyInjection组件 依赖注入组件
require_once __DIR__.'/vendor/autoload.php';
#容器
use Symfony\Component\DependencyInjection\ContainerBuilder;

#定义mailer类注入服务
class Mailer
{
    private $trans;
    public function __construct($trans) {
        $this->trans = $trans;
    }
    public function getTrans() {
        echo $this->trans;
    }
}
#step 1
#简单创建容器
$container = new ContainerBuilder();
$container->register('mailer', 'Mailer')
        ->addArgument($trans);//加参数


#容器参数灵活性修改
#设置容器中的参数
$container->setParameter('mailer.trans', 'sendmail:hello world,are you ok');
$container->register('mailer', 'Mailer')
        ->addArgument('%mailer.trans%');

#获得容器服务 mailer
$mailer = $container->get('mailer');
//$mailer->getTrans();

#把服务中的服务注入到其他类中
class MailerBox
{
    protected $mailer;
    
    public function __construct(\Mailer $mailer) {
        $this->mailer = $mailer;
    }
    
    public function getMailer() {
        return $this->mailer;
    }
}

#step 2
#将MailerBox注册进容器，并将Mailer已参数方式注入MailerBox
#如未取得容器Mailer，用->addArgument(new Refrence('mailer'))实例化类通知容器将mailer注入

$container->register('box', 'MailerBox')
        ->addArgument($mailer);
$box = $container->get('box');
$box_mailer = $box->getMailer();
$box_mailer->getTrans();
?>