$(document).ready(function(){
    // On écoute l'évènement change sur le select avec l'id capacité
   $('#capacite').on('change', function(){
        alert('ok')
        // On récupère la valeur du champ select
        var capacite = $("#capacite").val();
        // console.log(capacite);
        // On définit les paramètres
        var param = "capacite=" + capacite;

        // Fichier cible, on récupère la valeur de l'attribut action="" du formulaire
        var file = $('form').attr("action");
        // console.log(file);

        // Méthode, on récupère la valeur de l'attribut méthod="" du formulaire
        var method = $('form').attr("method");
        // console.log(method);

        // On déclenche la méthode ajax !
        $.ajax({
            // url: "ajax.php"
            url: file,
            // type: "POST"
            type: method,
            // data: "personne=" + perso;
            data: param,
            // Il faut préciser le format des données reçues
            dataType: "json",

            success: function(reponse) {
                console.log(reponse.resultat);
                // La fonction qui sera exécutée lors de la réception de la réponse
                $("#resultat").html(reponse.resultat);
            }
        })
   })
})