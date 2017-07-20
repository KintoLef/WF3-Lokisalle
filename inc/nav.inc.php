    <nav class="navbar navbar-inverse">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <a class="navbar-brand" href="<?php echo URL; ?>index.php">Lokisalle</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="<?php echo URL; ?>index.php">Accueil <span class="sr-only">(current)</span></a></li>
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
                        <li><a href="<?php echo URL; ?>connexion.php?action=deconnexion">Déconnexion</a></li>
                    <?php
                    }
                    ?>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Espace membre <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                    </ul>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
            
        </div><!-- /.container -->
    </nav>
</header>