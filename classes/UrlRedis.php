<?php

/**
 * url去重[使用redis hash table]
 */
class UrlRedis
{
    private $_conn;
    const HOST = '*';
    const PORT = '6379';
    const TIMEOUT = 0;
    const TABLE = 'vspider_fetched_url';
    public function __construct()
    {
        try
        {
            if (empty($this->_conn)) 
            {
                $this->_conn = new Redis;
                $this->_conn->connect(self::HOST, self::PORT, self::TIMEOUT);
            }
        }
        catch(Exception $e)
        {
            return;
        }
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
    public function push($url)
    {
        $key = md5($url);
        return $this->_conn->hMset(self::TABLE, array($key => time()));
    }
	public function exists($url)
    {
        $key = md5($url);
        return $this->_conn->hExists(self::TABLE, $key);

    }
}
