<?
     /*
        La page pour créer un deck
        Deck = Jeu de 10 cartes 
        Le titre de la page
        Ainsi que l'attribution du header pour la navbar et le fichier function qui est indispensable pour la connexion à la BDD
    */    
    $titre = "Panel Rédacteur";
    include '../header.php'; 
    $user = Secu($_SESSION['user']);

    // On récupère toutes les catégories validé pr l'administrateur    
    $GetCategorie = $BDD->prepare("SELECT * FROM categories WHERE valide = 1");
    $GetCategorie->execute();
    $resultat_categorie = $GetCategorie->fetchAll();

      if(!is_login()){
        Redirect('../util/connexion.php');
    }

    if( !is_redac()){
        Redirect('../index.php');
    }
    
     // On vérifie si la requête est bien POST
     if($_SERVER["REQUEST_METHOD"] == "POST") {
        // Informations nécessaire pour le bon fonctionnement
        $nom = Secu($_SESSION['user']);
        $nomdeck = Secu($_POST['nomdeck']);
        $valide = 0;
        $categorie =  Secu($_POST['categoriedeck']);
    
        // On récupère dans POST le nom du deck pour vérifier s'il existe pas déjà
        $insert_deck = $BDD->prepare('SELECT * FROM deck WHERE nomdeck = :nomdeck');
        $insert_deck->bindValue(':nomdeck', $nomdeck);
        $insert_deck->execute();

        // On vérifie si il existe pas déjà
       if( !($insert_deck->rowCount() > 0)) { 
             $playercount = 0;
             $difficulte = "Facile";
             // $sql = "INSERT INTO deck (nom, nomdeck,valide, playcount, categorie, difficulte) VALUES('$nom', '$nomdeck', '$valide', 0, '$categorie', 0)";
             // $BDD->exec($sql);
            $stmt = $BDD->prepare("INSERT INTO deck (nom, nomdeck,valide, playcount, categorie, difficulte) VALUES (:nom, :nomdeck, :valide, :playercount, :categorie, :difficulte )");
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':nomdeck', $nomdeck);
            $stmt->bindParam(':valide', $valide);
            $stmt->bindParam(':categorie', $categorie);
            $stmt->bindParam(':playercount', $playercount);
            $stmt->bindParam(':difficulte', $difficulte);
            $stmt->execute();
              
           
              echo 'Votre deck a été crée avec succès !';
              Redirect('manage_deck.php');
        }
    }
    
?>


    <table class="tabadmin" style="width: 30%; height: 75px;">
            <tbody>
                <tr>
                    <td style="width: 15%;" class="tdredac">Créer un nouveau deck</td>
                </tr>
                    <tr>
                        <td style="width: 20%;" class="tdredacq">
                          <form action="creerdeck.php" method="POST">
                              <p class="minimarge">
                                  <label for="nom">Nom du Deck</label></br>
                                  <input type="text" name="nomdeck" required="required"></br></br>
                                  <?php if ($resultat_categorie) : ?>
                                  <p>
                                    Thème :
                                  </p> 
                                   <!--
                                            On séléctionnes toutes les catégories qui ont été validé par l'administrateur 
                                            Pour sélectionner uniquement celle qui sont correct
                                            On utilise foreach pour récupérer tout les cartes valides/

                                           On récupère dans resultat_categorie les nom de chaque catégories pour qu'on est dans le menu select toutes les catégories valide 
                                    -->
                                  <select name="categoriedeck">
                                    <?php foreach ($resultat_categorie as $row){?>
                                    <option value="<?= $row['nom'] ?>"> <?echo $row['nom']; ?></option>
                                    <?php } endif ?>    
                                  </select>
                                  <p class="minimarge">
                                    <button type="submit" value="valider" name="valider" class="boutgray">Valider</button>
                                  </p>
                                </p>
                         </form>
                        </td>
                    </tr>
            </tbody>
        </table>
    </body> 
</html>


    
    
