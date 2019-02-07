<?php

namespace FredLab\tp4_blog_ecrivain\Model;

class Manager {
    
    protected function dbConnect() {
        $db = new \PDO('mysql:host=localhost;dbname=forteroche;charset=utf8', 'root', 'root');
        return $db;
    }
}
