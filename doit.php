<?php

/**
 * 
 * 要执行的抓取文件
 *
 * 加载各个资源文件，站点配置文件等，并实例化操作
 * @link http://curler.xstudio.me
 */
header('Content-type:text/html; charset=utf-8');
$path=dirname(__FILE__);
$dom=new DOMDocument();

function autoLoad($class_name)
{
    global $path;
    include($path . 'classes/' . $class_name.'.php');
}
spl_autoload_register('autoLoad');

/**
 * 配置要抓取的站点，必须是站点名-类别这样的二维数组，文件存储应该是对应的二级目录
 */
$sites=array(
    'u148'=>array(
        'word'
    ),
);
foreach($sites as $site=>$classes)
{
    foreach($classes as $class)
    {
        include($path.$site.'/'.$class.'/Main.php');

        $obj=new Main();
        $obj->load_config();
        $obj->do_work();
        $obj->submit();
    }
}

