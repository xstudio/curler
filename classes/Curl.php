<?php

/**
 * CURL页面抓取类 
 */
class Curl
{

    /**
     * get方式抓取页面内容
     *
     * @param string $url 
     * @param boolean $is_follow follow 302 location
     */
    public static function getData($url='', $is_follow=false)
    {
        $agentStr = 'Mozilla/5.0 (Windows NT 5.1; rv:20.0) Gecko/20100101 Firefox/20.0';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_USERAGENT, $agentStr);
        curl_setopt($ch, CURLOPT_HEADER, 0); 
        curl_setopt($ch, CURLOPT_NOSIGNAL, true);
        if($is_follow)
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1); 

        $data = curl_exec($ch);

        if (false === $data)
        {
            curl_close($ch);
            return false;
        }   

        curl_close($ch);
        return $data;
    }
    public static function getDataRef($url='', $ref='')
    {
        $agentStr = 'Mozilla/5.0 (Windows NT 5.1; rv:20.0) Gecko/20100101 Firefox/20.0';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_USERAGENT, $agentStr);
        curl_setopt($ch, CURLOPT_HEADER, 0); 
        curl_setopt($ch, CURLOPT_NOSIGNAL, true);
        curl_setopt($ch, CURLOPT_REFERER, $ref);
        $data = curl_exec($ch);

        if (false === $data)
        {
            curl_close($ch);
            return false;
        }   

        curl_close($ch);
        return $data;
    }

    /**
     * 批量抓取一系列页面内容 
     *
     * @param mixed $callback 指定每次抓取完成后要调用的回调函数，一般用来对抓取回来的内容进行赋值或者存储
     * 因为是在类中调用，所以此处$callback为array($obj, 'function') 
     * @param array $org_urls 用于批量抓取的url_list
     */
    public static function rolling_curl($callback, &$org_urls) 
    {
        $cb_ret = 0;

        if (!is_array($org_urls) || count($org_urls) <= 0)
            return false;

        $office = 128;
        $urls = array_slice($org_urls, 0, $office);
        $sec_urls = array_slice($org_urls, $office);
        $queue = curl_multi_init();
        $map = array();

        foreach ($urls as $key=>$url)
        {
            self::curl_init_ex($url, $queue, $map);
        }

        $responses = NULL;//array();    

        do {
            while (($code = curl_multi_exec($queue, $active)) == CURLM_CALL_MULTI_PERFORM) ;

            if ($code != CURLM_OK)  break; 

            // a request was just completed -- find out which one  
            while ($done = curl_multi_info_read($queue))
            {
                // get the info and content returned on the request             
                $info = NULL;//curl_getinfo($done['handle']);    
                $error = curl_error($done['handle']);
                if (0 != strlen($error))
                {
                    echo("time out :  ".$map[(string) $done['handle']]."  cnt=".count($map)."\n");
                    $error = true;
                    self::curl_init_ex($map[(string) $done['handle']], $queue, $map);
                    continue;
                }else{
                    $error = false;
                    $page = curl_multi_getcontent($done['handle']);

                    $fnArgNext = array('url'=>$map[(string) $done['handle']], 'error'=>$error, 'results'=>&$page);
                    
                    if(is_array($callback))
                        $call=$callback[0]->$callback[1]($fnArgNext, $check_map, $map[(string)$done['handle']]);
                    else
                        $call=$callback($fnArgNext, $check_map, $map[(string)$done['handle']]);

                    if (-1 == $call){
                        $cb_ret ++;
                    }
                    self::curl_init_ex(array_shift($sec_urls), $queue, $map);
                }
                // remove the curl handle that just completed             
                curl_multi_remove_handle($queue, $done['handle']);
                curl_close($done['handle']);
            }

            // Block for data in / output; error handling is done by curl_multi_exec    
            if ($active > 0)
                curl_multi_select($queue, 1);

        } while ($active);

        curl_multi_close($queue);
        return ($cb_ret === 0);
    }

    private static function curl_init_ex($url, &$mhandle, &$maps)
    {
        if (false == $url) return;

        $agentStr = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 2.0.50727; InfoPath.1; CIBA)';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_USERAGENT, $agentStr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOSIGNAL, true);
        curl_multi_add_handle($mhandle, $ch);
        $maps[(string) $ch] = $url;
    }
}

