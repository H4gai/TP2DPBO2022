<?php

class DB
{
    var $db_host = "";
    var $db_user = "";
    var $db_pass = "";
    var $db_name = "";
    var $db_link;
    var $result;

    function DB($db_host = '', $db_user = '', $db_pass = '', $db_name = '')
    {
        $this->db_host = $db_host;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_name = $db_name;
    }

    function open()
    {
        $this->db_link = mysqli_connect($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
    }

    function execute($query = "")
    {
        $this->result = mysqli_query($this->db_link, $query);
        return $this->result;
    }

    function getResult()
    {
        $rows = [];
        while($row = mysqli_fetch_assoc($this->result)) $rows[] = $row;
        return $rows;
    }

    function close()
    {
        mysqli_close($this->db_link);
    }
}

?>