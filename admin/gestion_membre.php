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
        $membre_a_supprimer = $pdo->prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
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
        $message .= '<td><a href="" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></a> <a href="?modifier&id_membre=' .  $ligne['id_membre'] .'" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span></a> <a href="?supprimer&id_membre=' . $ligne['id_membre'] .'" class="btn btn-default"><span class="glyphicon glyphicon-trash"></span></a></td>';
        $message .= '</tr>';
    }

    $message .= '</table>';

    // Si l'on clique sur le bouton modifier
    if(isset($_GET['modifier']) && !empty($_GET['id_membre']) && is_numeric($_GET['id_membre']))
    {
        // Affichage des champs input 
        $message .= '
        <form method="post" action="">
            <div class="row">
                <div class="col-md-6">
                    <label class="gestion_membre" for="pseudo">Pseudo</label>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-user"></span></span>
                        <input type="text" class="form-control" placeholder="pseudo" id="pseudo" name="pseudo" aria-describedby="basic-addon1">
                    </div>
                    <label class="gestion_membre" for="mdp">Mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-lock"></span></span>
                        <input type="text" class="form-control" placeholder="mot de passe" id="mdp" name="mdp" aria-describedby="basic-addon1">
                    </div>
                    <label class="gestion_membre" for="nom">Nom</label>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-pencil"></span></span>
                        <input type="text" class="form-control" placeholder="votre nom" name="nom" id="nom" aria-describedby="basic-addon1">
                    </div>
                    <label class="gestion_membre" for="prenom">Prénom</label>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-pencil"></span></span>
                        <input type="text" class="form-control" placeholder="votre prénom" name="prenom" id="prenom" aria-describedby="basic-addon1">
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="gestion_membre" for="email">Email</label>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-envelope"></span></span>
                        <input type="text" class="form-control" placeholder="votre email" name="email" id="email" aria-describedby="basic-addon1">
                    </div>
                    
                    <label class="gestion_membre" for="civilite">Civilité</label>
                    <div class="input-group">
                        <select name="civilite" id="civilite">                    
                            <option value="m">Homme</option>
                            <option value="f">Femme</option>                                                                
                        </select>
                    </div>
                    <label class="gestion_membre" for="statut">Statut</label>
                    <div class="input-group">
                        <select name="statut" id="statut">                    
                            <option value="0">Membre</option>
                            <option value="1">Administrateur</option>                                                                
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-membre pull-right">Enregistrer</button>    
                </div>
            </div>
            </form>'
            ;


        $id_membre = $_GET['id_membre'];
        $membre_a_modifier = $pdo->prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
        $membre_a_modifier->bindParam(":id_membre", $id_membre, PDO::PARAM_STR);
        $membre_a_modifier->execute();
    }

} else {
  header("location:../connexion.php");
  exit(); // permet d'arrêter l'exécution du script
}

require_once("../inc/head.inc.php");
require_once("../inc/nav.inc.php");
echo '<pre>'; print_r($_POST); echo '</pre>';
?>

<div class="container">

    <div class="starter-template">
        <h1>Gestion des membres</h1>
    </div>
    <?= $message; // cette balise php inclus un echo (equivalent à la ligne du dessus) ?>

    

</div><!-- /.container -->
        
<?php
require_once("../inc/footer.inc.php");
?>