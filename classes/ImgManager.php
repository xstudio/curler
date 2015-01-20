<?php

/**
 * 针对图片处理
 *
 * 判断是否是合法图片、下载等
 */
class ImgManager
{
    const BASE_PATH = '/home/upload/';
    public function isBinary($source)
    {
        switch(bin2hex(substr($source,0,2)))
        {   
            case'ffd8': return'ffd9'===bin2hex(substr($source,-2));
            case'8950': return'6082'===bin2hex(substr($source,-2));
            case'4749': return'003b'===bin2hex(substr($source,-2));
            default: return false;
        }   
    }

    public function getExtension($url)
    {
        return 'jpg';
    }

    public function downFile($url)
    {
        if ($this->isBinary($source = Curl::getData($url)))
        {
            $tmp_name = self::BASE_PATH . microtime(true) . rand(1, 1000) . '.' . $this->getExtension($url);
            if (@file_put_contents($tmp_name, $source))
            {
                return $tmp_name;
            }
        }
        return false;
    }

    public function deleteFile($file)
    {
        exec("rm -rf '" . $file . "'");
    }

    public function uploadFile($file)
    {
        
    }
}
