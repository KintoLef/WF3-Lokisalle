<?php
require_once("inc/init.inc.php");

if(isset($_GET['action']) && $_GET['action'] == 'deconnexion')
{
    session_destroy();
}

// vérification si l'utilisateur est connecté si oui on le redirige sur profil.php
if(utilisateur_connecte())
{
    header('location:profil.php');
}

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
        $_SESSION['utilisateur'] = array();
        $_SESSION['utilisateur']['id_membre'] = $info_utilisateur['id_membre'];
        $_SESSION['utilisateur']['pseudo'] = $info_utilisateur['pseudo'];
        $_SESSION['utilisateur']['nom'] = $info_utilisateur['nom'];
        $_SESSION['utilisateur']['prenom'] = $info_utilisateur['prenom'];
        $_SESSION['utilisateur']['email'] = $info_utilisateur['email'];
        $_SESSION['utilisateur']['civilite'] = $info_utilisateur['civilite'];        
        $_SESSION['utilisateur']['statut'] = $info_utilisateur['statut'];
        $_SESSION['utilisateur']['statut'] = $info_utilisateur['statut'];
        

        header('location:profil.php');

        // même chose avec un foreach
        // $_SESSION['utilisateur'] = array();
        // foreach($info_utilisateur AS $indice => $valeur)
        // {
        //     if($indice != 'mdp')
        //     {
        //         $_SESSION['utilisateur'][$indice] = $valeur;
        //     }            
        // }
    }
    else {
        $message .= '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">Attention, les informations saisies sont erronées !!</div>';
    }
}

require_once("inc/head.inc.php");
require_once("inc/nav.inc.php");
?>

<div class="container">

    <div class="starter-template">
        <h1>Connectez-vous</h1>
    </div>
    <?= $message; // cette balise php inclus un echo (equivalent à la ligne du dessus) ?>

    <form class="form-horizontal col-sm-8">
        <div class="form-group">
            <label for="pseudo" class="col-sm-2 control-label">Pseudo</label>
            <div class="col-sm-4">
            <input type="text" class="form-control" id="pseudo" placeholder="Pseudo">
            </div>
        </div>
        <div class="form-group">
            <label for="mdp" class="col-sm-2 control-label">Mot de Passe</label>
            <div class="col-sm-4">
            <input type="text" class="form-control" id="mdp" placeholder="Password">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-4">
                <button type="submit" class="btn btn-primary">Se Connecter</button>
            </div>
        </div>
    </form>

</div><!-- /.container -->
        
<?php
require_once("inc/footer.inc.php");
?>