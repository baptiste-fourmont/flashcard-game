<?php 
       /*
        La page pour la catégorie dans le jouer.php
        Le titre de la page: Jeu il permet d'afficher les catégories à gauche
        Ainsi que l'attribution du header pour la navbar et le fichier function qui est indispensable pour la connexion à la BDD
    */ 
    $titre = "Jouer";
    include '../header.php';
        
    $user = $_SESSION['user'];
    // On sélectionne toutes les catégories valides
    $GetCategorie = $BDD->prepare("SELECT * FROM categories WHERE valide = 1");
    $GetCategorie->execute();
    $resultat_categorie = $GetCategorie->fetchAll();
    
    // On sélectionne tout les deck valides 
    $GetDeck = $BDD->prepare("SELECT * FROM deck WHERE valide = 1");
    $GetDeck->execute();
    $resultat_deck = $GetDeck->fetchAll();

    // On stocke la valeur du thème choisi
    $ThemeChoisi = $_POST['affichercat'];
    // On récupère les informatiosn du deck choisi
    $GetDeckTheme = $BDD->prepare("SELECT * FROM deck WHERE valide = 1 AND categorie = :categorie ORDER BY cast(`playcount` as unsigned) DESC");
    $GetDeckTheme->bindParam(':categorie', $ThemeChoisi); 
    $GetDeckTheme->execute();
    $resultat_decktheme = $GetDeckTheme->fetchAll();

    // On fait une rêquete pour trier les joueur en fonctions des points dans l'ordre décroissant
    $ClassPlayer = $BDD->prepare("SELECT * FROM `users` ORDER BY cast(`point` as unsigned) DESC ");
    $ClassPlayer->execute();
    $resultat_classplayer = $ClassPlayer->fetchAll();
    
    // Si la requete est différent de POST on redirige sur la page jouer.php
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
      Redirect("jouer.php");
    }

?>

<html>
<head>
</head>
<body>
<div class="line1"></div>
<div class="line2"></div>

<div class="row">
  <div class="column left">
    <form action="jouer.php" method="POST">
    <!--
        On se situe dans la partie à Gauche de jouer.php
        Cette zone permet d'enregistrer les informations obtenu lors du jeu en cours du thème etc sur la partie joué pour qu'elle s'affiche en haut au dessus des decks les plus jouées
    -->
   
    <?  // On récupère les informations de l'utilisateur connecté sur la page et on récupère les informations des parties précédentes comme un objet
        $getlastgame = $BDD->prepare("SELECT * FROM JeuEnCours WHERE user = :user");
       $getlastgame->bindValue(':user',$user);
       $getlastgame->execute();
       $lastg = $getlastgame->fetch(PDO::FETCH_ASSOC);

        // on vérifie qu'il a déjà jouer une partie
       if ( $getlastgame->rowCount()>0) {
        // lastg['fini'] == 1 correspond à savoir si le joueur a bien terminé la patie en cours
       if($lastg['fini']==1){
        ?>
    <h2>Ma dernière partie</h2>
       <div class="lastg" name="block">
            <?php 
                // On affiche le thème de la dernière partie
                echo "<h1>";
                echo  $lastg['theme'];
                echo "</h1>";
            ?>
         <p class="qname">

            <?php 
                // On affiche le score final de la derrière partie
                echo "Score obtenu : ".$lastg['scorefinal']."/10";
            ?>
        </p>
        <p>
            <button name="continuerefaire" type="submit" value="<?= $row['nomdeck'] ?>">Refaire ce quiz</button>
        </p>
       </div>
       <? } }?>
    <h2 class="minimarge">QUIZ disponibles</h2>
    <?php foreach ($resultat_deck as $row){?>
       <div class="lastg" name="block">
            <?php 
                echo "<h1>";
                echo htmlspecialchars_decode($row['nomdeck']);
                echo "</h1>";
            ?>
         <p class="qname">

            <?php 
                echo '<p class="qname"> Difficulté : '.$row['difficulte'].'</p>';
                echo $row['categorie']." par ".$row['nom'];
            ?>
        </p>
        <p>
            <button name="continue" type="submit" value="<?= $row['nomdeck'] ?>">Faire ce quiz</button>
        </p>
       </div>
     <?php } ?>
  </div>
  </form>

              <div class="column middle">
                <a href="jouer.php" style="margin-left: 1%; text-decoration:none; color: black">Précédent</a>
                <h2>
                    Jeux Disponibles : <? echo $ThemeChoisi; ?>
                </h2>
                    <div class="grid-container">
                    <!--
                        On récupère tout les deck et on les affiche dans la section du milieu
                    -->
                    <?php foreach ($resultat_decktheme as $row){?>
                            <div class="grid-item" value="<?= $row['nom'] ?>"><?echo $row['nomdeck'];
                                 $getnbdeck = $BDD->prepare("SELECT * FROM deck WHERE categorie = :categorie");
                                 $getnbdeck->bindValue(':categorie',$row['nom']);
                                 $getnbdeck->execute();?>
                                 <p class="petit"> par <? echo htmlspecialchars_decode($row['nom']); ?> </p>
                                 <p class="petit"> Difficulté : <? echo $row['difficulte']; ?> </p>
                                 <!-- Le nombre de jeu qui ont été fait -->
                                 <p class="petit">Joué : <? echo $row['playcount']." " ?> fois </p>
                                 <form action="jouer.php" method="POST">
                                    <button class = "buttoncat"name="continue" type="submit" value="<?= $row['nomdeck'] ?>">Faire ce quiz</button>
                                 </form> 
                            </div>
                     <?php } ?>
                    </div>
                </div>

  <div class="column right">
    <h2>
        Meilleurs joueurs
    </h2>
    <!-- On classe tout les joueurs en fonction des points -->
    <?php foreach ($resultat_classplayer as $row){?>
      <div class="bestj">
          <h1><?php echo $row['userMAJ']; ?></h1>
          <p>
            Nombre de points total : <?php echo $row['point']; ?>
          </p>
          <p>
            Dernier jeu joué : <?php
                                 if($row['jeu'] ==NULL){
                                    echo "Aucun";
                                 }
                                 else{
                                    // Dernier jeu auquel il a joué
                                    echo $row['jeu']; 
                                 }
                                ?>
          </p>
        </div>
        <p class="minimarge"></p>
     <?php } ?>
        
  </div>
</div>

</body>
</html>