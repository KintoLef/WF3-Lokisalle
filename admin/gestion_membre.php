<?php
require_once("../inc/init.inc.php");

// Si l'utilisateur est admin
if(utilisateur_admin()) 
{
    // Si l'admin veut supprimer un membre
    if(isset($_GET['supprimer']) && !empty($_GET['id_membre']) && is_numeric($_GET['id_membre']))
    {
        // On récupère l'id_membre dans l'url
        $id_membre = $_GET['id_membre'];
        $membre_a_supprimer = $pdo->prepare("DELETE FROM membre WHERE id_membre = :id_membre");
        $membre_a_supprimer->bindParam(":id_membre", $id_membre, PDO::PARAM_STR);
        $membre_a_supprimer->execute();
    }


    // AFFICHAGE DE LA LISTE DES MEMBRES EN BDD
    $affichage = $pdo->query("SELECT * FROM membre");
    
    $nb_col = $affichage->columnCount();

    $message .= '<table border="1">';
    $message .= '<tr>';

    for ($i = 0; $i < $nb_col; $i++)
    {

        $colonne = $affichage->getColumnMeta($i);
        if($colonne['name'] != 'mdp')
        {
            $message .= '<th>' . $colonne['name'] . '</th>';
        }
    }
    $message .= '<th>actions</th>';
    $message .= '</tr>';
    while($ligne = $affichage->fetch(PDO::FETCH_ASSOC))
    {
        $message .= '<tr>';
        foreach($ligne AS $indice => $donnees)
        {
            
            if($indice != 'mdp')
            {
                if($donnees == 'm')
                {
                    $donnees = 'Homme';
                }
                elseif($donnees == 'f')
                {
                    $donnees = 'Femme';
                }
                $message .= '<td>' . $donnees . '</td>';
            }
        }
        $message .= '<td><a href="../profil.php?afficher&id_membre=' . $ligne['id_membre'] . '" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></a> <a href="?modifier&id_membre=' .  $ligne['id_membre'] . '" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span></a> <a href="?supprimer&id_membre=' . $ligne['id_membre'] . '" class="btn btn-default"><span class="glyphicon glyphicon-trash"></span></a></td>';
        $message .= '</tr>';
    }

    $message .= '</table>';

    if(isset($_GET['modifier']) && !empty($_GET['id_membre']) && is_numeric($_GET['id_membre']))
    { 
        // Récupération de l'id_membre à modifier
        $id_membre = $_GET['id_membre'];

        // Déclaration de variables vides pour affichage dans les values du formulaire
        $pseudo = "";
        $mdp = "";
        $nom = "";
        $prenom = "";
        $email = "";
        $civilite = "";
        $statut = "";
        
        // Affichage du formulaire plus bas

        // Vérification de l'existence des champs du formulaire
        if(isset($_POST['id_membre']) && isset($_POST['pseudo']) && isset($_POST['mdp']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['civilite']) && isset($_POST['statut']))
        {
            //  Récupération des informations saisies par l'utilisateur
            $id_membre = $_POST['id_membre'];
            $pseudo = $_POST['pseudo'];
            $mdp = $_POST['mdp'];
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $civilite = $_POST['civilite'];
            $statut = $_POST['statut'];

            // Déclaration d'une variable d'erreur pour les vérifications
            $erreur = "";

            // Contrôle sur la taille du pseudo (entre 4 et 14 caractères)
            // On rentre la taille du pseudo dans une variable
            $taille_pseudo = iconv_strlen($pseudo);
            if($taille_pseudo < 4 || $taille_pseudo > 14)
            {
            $message .= '<div class="alert alert-danger" role="alert">Attention, la taille du pseudo est incorrecte.<br />En effet, le pseudo doît être compris entre 4 et 14 caractères inclus.</div>';
            $erreur = true; // Si l'on rentre dans cette condition alors il y a une erreur.
            }

            // Vérification des caractères contenus dans le pseudo à l'aide d'une expression régulière
            // Contrôle des caractères dans le pseudo (autorisés a-z A-Z 0-9 _ - .)
            $verif_caracteres = preg_match('#^[a-zA-Z0-9._-]+$#', $pseudo);
            
            // On vérifie si les caractères sont autorisés
            if(!$verif_caracteres && !empty($pseudo)) 
            {
            // On rentre dans cette condition si verif_caracteres contient 0 donc s'il y a des caractères non-autorisés
            $message .= '<div class="alert alert-danger" role="alert">Attention, caractères non autorisés dans le pseudo.<br />Caractères autorisés: A-Z ET 0-9.</div>';
            $erreur = true; // Si l'on rentre dans cette condition alors il y a une erreur
            }

            // Vérification de l'adresse email
            if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) &&!empty($email))
            {
            $message .= '<div class="alert alert-danger" role="alert">Attention, votre adresse email est vide ou invalide.<br />Veuillez la saisir à nouveau .</div>';
            $erreur = true;
            }

            // Contrôle sur la disponibilité du pseudo en bdd
            $disponibilite_pseudo = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
            $disponibilite_pseudo->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
            $disponibilite_pseudo->execute();

            // On vérifie si la requête $verif_pseudo renvoie au moins une ligne de résultat
            if($disponibilite_pseudo->rowCount() > 0)
            {
            $message .= '<div class="alert alert-danger" role="alert">Attention, votre pseudo n\'est pas disponible.<br />Veuillez en saisir un nouveau.</div>';
            $erreur = true; // Si l'on rentre dans cette condition alors il y a une erreur
            }

            // S'il n'y a aucune erreur
            if($erreur !== true)
            {
                $modification = $pdo->prepare("UPDATE membre SET pseudo = :pseudo, mdp = :mdp, nom = :nom, prenom = :prenom, email = :email, civilite = :civilite, statut = :statut WHERE id_membre = :id_membre");

                $modification->bindParam(":id_membre", $id_membre, PDO::PARAM_STR);
                $modification->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
                $modification->bindParam(":mdp", $mdp, PDO::PARAM_STR);
                $modification->bindParam(":nom", $nom, PDO::PARAM_STR);
                $modification->bindParam(":prenom", $prenom, PDO::PARAM_STR);
                $modification->bindParam(":email", $email, PDO::PARAM_STR);
                $modification->bindParam(":civilite", $civilite, PDO::PARAM_STR);
                $modification->bindParam(":statut", $statut, PDO::PARAM_STR);
                $modification->execute();

            }
            $membre_a_modifier = $pdo->prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
            $membre_a_modifier->bindParam(":id_membre", $id_membre, PDO::PARAM_STR);
            $membre_a_modifier->execute();
        }
    }

} else {
  header("location:../connexion.php");
  exit(); // permet d'arrêter l'exécution du script
}

require_once("../inc/head.inc.php");
require_once("../inc/nav.inc.php");
// echo '<pre>'; print_r($_POST); echo '</pre>';
?>

<div class="container">

    <div class="starter-template">
        <h1>Gestion des membres</h1>
    </div>
    <?= $message; // cette balise php inclus un echo (equivalent à la ligne du dessus) ?>

    <?php
    // Si l'on clique sur le bouton modifier
    if(isset($_GET['modifier']) && !empty($_GET['id_membre']) && is_numeric($_GET['id_membre']))
    {
    ?>
        <!-- Affichage des champs input --> 
        <form method="post" action="">
            <div class="row">
                <div class="col-md-6">
                    <!-- Input vide pour récupérer l'id_membre via $_GET -->
                    <input type="hidden" class="form-control" id="id_membre" name="id_membre" value="<?php echo $id_membre; ?>">
                    <label class="gestion_membre" for="pseudo">Pseudo</label>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-user"></span></span>
                        <input type="text" class="form-control" placeholder="pseudo" id="pseudo" name="pseudo" aria-describedby="basic-addon1" value="<?php echo $pseudo; ?>">
                    </div>
                    <label class="gestion_membre" for="mdp">Mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-lock"></span></span>
                        <input type="text" class="form-control" placeholder="mot de passe" id="mdp" name="mdp" aria-describedby="basic-addon1" value="<?php echo $mdp; ?>">
                    </div>
                    <label class="gestion_membre" for="nom">Nom</label>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-pencil"></span></span>
                        <input type="text" class="form-control" placeholder="votre nom" name="nom" id="nom" aria-describedby="basic-addon1" value="<?php echo $nom; ?>">
                    </div>
                    <label class="gestion_membre" for="prenom">Prénom</label>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-pencil"></span></span>
                        <input type="text" class="form-control" placeholder="votre prénom" name="prenom" id="prenom" aria-describedby="basic-addon1" value="<?php echo $prenom; ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="gestion_membre" for="email">Email</label>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-envelope"></span></span>
                        <input type="text" class="form-control" placeholder="votre email" name="email" id="email" aria-describedby="basic-addon1" value="<?php echo $email; ?>">
                    </div>
                    
                    <label class="gestion_membre" for="civilite">Civilité</label>
                    <div class="input-group">
                        <select name="civilite" id="civilite">                    
                            <option value="m">Homme</option>
                            <option value="f" <?php if($civilite == "f") { echo 'selected'; } ?>>Femme</option>                                                                
                        </select>
                    </div>
                    <label class="gestion_membre" for="statut">Statut</label>
                    <div class="input-group">
                        <select name="statut" id="statut">                    
                            <option value="0">Membre</option>
                            <option value="1" <?php if($statut == 1) { echo 'selected'; } ?>>Administrateur</option>                                                                
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-membre pull-right">Enregistrer</button>    
                </div>
            </div>
        </form>
    <?php
    }
    ?>
            
</div><!-- /.container -->
        
<?php
require_once("../inc/footer.inc.php");
