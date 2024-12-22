<?php
class Appli
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllApplis()
    {
        $query = "SELECT * FROM applis";
        return $this->db->query($query)->fetchAll();
    }
}

?>