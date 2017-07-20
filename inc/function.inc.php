<?php

// function pour savoir si un utilisateur est connecté
function utilisateur_connecte()
{
    if(isset($_SESSION['utilisateur']))
    {
        // si l'indice utilisateur existe alors l'utilisateur est connecté car il est passé par la page connexion
        return true; // si on passe sur cette ligne, on sort de la fonction et le return false en dessous ne sera pas pris en compte
    }
    return false; // si on rentre pas dans le if, on retourne false
}