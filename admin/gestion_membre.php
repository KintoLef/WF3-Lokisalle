<?php
require_once("../inc/init.inc.php");

// Si l'utilisateur est admin
if(utilisateur_admin()) 
{
    // On affiche la liste des membres dans la bdd
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
        $message .= '<td><a href="" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></a> <a href="" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span></a> <a href="" class="btn btn-default"><span class="glyphicon glyphicon-trash"></span></a></td>';
        $message .= '</tr>';
    }

    $message .= '</table>';

} else {
  header("location:../connexion.php");
  exit(); // permet d'arrêter l'exécution du script
}

require_once("../inc/head.inc.php");
require_once("../inc/nav.inc.php");
?>

<div class="container">

    <div class="starter-template">
        <h1>Gestion des membres</h1>
    </div>
    <hr />
    <?= $message; // cette balise php inclus un echo (equivalent à la ligne du dessus) ?>

    

</div><!-- /.container -->
        
<?php
require_once("../inc/footer.inc.php");
?>