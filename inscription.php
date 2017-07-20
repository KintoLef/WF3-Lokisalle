<?php
require_once("inc/init.inc.php");

// Déclaration de variables vides pour affichage dans les values du formulaire
$pseudo = "";
$mdp = "";
$nom = "";
$prenom = "";
$email = "";
$civilite = "";

// Contrôle de l'existence de tous les champs présents dans le formulaire
if(isset($_POST['pseudo']) && isset($_POST['mdp']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['sexe']) && isset($_POST['civilite'])) 
{
    
}

require_once("inc/head.inc.php");
require_once("inc/nav.inc.php");
echo '<pre>'; print_r($_POST); echo '</pre>';
?>

<div class="container">

    <div class="starter-template">
        <h1>Inscription</h1>
    </div>
    <?= $message; // cette balise php inclus un echo (equivalent à la ligne du dessus) ?>

    <div class="row">
        <div class="col-sm-4 col-sm-offset-4">
          <form method="post" action="">
            <div class="form-group">
              <label for="pseudo">Pseudo:</label>
              <!-- On met les value="" pour rafraichir le champ à chaque submit -->
              <input type="text" class="form-control" id="pseudo" name="pseudo" value="<?php echo $pseudo; ?>">
            </div>  
            <div class="form-group">
              <label for="mdp">Mot de passe:</label>
              <input type="text" class="form-control" id="mdp" name="mdp" value="<?php echo $mdp; ?>">
            </div>   
            <div class="form-group">
              <label for="nom">Nom:</label>
              <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $nom; ?>">
            </div>
            <div class="form-group">
              <label for="prenom">Prénom:</label>
              <input type="text" class="form-control" id="prénom" name="prenom" value="<?php echo $prenom; ?>">
            </div>
            <div class="form-group">
              <label for="email">Email:</label>
              <input type="text" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
            </div>
            <div class="form-group">
              <label for="civilite">Sexe:</label>
              <select class="form-control" name="civilite" id="civilite">
                <option value="m">Homme</option>
                <option value="f" <?php if($sexe == "f") { echo 'selected'; } ?>>Femme</option>
              </select>
            </div>
            <div class="form-group">
              <button type="submit" name="inscription" id="inscription" class="btn btn-primary form-control">S'inscrire</button>
            </div>
              
          </form>
        </div> <!-- /.col-sm-4 -->
      </div> <!-- /.row -->

</div><!-- /.container -->
        
<?php
require_once("inc/footer.inc.php");
?>