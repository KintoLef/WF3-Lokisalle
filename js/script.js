if(document.getElementById('capacite') != null) {

    // On récupère le select avec l'id capacite, on lui ajoute l'évènement change
    document.getElementById('capacite').addEventListener('change', ajax);

    function ajax () {
        // alert('ok')
        // Le fichier cible où l'on va traiter la requête http envoyée par l'ajax
        var file = "ajax.php";
        var champCapacite = document.getElementById('capacite');
        if(window.XMLHttpRequest) 
            var xhttp = new XMLHttpRequest(); // Pour la plupart des navigateurs
        else 
            var xhttp = new ActiveXObject("Microsoft.XMLHTTP"); // Dans le cas des vieilles versions d'IE, le else se déclenche 
        // On récupère la valeur du champ mais on ne s'en sert pas
        var valeur = champCapacite.value;

        // On définit les paramètres à envoyer dans la requête HTTP
        var param = "capacite=" + valeur;


        xhttp.open("POST", file, true);

        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xhttp.onreadystatechange = function () {
            if(xhttp.readyState == 4 && xhttp.status == 200) {
            // Cette ligne permet de corriger son PHP à partir de la console du navigateur,  il doit être placé avant le JSON.parse()
            console.log(xhttp.responseText);
            // On convertit la réponse HTTP (array PHP encodé au format JSON) en objet JSON
            var result = JSON.parse(xhttp.responseText);
            // On obtient l'objet result avec la clé resultat
            console.log(result);
            // On sélectionne l'élément select avec l'id ville, on remplace le contenu de cet élément par la réponse de la requête, avec la clé définit en php
            document.getElementById("resultat").innerHTML = result.resultat;
            }
        }
        // On envoit la requête
        xhttp.send(param);
    }
}









$(function () {
    $('#date_arrivee').datetimepicker({format: 'Y-m-d H:i'});
    $('#date_depart').datetimepicker({format: 'Y-m-d H:i'});
});
