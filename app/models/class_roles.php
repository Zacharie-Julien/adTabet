<?php
class Role
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
    public function getAllRoles()
    {
        $query = "SELECT * FROM role";
        return $this->db->query($query)->fetchAll();
    }
}

?>