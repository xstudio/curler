<?php

/**
 * 继承Job的实现类，必须声明命名空间，规则 站点\类别， 与文件夹名相同
 */
namespace u148\word;
class Main extends \Job
{
    public function load_config()
    {
        parent::load_config(dirname(__FILE__).'/config.php');
        $this->_handle['url'] = new UrlRedis;
        $this->_handle['log'] = new LogFile('/home/runtime.log');
        $this->_handle['log']->start_time = date('Y-m-d H:i:s', time());
        $this->_handle['log']->site = $this->site;
    }
    public function get_root_urls($urls='')
    {
        return array('http://www.u148.net/article/101199.html', 'http://www.u148.net/article/102707.html', 'http://www.u148.net/article/105281.html');
    }
    public function set_info(&$obj, &$check_map=null, &$url)
    {
        $this->_handle['url']->push($url);
        
        if (parent::set_info($obj, $check_map, $url) === true)
        {
            $key=md5($url);
            $this->data[$key]['from_url']=$url;
            
            $this->data[$key]['site']=$this->site;
            $this->data[$key]['from_site']=$this->from_site;
            $this->_handle['log']->success_count++;
        }
        else
            $this->_handle['log']->error_count++;
    }
    public function callback($data)
    {
        return $data;
    }

    public function __destruct() 
    {
        $this->_handle['log']->end_time = date('Y-m-d H:i:s', time());
        $this->_handle['log']->write();
    }

    public function submit()
    {
        print_r($this->data);
    }
}
