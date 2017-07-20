<?php
// 1. Connexion à une BDD
$pdo = new PDO('mysql:host=localhost;dbname=wf3-lokisalle', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

// création de variables pouvant nous servir dans le cadre du projet:
// variable pour afficher des messages à l'utilisateur
$message = "";

// ouverture de la session
session_start();

// définition de constante pour le chemin absolu ainsi que pour la racine serveur
// racine site
define("URL", "/formation/paris-iv/WF3-Lokisalle");

// racine serveur
define("RACINE_SERVER", $_SERVER['DOCUMENT_ROOT'] . URL);