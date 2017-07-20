<?php
require_once("inc/init.inc.php");

if(utilisateur_admin()) 
{

} else {
  header("location:../connexion.php");
  exit(); // permet d'arrêter l'exécution du script
}

require_once("inc/head.inc.php");
require_once("inc/nav.inc.php");
?>

<div class="container">

    <div class="starter-template">
        <h1>Gestion des produits</h1>
    </div>
    <?= $message; // cette balise php inclus un echo (equivalent à la ligne du dessus) ?>

    

</div><!-- /.container -->
        
<?php
require_once("inc/footer.inc.php");
?>