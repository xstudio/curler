<?php
/**
 * 站点配置文件
 */
return array(
    'site' => 'u148',
    'from_site'=>'robot_u148',
    /**
     * Job get_root_urls函数中会调用此配置
     */
    'root_url'=>array(
        'url'=>'http://www.u148.net/text/%d.html', //抓取的分页链接
        'host'=>'http://www.u148.net', //连接必须包含此域名
        'xpath'=>"//div[@class='mainlist_content']/h1/a[2]", //根据此规则提取
        /**
         * 定义抓取从start页到end页
         */
        'fetch_page'=>array(
            'start'=>1,
            'end'=>2
        )
    ),
    /**
     * 从单个页面中提取的信息规则配置
     */
    'base'=>array(
        'title'=>array(
            'xpath'=>"//div[@class='u148content']/h1/a",
            'get'=>0    //获取第0个a连接里的内容
        ),
        'big_img'=>array(
            'xpath'=>"//div[@class='content']/p/a/img[@src]",
            'get'=>'src' //获取img里src属性直
        ),
        'content'=>array(
            'xpath'=>"//div[@class='content']",
            'get'=>'html' //直接获取div里的html，不会过滤html标签
        )
    )
);
