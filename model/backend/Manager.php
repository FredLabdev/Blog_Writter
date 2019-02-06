<?php

namespace FredLab\tp4_blog_ecrivain\Model\Backend;

class Manager {
    
    protected function dbConnect() {
        $db = new \PDO('mysql:host=localhost;dbname=forteroche', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        return $db;
    }
}
