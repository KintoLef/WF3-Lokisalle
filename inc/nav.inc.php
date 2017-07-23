    <nav class="navbar navbar-default">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <a class="navbar-brand" href="<?php echo URL; ?>index.php">Lokisalle</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav left">
                    <li class="active"><a href="<?php echo URL; ?>index.php">Accueil <span class="sr-only">(current)</span></a></li>
                   <li><a href="<?php echo URL; ?>info.php">Qui sommes-nous ?</a></li>
                   <li><a href="<?php echo URL; ?>contact.php">Contact</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php if(!utilisateur_admin()) { ?><span class="glyphicon glyphicon-user"></span> Espace membre <?php } else { ?><span class="glyphicon glyphicon-king"></span> Espace admin<?php } ?> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php
                        if(!utilisateur_connecte())
                        { 
                        ?>
                            <li><a href="<?php echo URL; ?>inscription.php">Inscription</a></li>
                            <li><a href="<?php echo URL; ?>connexion.php">Connexion</a></li>
                        <?php
                        } else {
                        ?>           
                            <li><a href="<?php echo URL; ?>profil.php">Profil</a></li>
                            <?php
                            if(utilisateur_admin())
                            { 
                            ?>
                                <li><a href="<?php echo URL; ?>admin/gestion_avis.php">Gestion avis</a></li>
                                <li><a href="<?php echo URL; ?>admin/gestion_membre.php">Gestion membre</a></li>
                                <li><a href="<?php echo URL; ?>admin/gestion_produit.php">Gestion produit</a></li>
                                <li><a href="<?php echo URL; ?>admin/gestion_salle.php">Gestion salle</a></li>

                            <?php
                            }
                            ?>
                            <li><a href="<?php echo URL; ?>connexion.php?action=deconnexion">Déconnexion</a></li>
                        <?php
                        }
                        ?>
                    </ul>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
            
        </div><!-- /.container -->
    </nav>
</header>