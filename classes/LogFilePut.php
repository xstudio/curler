<?php

class LogFilePut
{
    public $start_time=0;
    public $end_time=0;
    public $site='';
    public $success_count=0;
    public $error_count=0;
    public $exist_count=0;
    public $public_count=0;
    public $public_error=0;
    public $count=0;
    
    public $log_file;

    public function __construct($log_file)
    {
        $this->log_file = $log_file;
    }

    public function write($content)
    {
        return @file_put_contents($this->log_file, $content . "\n", FILE_APPEND);
    }
    
    public function writeBase($content)
    {
        return $this->write(date('Y-m-d H:i:s') . ' ' .$content);
    }
    public function writeSplit($content)
    {
        return $this->write("\n-------------" . date('Y-m-d H:i:s') . ' '  . $content . "-------------\n");
    }
    public function writes()
    {
        $writeStr = "-------------".$this->start_time." start-----------\n";
        $writeStr .= 'fetch_all_count:' . $this->count . "\n";
        $writeStr .= 'fetch_success_count:' . $this->success_count . "\n";
        $writeStr .= 'fetch_error_count:' . $this->error_count . "\n";
        $writeStr .= 'fetch_exist_count:' . $this->exist_count . "\n";
        $writeStr .= 'public_count:' . $this->public_count . "\n";
        $writeStr .= 'public_error:' . $this->public_error . "\n";
        $writeStr .= "-------------".$this->end_time." end-------------\n\n";
        fwrite($this->_conn, $writeStr);
    }
}
