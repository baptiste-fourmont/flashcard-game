

<?php
  require 'function.php';
  /*
      Ce fichier permet l'intégration plus facile aux nouvelles pages
      Elle permet d'intérer la navbar plus facilement et la rendre plus dynamique.
      Nous avons fait le choix d'utiliser HTTP_HOST afin de le rendre compatible dès l'installation au lieu 
      d'utiliser directement href="lesite.com/assets/css/style.css"> par exemple.
  
     
      On utilise HTTP_HOST afin de pouvoir utiliser les rédirections peu importe si il est en local ou sur n'importe quelle site
      Par exemple pour tester le site si une personne l'installe sur sa machine il pourra directement être fonctionnel.        
  
      Si on se place du principe que la navbar est utilisé sur tout le site et qu'on veut directement l'implémenté avec ce fichier.php
      Si l'on utilise des /util/connexion.php on ne pointera pas vers le bon chemin car l'adresse de la page changera.
  
      Il se peut que vous rencontré des mots marqué en chinois j'utilise la distribution DEEPIN sur Linux et mon éditeur Deepin Editor ne pouvait pas encoder en UTF-8 
      Je n'ai pas pu modifié tout les carractères en chinois ou mandarin je tiens à m'en excusez.
      Merci 
  
  */
  ?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
    <title><?php echo $titre; ?></title>
    <?php 
      if(is_login()){ // Nous permet de vérifier si l'utilisateur est connecté et si oui, d'appliquer le thème que celui ci a choisi
          $user = Secu($_SESSION['user']);
          $valcss = $BDD->prepare("SELECT * FROM users WHERE user = '$user'");
          $valcss->execute();
          $rowcss = $valcss->fetch(PDO::FETCH_ASSOC);
          $cssvalue = $rowcss['graph'];
          if($cssvalue > 4 || $cssvalue < 1){
              $cssvalue = "";
          }
      
          ?>
    <link rel="stylesheet" href="<?php $_SERVER['HTTP_HOST'] ?>/assets/css/style<? echo "$cssvalue"; ?>.css">
    <?php  } 
      else{ ?>
    <link rel="stylesheet" href="<?php $_SERVER['HTTP_HOST'] ?>/assets/css/style.css">
    <?php  }
      ?>
  </head>
  <body>
    <nav class="navtop">
      <div class ="logo">
        <?php if( is_login() ) : ?>
        <h4>Quizoo</h4>
        <?php else : ?>
        <h4>Quizoo</h4>
        <?php endif; ?>
      </div>
      <ul class="nav-links">
        <li>
          <a href="<?php $_SERVER['HTTP_HOST'] ?>/index.php">Accueil</a>
        </li>
        <li>
          <?php if (is_login()) : ?>
          <a href="<?php $_SERVER['HTTP_HOST'] ?>/jeu/jouer.php">Jouer</a>
          <?php endif; ?>
        </li>
        <li>
          <?php if (is_login()) : ?>
          <a href="<?php $_SERVER['HTTP_HOST'] ?>/util/deconnexion.php">Deconnexion</a>
          <?php else : ?>
          <a href="<?php $_SERVER['HTTP_HOST'] ?>/util/connexion.php">Connexion</a>
          <?php endif; ?>
        </li>
      </ul>
    </nav>
    <?php if (is_login()) : ?>
    <nav class="navuser">
      <div class ="sousmenu">
        <?php if (is_login()) : ?>
        <h4><?php echo $_SESSION['user']." - ". usertype() ?></h4>
        <?php else : ?>
        <h4>Bonjour Invité</h4>
        <?php endif; ?>
      </div>
      <ul class="navuser-links">
        <li>
          <a href="<?php $_SERVER['HTTP_HOST'] ?>/user/profile.php">Mon Profil</a>
          <ul class="none">
            <li>
              <a href="<?php $_SERVER['HTTP_HOST'] ?>/user/pswd_user.php">Changer votre mot de Passe</a>
            </li>
            <li>
              <a href="<?php $_SERVER['HTTP_HOST'] ?>/util/deconnexion.php">Déconnexion</a>
            </li>
          </ul>
        </li>
        <?php if( is_login() && is_admin() ) : ?>
        <li>
          <a href="<?php $_SERVER['HTTP_HOST'] ?>/admin.php">Panel Admin</a>
          <ul class="none">
            <li>
              <a href="<?php $_SERVER['HTTP_HOST'] ?>/admin/accmanage.php">Gérer les comptes</a>
            </li>
            <li>
              <a href="<?php $_SERVER['HTTP_HOST'] ?>/admin/update_car.php">Gérer les cartes</a>
            </li>
            <li>
              <a href="<?php $_SERVER['HTTP_HOST'] ?>/admin/manage_deckad.php">Gérer les decks</a>
            </li>
            <li>
              <a href="<?php $_SERVER['HTTP_HOST'] ?>/admin/manage_catad.php">Gérer les catégories</a>
            </li>
            <li>
              <a href="<?php $_SERVER['HTTP_HOST'] ?>/admin/add_categorie.php">Ajouter une Catégorie</a>
            </li>
            <li>    
              <a href="<?php $_SERVER['HTTP_HOST'] ?>/admin/manage_categories.php">Voir les demandes de Catégories</a>
            </li>
          </ul>
        </li>
        <?php elseif ( is_login() && is_redac() ) : ?>
        <li>
          <a href="<?php $_SERVER['HTTP_HOST'] ?>/redac.php">Panel Rédacteur</a>
          <ul class="none">
            <li>
              <a href="<?php $_SERVER['HTTP_HOST'] ?>/redac/add_card.php">Ajouter une carte</a>
            </li>
            <li>
              <a href="<?php $_SERVER['HTTP_HOST'] ?>/redac/update_car.php">Modifier une carte</a>
            </li>
            <li>
              <a href="<?php $_SERVER['HTTP_HOST'] ?>/redac/submit_categories.php">Demander une Catégorie</a>
            </li>
            <li>
              <a href="<?php $_SERVER['HTTP_HOST'] ?>/redac/creerdeck.php">Créer un Deck</a>
            </li>
            <li>
              <a href="<?php $_SERVER['HTTP_HOST'] ?>/redac/manage_deck.php">Gérer ses Decks</a>
            </li>
          </ul>
        </li>
        <?php endif; ?>
      </ul>
    </nav>
    <?php endif; ?>

