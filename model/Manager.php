<?php

namespace FredLab\tp4_blog_ecrivain\Model;

class Manager {
    
    protected function dbConnect() {
        $db = new \PDO('mysql:host=db756345555.db.1and1.com;dbname=db756345555;charset=utf8', 'dbo756345555', 'Forteroche&2019', array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
        return $db;
    }
}
