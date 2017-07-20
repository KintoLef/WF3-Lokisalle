<?php
// 1. Connexion à une BDD
$pdo = new PDO('mysql:host=localhost;dbname=wf3-lokisalle', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));