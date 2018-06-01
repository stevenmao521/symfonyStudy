<?php
#Aseets组件
require_once __DIR__.'/vendor/autoload.php';

#assets组件
use Symfony\Component\Asset\Package;
#不加任何版本
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
#设置版本
use Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy;
#自定义版本策略
use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;
#资源群组
use Symfony\Component\Asset\PathPackage;
#资源放在不同域名下
use Symfony\Component\Asset\UrlPackage;
#统一包管理
use Symfony\Component\Asset\Packages;

#1
$package = new Package(new EmptyVersionStrategy());
#echo $package->getUrl('/image.png');
#echo $package->getVersion('/image.png');  

#2.加上版本的管理
$package_v = new Package(new StaticVersionStrategy('v1', '%s?version=%s'));
#echo $package_v->getUrl('/image.png');
#echo $package_v->getVersion('/image.png');

#3.自定义版本策略
class MyVersionStrategy implements VersionStrategyInterface
{
    private $version;
    public function __construct() {
        $this->version = date('Ymd');
    }
    public function getVersion($path) {
        return $this->version;
    }
    
    public function applyVersion($path) {
        return sprintf('%s?version=%s', $path, $this->version);
    }
}
$my_strategy = new MyVersionStrategy();
$package_v3 = new Package(new MyVersionStrategy());
#echo $package_v3->getUrl('/image.png');
#echo $package_v3->getVersion('/image.png');

#4. 资源群组
# 可以统一指定资源的路径 /static/images
$package_v4 = new PathPackage('/static/images', new StaticVersionStrategy('v1', '%s?version=%s'));
#echo $package_v4->getUrl('images.png');
#echo $package_v4->getVersion('images.png');

#5. 绝对Assets和CDNs
#那些把资源存放在不同的域名和CDN下的程序 (Content Delivery Networks) 应该使用 UrlPackage 类，以生成各自资源的绝对URL:
$package_v5 = new UrlPackage(
    'http://www.demo.com',
    new StaticVersionStrategy('v1', '%s?version=%s')
);
#echo $package_v5->getUrl('images.png');

#6. 统一包管理,已命名的包
# packages统一管理所有包资源，各个包引用不同路径
$strategy = new StaticVersionStrategy('v1');
$default_package = new Package($strategy);

$name_packages = array(
    'img' => new UrlPackage('http://www.demo.com/', $strategy),
    'doc' => new PathPackage('/static/deep/for/docs/', $strategy)
);
$package_v6 = new Packages($default_package, $name_packages);
echo $package_v6->getUrl('default.png');
echo $package_v6->getUrl('images.png', 'img');
echo $package_v6->getUrl('demo.doc', 'doc');
