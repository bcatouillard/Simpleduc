<?php
Class Module{
    private $db;
    private $select;
    private $insert;
    private $update;
    private $delete;
    private $selectByCode;
    
    public function __construct($db) {
        $this->db=$db;
        $this->select = $db->prepare("select * from module");
        $this->insert = $db->prepare("insert into module() values()");
        $this->update = $db->prepare("update module set where ");
        $this->delete = $db->prepare("delete from module where");
        $this->selectByCode = $db->prepare("select * from module where");
    }
}
?>