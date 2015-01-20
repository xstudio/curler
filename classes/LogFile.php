<?php

/**
 * 将运行日志记录到文件中[各个变量的值在运行时赋予]
 */
class LogFile
{
    private $_conn;

    public $start_time=0;
    public $end_time=0;
    public $site='';
    public $success_count=0;
    public $error_count=0;
    public $exist_count=0;
    public $public_count=0;
    public $public_error=0;
    public $count=0;


    public function __construct($log_file)
    {
        if (empty($this->_conn))
        {
            $this->_conn = fopen($log_file, 'a');
        }
    }


    public function __destruct()
    {
        if (!empty($this->_conn))
        {
    	    fclose($this->_conn);
        }
    }

    public function write()
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
