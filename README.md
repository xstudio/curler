# CURLER 您身边的WEB采集专家

使用说明
  
    主要用于批量采集指定网站的页面的特定信息，例如要采集小说网站分类小说的全部章节，或者视频网站视频分类视频的详细信息，通过简单的配置，即可实现。
    有任何问题请访问 www.xstudio.me 联系我
    @author 小笙
    @date 2014/07/21
    @link http://curler.xstudio.me

- 配置好apache+php环境，安装php curl扩展并开启.
- 下载该程序源码包，并放置于web服务器根目录（压缩包中u148文件夹为示例代码）.
- 新建站点文件夹，例如要抓取u148.net 文字分类的网页，则在程序根目录新建目录u148，u148目录下新建word.
- 拷贝u148/word下config.php，Main.php到站点文件夹下，并修改config中抓取配置和Main.php中命名空间.
- 修改程序根目录下doit.php $site变量，以及抓取的执行步骤，默认已经写好.
- 运行doit.php即可执行抓取程序.
- 可以将doit.php加入crontab，这样每天就可以定时批量的进行抓取.
