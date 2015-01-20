<?php

/**
 * 将每次的抓取日志记录到mysql[各个变量的值在运行时赋予]
 *
 *
 */
class LogMysql
{
    private $_conn;

    public $start_time=0;
    public $end_time=0;
    public $site='';
    public $success_count=0;
    public $error_count=0;
    public $exist_count=0;
    public $count=0;
    
    public $table_name = 'spider_log';

    public function __construct()
    {
    	$this->_conn=@new mysqli(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS,SAE_MYSQL_DB);
        $this->_conn->query("SET NAMES 'utf8'");
    }


    public function __destruct()
    {
    	$this->_conn = null;
    }

    public function write()
    {
        $sql = "insert into {$this->table_name} values(null, {$this->start_time}, {$this->end_time}, '{$this->site}', {$this->success_count}, {$this->error_count}, {$this->exist_count}, {$this->count})";
        if ($this->_conn->query($sql))
            echo 'success';
        else
            echo $this->_conn->error;
        
    }
}
