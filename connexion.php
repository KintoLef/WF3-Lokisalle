<?php
require_once("inc/init.inc.php");

if(isset($_GET['action']) && $_GET['action'] == 'deconnexion')
{
    session_destroy();
}

// vérification si l'utilisateur est connecté si oui on le redirige sur profil.php
// if(utilisateur_connecte())
// {
//     header('location:profil.php');
// }

// vérification de l'existence des indices du formulaire
if(isset($_POST['pseudo']) && isset($_POST['mdp']))
{
    $pseudo = $_POST['pseudo'];
    $mdp = $_POST['mdp'];

    $verif_connexion = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo AND mdp = :mdp");
    $verif_connexion->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
    $verif_connexion->bindParam(":mdp", $mdp, PDO::PARAM_STR);
    $verif_connexion->execute();

    if($verif_connexion->rowCount() > 0)
    {
        // si nous avons 1 ligne alors le pseudo et le mdp sont corrects
        $info_utilisateur = $verif_connexion->fetch(PDO::FETCH_ASSOC);
        
        // on place toutes les informations de l'utilisateur dans la session sauf le mdp
        $_SESSION['membre'] = array();
        $_SESSION['membre']['id_membre'] = $info_utilisateur['id_membre'];
        $_SESSION['membre']['pseudo'] = $info_utilisateur['pseudo'];
        $_SESSION['membre']['nom'] = $info_utilisateur['nom'];
        $_SESSION['membre']['prenom'] = $info_utilisateur['prenom'];
        $_SESSION['membre']['email'] = $info_utilisateur['email'];
        $_SESSION['membre']['civilite'] = $info_utilisateur['civilite'];        
        $_SESSION['membre']['statut'] = $info_utilisateur['statut'];
        $_SESSION['membre']['statut'] = $info_utilisateur['statut'];
        
        header('location:profil.php');
    }
    else {
        $message .= '<div class="alert alert-danger" role="alert">Attention, les informations saisies sont erronées !!</div>';
    }
}

require_once("inc/head.inc.php");
require_once("inc/nav.inc.php");
// echo '<pre>'; print_r($_SESSION); echo '</pre>';
?>

<div class="container">

    <div class="starter-template">
        <h1>Connectez-vous</h1>
    </div>
    <?= $message; // cette balise php inclus un echo (equivalent à la ligne du dessus) ?>

    <div class="row">
        <div class="col-sm-4 col-sm-offset-4">
          <form method="post" action="">
            <div class="form-group">
              <label for="pseudo">Pseudo:</label>
              <!-- On met les value="" pour rafraichir le champ à chaque submit -->
              <input type="text" class="form-control" id="pseudo" name="pseudo">
            </div>  
            <div class="form-group">
              <label for="mdp">Mot de passe:</label>
              <input type="text" class="form-control" id="mdp" name="mdp">
            </div>               
            <div class="form-group">
              <button type="submit" name="inscription" id="inscription" class="btn btn-primary form-control">Se Connecter</button>
            </div>
              
          </form>
        </div> <!-- /.col-sm-4 -->
      </div> <!-- /.row -->

</div><!-- /.container -->
        
<?php
require_once("inc/footer.inc.php");
?>