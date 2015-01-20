<?php

/**
 * 抓取实现类
 *
 * @function load_config 加载配置文件，默认为Main.php同级目录下config.php
 * 需要在子类中进行指定
 * @function get_root_urls 获取要抓取的url list.默认通过遍历分页url获取所有url
 * http://www.demo.com/article/list/%s.html %s指分页数
 * 如果是其它类型，子类需要覆盖此函数
 * @function do_work 调用curl进行批量抓取 并调用set_info函数将抓取回来的内容设置到$data中
 * 如果抓取回来的内容，需要进一步处理，请覆盖callback函数
 * @function submit 提交$data，可以将其写进数据库、文件等
 */
class Job
{
    /**
     * 用于日志等的句柄，可以使文件或者数据库连接，需要在子类指定
     */
    protected $_handle;
    public $data;
    /**
     * load config 
     */
    public function load_config($config_file='')
    {
        if(file_exists($config_file))
            $config_arr=include($config_file);

        foreach($config_arr as $param=>$value)
            $this->$param=$value;
    }

    /**
     * do fetch work
     */
    public function do_work()
    {
        $urls=$this->get_root_urls();
        Curl::rolling_curl(array($this, 'set_info'), $urls);
    }

    /**
     * submit jod like save data to db
     */
    public function submit()
    {
        print_r($this->data);
    }
    
    public function writeLog($file = '', $content = '') 
    {
        return file_put_contents($file, date('Y-m-d H:i:s') . ':'  . $content . "\n", FILE_APPEND);
    }

    public function set_info(&$obj, &$check_map=null, &$url)
    {
        if (empty($this->dom))
            $this->dom = new DOMDocument();
        if($obj['error']===TRUE) 
            return 'fetch error.';
        if(@$this->dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>' . $obj['results'])===false)
            return 'load html error.';

        if(($xml=simplexml_import_dom($this->dom))===false)
            return 'import dom error.';
        foreach($this->base as $column=>$param)
        {
            $fetch=$xml->xpath($param['xpath']);
            if (isset($fetch[0]) && !empty($fetch[0]))
            {
                if($param['get']==='html')
                    $tmp_data[$column]=preg_replace('/<[^>]*>$/', '', preg_replace('/^<[^>]*>/', '', $fetch[0][0]->asXml()));
                else
                    $tmp_data[$column]=strval($fetch[0][$param['get']]);
            }
        }
        
        $this->data[md5($url)]=$this->callback($tmp_data);
        return true;
        
    }

    public function get_root_urls()
    {
        if (empty($this->dom))
            $this->dom = new DOMDocument();
        $result=array();
        for($page=$this->root_url['fetch_page']['start']; $page<=$this->root_url['fetch_page']['end']; $page++)
        {
            
            if(@$this->dom->loadHTMLFile(sprintf($this->root_url['url'], $page))===false)
                continue;
            if(($xml=simplexml_import_dom($this->dom))===false)
                continue;

            $fetch=$xml->xpath($this->root_url['xpath']);
            if(!empty($fetch))
            {
                foreach($fetch as $link)
                {
                    if(strpos($link['href'], $this->root_url['host'])===false)
                        $result[]=$this->root_url['host'].strval($link['href']);
                    else
                        $result[]=strval($link['href']);
                }
            }
        }
        return array_unique($result);
    }
    public function callback($data)
    {
        return $data;
    }


}

