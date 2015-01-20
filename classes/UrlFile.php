<?php

/**
 * url去重[使用sae kvdb]
 */
class UrlFile
{
    private $_kv;
    public function __construct()
    {
    	$this->_kv = new SaeKV;
        $this->_kv->init();
    }
    /**
     * url去重
     */
    public function filter($urls)
    {
        if (!empty($urls))
        {
        	foreach ($urls as $k=>$url)
            {
            	if ($this->exists($url))
                    unset($urls[$k]);
            }
        }
        return $urls;
        
    }

    /**
     * 
     */
    public function push($url, $site)
    {
        return $this->_kv->set('link:' . md5($url), '{"site":"'.$site.'", "date":"'.date('Y/m/d H:i:s', time()).'"}');
        
    }
	public function exists($url)
    {
        return $this->_kv->get('link:' . md5($url)) !== false;
    }
    public function save($data)
    {
        if (empty($data)) return;
        
        foreach($data as $v)
        {
            $key = 'spider:'. $v['site_en'] .':' . date('YmdHis'.time()) . uniqid();
            $this->_kv->set($key, json_encode($v));
        }
    }
}
