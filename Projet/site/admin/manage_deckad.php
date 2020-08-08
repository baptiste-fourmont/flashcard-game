<?php 
      /*
        La page pour gérer son deck
        Deck = Jeu de 10 cartes 
        Le titre de la page
        Ainsi que l'attribution du header pour la navbar et le fichier function qui est indispensable pour la connexion à la BDD
    */   
     $titre = "Gérer les decks";
     include '../header.php';
    
     if(!is_login()){
         Redirect('../util/connexion.php');
     }

    if(!is_admin()){
        Redirect('../index.php');
    } 
    
    $user = Secu($_SESSION['user']);
    // On récupère tout les deck créer par l'utilisateur afin qu'il puisse gèrer tout ses deck avec fetchall on les récupère tous
    $GetDeck = $BDD->prepare("SELECT * FROM deck");
    $GetDeck->bindValue(':user',$user);
    $GetDeck->execute();
    $resultat_deck = $GetDeck->fetchAll();
    
    // On récupère toutes les catégories
    $GetCat = $BDD->prepare("SELECT * FROM categories WHERE valide = 1");
    $GetCat->execute();
    $resultat_cat = $GetCat->fetchAll();

   
    
  
   // On vérjfje la requête est bien POST
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        // On voit si l'utiliateur à bien cliquer sur supprimer 
        if(isset($_POST['supprimer'])){
            // On récupère le nom du deck
            $nomdeck = Secu($_POST['supprimer']);
            $data = $BDD->prepare('DELETE FROM deck WHERE nomdeck = :nom');
            $data->bindParam(':nom', $nomdeck);
            $data->execute();
            echo 'Votre deck a été supprimé';
            // On raffraichit la page à l'aide de cette fonction
            header("Refresh:0");
        }
        if(isset($_POST['modifier'])){
            // Pas besoin de récupérer les caractères spéciaux
            $_SESSION['jeu'] = Secu($_POST['modifier']);
            $GetCard = $BDD->prepare("SELECT * FROM cartes WHERE valide = 1");
            $GetCard->bindValue(':user',$user);
            $GetCard->execute();
            $resultat_card = $GetCard->fetchAll();

            $deckres = $_POST['modifier'];
            $whersdeck = $BDD->prepare("SELECT * FROM deck WHERE `deck`.`nomdeck` = :id");
            $whersdeck->bindValue(':id', $deckres);
            $whersdeck->execute();
            $deckname = $whersdeck->fetch(PDO::FETCH_ASSOC);
            echo  "<form action='update_deck.php' method='POST' style='text-align : center;'";
            echo '<h2>Modifier votre deck</h2>';
            echo '<p>Votre deck doit être composé d\'obligatoirement 10 cartes à choisir parmi celles existantes</p></br>';
            
            // Bloc question
             /*
             resultat_card sont les cartes validé par l'administrateur cad valide = 1
             Pour sélectionner uniquement celle qui sont correct
             On utilise foreach pour récupérer tout les cartes valides et on récpète 10 fois la même procèdure afin d'avoir 10 questions
             Nous avons fait le choix d'utiliser echo ici pour rendre le site plus dynamique sur la même page on aurait pu très bien le faire du côté HTML
            
            */
            echo 'Question 1:';
            echo '<select name="q1">';
            foreach ($resultat_card as $row){
                if($deckname['q1']==$row['question']){
                    echo '<option value="'.$row['question'].'"selected>';
                    echo $row['question'];
                    echo "</option>";
                }
                else{
                    echo '<option value="'.$row['question'].'">';
                    echo $row['question'];
                    echo "</option>";
                }
            }
            echo '</select></br></br>';

            echo 'Question 2:';
            echo '<select name="q2">';
            foreach ($resultat_card as $row){
                if($deckname['q2']==$row['question']){
                    echo '<option value="'.$row['question'].'"selected>';
                    echo $row['question'];
                    echo "</option>";
                }
                else{
                    echo '<option value="'.$row['question'].'">';
                    echo $row['question'];
                    echo "</option>";
                }
            }
            echo '</select></br></br>';

            echo 'Question 3:';
            echo '<select name="q3">';
            foreach ($resultat_card as $row){
                if($deckname['q3']==$row['question']){
                    echo '<option value="'.$row['question'].'"selected>';
                    echo $row['question'];
                    echo "</option>";
                }
                else{
                    echo '<option value="'.$row['question'].'">';
                    echo $row['question'];
                    echo "</option>";
                }
            }
            echo '</select></br></br>';

            echo 'Question 4:';
            echo '<select name="q4">';
            foreach ($resultat_card as $row){
                if($deckname['q4']==$row['question']){
                    echo '<option value="'.$row['question'].'"selected>';
                    echo $row['question'];
                    echo "</option>";
                }
                else{
                    echo '<option value="'.$row['question'].'">';
                    echo $row['question'];
                    echo "</option>";
                }
            }
            echo '</select></br></br>';
            
            echo 'Question 5:';
            echo '<select name="q5">';
            foreach ($resultat_card as $row){
                if($deckname['q5']==$row['question']){
                    echo '<option value="'.$row['question'].'"selected>';
                    echo $row['question'];
                    echo "</option>";
                }
                else{
                    echo '<option value="'.$row['question'].'">';
                    echo $row['question'];
                    echo "</option>";
                }
            }
            echo '</select></br></br>';
            
            echo 'Question 6:';
            echo '<select name="q6">';
            foreach ($resultat_card as $row){
                if($deckname['q6']==$row['question']){
                    echo '<option value="'.$row['question'].'"selected>';
                    echo $row['question'];
                    echo "</option>";
                }
                else{
                    echo '<option value="'.$row['question'].'">';
                    echo $row['question'];
                    echo "</option>";
                }
            }
            echo '</select></br></br>';
            
            echo 'Question 7:';
            echo '<select name="q7">';
            foreach ($resultat_card as $row){
                if($deckname['q7']==$row['question']){
                    echo '<option value="'.$row['question'].'"selected>';
                    echo $row['question'];
                    echo "</option>";
                }
                else{
                    echo '<option value="'.$row['question'].'">';
                    echo $row['question'];
                    echo "</option>";
                }
            }
            echo '</select></br></br>';
            
            echo 'Question 8:';
            echo '<select name="q8">';
            foreach ($resultat_card as $row){
                if($deckname['q8']==$row['question']){
                    echo '<option value="'.$row['question'].'"selected>';
                    echo $row['question'];
                    echo "</option>";
                }
                else{
                    echo '<option value="'.$row['question'].'">';
                    echo $row['question'];
                    echo "</option>";
                }
            }
            echo '</select></br></br>';
            
            echo 'Question 9:';
            echo '<select name="q9">';
            foreach ($resultat_card as $row){
                if($deckname['q9']==$row['question']){
                    echo '<option value="'.$row['question'].'"selected>';
                    echo $row['question'];
                    echo "</option>";
                }
                else{
                    echo '<option value="'.$row['question'].'">';
                    echo $row['question'];
                    echo "</option>";
                }
            }
            echo '</select></br></br>';
            
            echo 'Question 10:';
            echo '<select name="q10">';
            foreach ($resultat_card as $row){
                if($deckname['q10']==$row['question']){
                    echo '<option value="'.$row['question'].'"selected>';
                    echo $row['question'];
                    echo "</option>";
                }
                else{
                    echo '<option value="'.$row['question'].'">';
                    echo $row['question'];
                    echo "</option>";
                }
            }
            echo '</select></br></br>';

            echo 'Theme :';
            echo '<select name="theme">';
            foreach ($resultat_cat as $row){
                if($deckname['categorie']==$row['nom']){
                    echo '<option value="'.$row['question'].'"selected>';
                    echo $row['nom'];
                    echo "</option>";
                }
                else{
                    echo '<option value="'.$row['nom'].'">';
                    echo $row['nom'];
                    echo "</option>";
                }
            }
            echo '</select></br></br>';
            echo 'Difficulté :';
            echo '<select name="difficulte">';
            echo '<option value="Facile">';
            echo 'Facile';
            echo "</option>";
            echo '<option value="Normale">';
            echo 'Normale';
            echo "</option>";
            echo '<option value="Difficile">';
            echo 'Difficile';
            echo "</option>";

            echo '</select></br></br>';
        
            echo "<button type='submit' nom='envoyer' class='button_site'>Envoyer</button>";

            echo "</form>";
            die();
        } 
    
    }

    
?>


        <form action="manage_deckad.php" method="POST">
            <table class="tabadmin" style="width: 20%; height: 75px;">
                <tbody>
                    <tr>
                        <td style="width: 60%;" class="tdredac">
                            Choisissez le deck à éditer
                        </td>
                        <td style="width: 40%;" class="tdredac">
                            Action :
                        </td>
                    </tr>
                    <tr>
                        <?php foreach ($resultat_deck as $row){?>
                            <tr>
                                <td style="width: 60%;" class="tdredacq">
                                    <?echo htmlspecialchars_decode($row['nomdeck']); ?>
                                </td>
                                <td style="width: 40%;" class="tdredacq">
                                    <button name="modifier" type="submit" value="<?= htmlspecialchars_decode($row['nomdeck']); ?>" class="butredac" >Modifier le deck</button>
                                    <button name="supprimer" type="submit" value="<?= htmlspecialchars_decode($row['nomdeck']); ?>" class="butredac" >Supprimer le deck</button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tr>
                </tbody>
            </table>
        </form>
    </body> 
</html>