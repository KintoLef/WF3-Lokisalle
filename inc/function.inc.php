<?php

// function pour savoir si un utilisateur est connecté
function utilisateur_connecte()
{
    if(isset($_SESSION['membre']))
    {
        // si l'indice membre existe alors l'membre est connecté car il est passé par la page connexion
        return true; // si on passe sur cette ligne, on sort de la fonction et le return false en dessous ne sera pas pris en compte
    }
    return false; // si on rentre pas dans le if, on retourne false
}

// fonction pour savoir si un utilisateur est connecté mais aussi si il a le satut administrateur
function utilisateur_admin()
{
    if(utilisateur_connecte() && $_SESSION['membre']['statut'] == 1)
    {
        return true;
    }
    return false;
}

// fonction pour vérifier la disponiblité des produits
function getDatesFromRange($start, $end)
{
    $array = array();
    $interval = new DateInterval('P1D');
    $realEnd = new DateTime($end);
    $realEnd->add($interval);
    $period = new DatePeriod(new DateTime($start), $interval, $realEnd);
    foreach ($period as $date) {
        $array[] = $date->format('Y-m-d');
    }
    return $array;
}