<?php
require_once("inc/init.inc.php");

// vérification si l'utilisateur est connecté sinon on le redirige sur connexion.php
if(!utilisateur_connecte())
{
    header('location:connexion.php');
}
if(utilisateur_admin() && (isset($_GET['afficher']) && !empty($_GET['id_membre']) && is_numeric($_GET['id_membre'])))
{
  
} else {

}

if($_SESSION['membre']['statut'] == 1)
{
    $role = '(administrateur)';
} else {
    $role = '(membre)';
}

require_once("inc/head.inc.php");
require_once("inc/nav.inc.php");
?>

<div class="container">
    <?php
    // Sil'utilisateur est admin et que l'url possède ces attributs
    if(utilisateur_admin() && (isset($_GET['afficher']) && !empty($_GET['id_membre']) && is_numeric($_GET['id_membre'])))
    {
        $id_membre = $_GET['id_membre'];
        $affichage = $pdo->prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
        $affichage->bindParam(":id_membre", $id_membre, PDO::PARAM_STR);
        $affichage->execute();

    ?>
        <div class="starter-template">
            <h1><span class="glyphicon glyphicon-user"></span><?php echo ' ' . $_SESSION['membre']['prenom'] . ' ' . $_SESSION['membre']['nom'] . ' ! ' . $role ?></h1>
        </div>
        <div class="row">
            <div class="col-sm-9 col-sm-offset-2">
                <div class="col-sm-10" style="border-radius: 3px; border: 1px solid #AAA;">
                    <h2 style="color: black;">Informations:</h2>
                    <ul class="list-group">
                        <li class="list-group-item">Pseudo: <?php echo $_SESSION['membre']['pseudo'] ?></li>
                        <li class="list-group-item">Nom: <?php echo $_SESSION['membre']['nom'] ?></li>
                        <li class="list-group-item">Prénom: <?php echo $_SESSION['membre']['prenom'] ?></li>
                        <li class="list-group-item">Email: <?php echo $_SESSION['membre']['email'] ?></li>
                        <li class="list-group-item">Sexe: <?php echo $_SESSION['membre']['civilite'] ?></li>
                        <li class="list-group-item">Membre depuis: <?php echo $_SESSION['membre']['date_enregistrement'] ?></li>
                    </ul>
                </div>
            </div>
        </div>
    <br>
    <?php
    } else {
    ?>    
    <div class="starter-template">
        <h1><span class="glyphicon glyphicon-user"></span><?php echo ' Bonjour ' . $_SESSION['membre']['prenom'] . ' ' . $_SESSION['membre']['nom'] . ' ! ' . $role ?></h1>
    </div>
    <?= $message; // cette balise php inclus un echo (equivalent à la ligne du dessus) ?>
    <div class="row">
        <div class="col-sm-9 col-sm-offset-2">
            <div class="col-sm-10" style="border-radius: 3px; border: 1px solid #AAA;">
                <h2 style="color: black;">Informations:</h2>
                <ul class="list-group">
                    <li class="list-group-item">Pseudo: <?php echo $_SESSION['membre']['pseudo'] ?></li>
                    <li class="list-group-item">Nom: <?php echo $_SESSION['membre']['nom'] ?></li>
                    <li class="list-group-item">Prénom: <?php echo $_SESSION['membre']['prenom'] ?></li>
                    <li class="list-group-item">Email: <?php echo $_SESSION['membre']['email'] ?></li>
                    <li class="list-group-item">Sexe: <?php echo $_SESSION['membre']['civilite'] ?></li>
                    <li class="list-group-item">Membre depuis: <?php echo $_SESSION['membre']['date_enregistrement'] ?></li>
                </ul>
            </div>
        </div>
    </div>
    <br>
    <?php
    }
    ?>
    

</div><!-- /.container -->
        
<?php
require_once("inc/footer.inc.php");
?>