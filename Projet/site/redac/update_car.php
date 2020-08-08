<?   /*
        La page pour modifier ses cartes
        Le titre de la page: Gérer mes cartes
        Ainsi que l'attribution du header pour la navbar et le fichier function qui est indispensable pour la connexion à la BDD
    */ 
    $titre = "Gérer mes cartes";
    include '../header.php'; 
    $user = Secu($_SESSION['user']);
    
    // On veut récupérer l'id de l'user qui est sur la page pour ca on doit utiliser une rêquête fetch(PDO:FETCH_ASSOC) qui correspond à un objet user
    $userid = $BDD->prepare("SELECT * FROM users WHERE user = '$user'");
    $userid->execute();
    $rowid = $userid->fetch(PDO::FETCH_ASSOC);
    $iduser = $rowid['id'];

    if(!is_login()){
         Redirect('../util/connexion.php');
    }
    if(is_admin()){
        Redirect("../admin/update_carad.php");
    } 
    if(!is_redac()){
        Redirect("../index.php");
    } 
    
    // On veut récupèrer toutes les cartes validé par l'administrateur et dont l'user connecté sur la page en est le créateur et on les tri par ordre décroissant
    $createur = Secu($_SESSION["user"]);
    $GetMyCard = $BDD->prepare('SELECT * FROM cartes WHERE valide = 1 AND createur = :createur ORDER by createur DESC');
    $GetMyCard->bindValue(':createur', $createur);
    $GetMyCard->execute();
    $resultat_card = $GetMyCard->fetchAll(); 

    // On vérifie que la méthode dans la form est bien post
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        if(isset($_POST['question'])){
            $question = $_POST['question'];
            $whersquestion = $BDD->prepare("SELECT * FROM cartes WHERE `cartes`.`id` = :id");
            $whersquestion->bindValue(':id', $question);
            $whersquestion->execute();
            $questionname = $whersquestion->fetch(PDO::FETCH_ASSOC);
            $nomdelaquestion = $questionname['question']; 

            $savequestion = $BDD->prepare("UPDATE `users` SET `carteamodif` = :carteamodif WHERE `users`.`id` = :id");
            $savequestion->bindParam(':carteamodif', $question);
            $savequestion->bindParam(':id', $iduser);
            $savequestion->execute();
           // $savequestion = ("UPDATE `users` SET `carteamodif` = '$question' WHERE `users`.`id` = $iduser;");
     
            //$preguntaname = ("UPDATE `users` SET `oldcardname` = '$nomdelaquestion' WHERE `users`.`id` = $iduser;");
            $preguntaname = $BDD->prepare("UPDATE `users` SET `oldcardname` = :oldcardname WHERE `users`.`id` = :id");
            $preguntaname->bindParam(':oldcardname', $nomdelaquestion);
            $preguntaname->bindParam(':id', $iduser);
            $preguntaname->execute();
            
            ?>  
                <table class="tabadmin" style="width: 643px; height: 75px;">
                    <tbody>
                        <tr>
                            <td style="width: 161px;" class="tdadmin">
                                Par quoi voulez modifier la question : 
                                    <p class="minimarge">
                                        <? echo $nomdelaquestion; ?>   
                                    </p>
                            </td>
                            <td style="width: 100px;" class="tdadmin">
                                <form action="update_car.php" method="POST"> 
                                    <input type="text" name="questionmodif" required="required">
                                    <button type="submit" class="butredac" >Modifier la carte</button>
                                </form>
                                <form action="update_car.php" method="POST"> 
                                    <button name="no" type="submit" class="butredac" >Annuler et retourner à la gestion des cartes</button>
                                </form>
                            </td>
                        </tr>
                        <tr>
                        </tr>
                    </tbody>
                </table>
                <?
                die();
        }

        if(isset($_POST['questionmodif'])){
             $question = $_POST['questionmodif'];
             $questionancienne = $rowid['carteamodif'];
             $changecardincards = $BDD->prepare("UPDATE `cartes` SET `question` = :question WHERE `cartes`.`id` = :id");
             $changecardincards->bindParam(':question', $question);
             $changecardincards->bindParam(':id', $questionancienne);
             $changecardincards->execute();
             
             //$changecardincards = ("UPDATE `cartes` SET `question` = '$question' WHERE `cartes`.`id` = $questionancienne;");
             //$BDD->exec($changecardincards);


             $oldq = $rowid['oldcardname'];
             //$sql1 = "UPDATE deck SET q1='$question' WHERE q1='$oldq'";
             //$BDD->exec($sql1);

             $sql1 = $BDD->prepare("UPDATE `deck` SET `q1` = :q1 WHERE `q1` = :q1old");
             $sql1->bindParam(':q1', $question);
             $sql1->bindParam(':q1old', $oldq);
             $sql1->execute();

             $sql2 = $BDD->prepare("UPDATE `deck` SET `q2` = :q1 WHERE `q2` = :q1old");
             $sql2->bindParam(':q1', $question);
             $sql2->bindParam(':q1old', $oldq);
             $sql2->execute();

             $sql3 = $BDD->prepare("UPDATE `deck` SET `q3` = :q1 WHERE `q3` = :q1old");
             $sql3->bindParam(':q1', $question);
             $sql3->bindParam(':q1old', $oldq);
             $sql3->execute();
            
             $sql4 = $BDD->prepare("UPDATE `deck` SET `q4` = :q1 WHERE `q4` = :q1old");
             $sql4->bindParam(':q1', $question);
             $sql4->bindParam(':q1old', $oldq);
             $sql4->execute();
            

             $sql5 = $BDD->prepare("UPDATE `deck` SET `q5` = :q1 WHERE `q5` = :q1old");
             $sql5->bindParam(':q1', $question);
             $sql5->bindParam(':q1old', $oldq);
             $sql5->execute();

             $sql6 = $BDD->prepare("UPDATE `deck` SET `q6` = :q1 WHERE `q6` = :q1old");
             $sql6->bindParam(':q1', $question);
             $sql6->bindParam(':q1old', $oldq);
             $sql6->execute();
    

             $sql7 = $BDD->prepare("UPDATE `deck` SET `q7` = :q1 WHERE `q7` = :q1old");
             $sql7->bindParam(':q1', $question);
             $sql7->bindParam(':q1old', $oldq);
             $sql7->execute();
             
             $sql8 = $BDD->prepare("UPDATE `deck` SET `q8` = :q1 WHERE `q8` = :q1old");
             $sql8->bindParam(':q1', $question);
             $sql8->bindParam(':q1old', $oldq);
             $sql8->execute();

             $sql9 = $BDD->prepare("UPDATE `deck` SET `q9` = :q1 WHERE `q9` = :q1old");
             $sql9->bindParam(':q1', $question);
             $sql9->bindParam(':q1old', $oldq);
             $sql9->execute();
       
             $sql10 = $BDD->prepare("UPDATE `deck` SET `q10` = :q1 WHERE `q10` = :q1old");
             $sql10->bindParam(':q1', $question);
             $sql10->bindParam(':q1old', $oldq);
             $sql10->execute();


             header("Refresh:0");

        }
        if(isset($_POST['reponse'])){
            $question = $_POST['reponse'];
            $whersquestion = $BDD->prepare("SELECT * FROM cartes WHERE `cartes`.`id` = :id");
            $whersquestion->bindValue(':id', $question);
            $whersquestion->execute();
            $questionname = $whersquestion->fetch(PDO::FETCH_ASSOC);
            $nomdelaquestion = $questionname['question'];
            //$savequestion = ("UPDATE `users` SET `carteamodif` = '$question' WHERE `users`.`id` = $iduser;");
            //$BDD->exec($savequestion);
            

            $savequestion = $BDD->prepare("UPDATE `users` SET `carteamodif` = :carteamodif WHERE `users`.`id` = :id");
            $savequestion->bindParam(':carteamodif', $question);
            $savequestion->bindParam(':id', $iduser);
            $savequestion->execute();
           // $savequestion = ("UPDATE `users` SET `carteamodif` = '$question' WHERE `users`.`id` = $iduser;");
     
            //$preguntaname = ("UPDATE `users` SET `oldcardname` = '$nomdelaquestion' WHERE `users`.`id` = $iduser;");
            $preguntaname = $BDD->prepare("UPDATE `users` SET `oldcardname` = :oldcardname WHERE `users`.`id` = :id");
            $preguntaname->bindParam(':oldcardname', $nomdelaquestion);
            $preguntaname->bindParam(':id', $iduser);
            $preguntaname->execute();

            //$preguntaname = ("UPDATE `users` SET `oldcardname` = '$nomdelaquestion' WHERE `users`.`id` = $iduser;");
            //$BDD->exec($preguntaname);
            ?>  
                <table class="tabadmin" style="width: 643px; height: 75px;">
                    <tbody>
                        <tr>
                            <td style="width: 161px;" class="tdadmin">
                                Par quoi voulez modifier la réponse à la question : 
                                    <p class="minimarge">
                                        <? echo $nomdelaquestion; ?>   
                                    </p>
                            </td>
                            <td style="width: 100px;" class="tdadmin">
                                <form action="update_car.php" method="POST"> 
                                    <input type="text" name="reponsemodif" required="required">
                                    <button type="submit" value="$reponse" class="butredac" >Modifier la carte</button>
                                </form>
                                <form action="update_car.php" method="POST"> 
                                    <button name="no" type="submit" class="butredac" >Annuler et retourner à la gestion des cartes</button>
                                </form>
                            </td>
                        </tr>
                        <tr>
                        </tr>
                    </tbody>
                </table>
                <?
                die();
        }
        if(isset($_POST['reponsemodif'])){
             $reponse = $_POST['reponsemodif'];
             $questionancienne = $rowid['carteamodif'];
           //  $changecardincards2 = ("UPDATE `cartes` SET `reponse` = '$reponse' WHERE `cartes`.`id` = $questionancienne;");
             $changecardincards2 = $BDD->prepare("UPDATE `cartes` SET `reponse` = :reponse WHERE `cartes`.`id` = :id");
             $changecardincards2->bindParam(':reponse', $reponse);
             $changecardincards2->bindParam(':id', $questionancienne);
             $changecardincards2->execute();
             header("Refresh:0");
        }
        if(isset($_POST['categorie'])){
            $question = $_POST['categorie'];
            $whersquestion = $BDD->prepare("SELECT * FROM cartes WHERE `cartes`.`id` = :id");
            $whersquestion->bindValue(':id', $question);
            $whersquestion->execute();
            $questionname = $whersquestion->fetch(PDO::FETCH_ASSOC);
            $nomdelaquestion = $questionname['question'];

            $savequestion = $BDD->prepare("UPDATE `users` SET `carteamodif` = :carteamodif WHERE `users`.`id` = :id");
            $savequestion->bindParam(':carteamodif', $question);
            $savequestion->bindParam(':id', $iduser);
            $savequestion->execute();

            $preguntaname = $BDD->prepare("UPDATE `users` SET `oldcardname` = :oldcardname WHERE `users`.`id` = :id");
            $preguntaname->bindParam(':oldcardname', $nomdelaquestion);
            $preguntaname->bindParam(':id', $iduser);
            $preguntaname->execute();
            ?>  
                <table class="tabadmin" style="width: 643px; height: 75px;">
                    <tbody>
                        <tr>
                            <td style="width: 161px;" class="tdadmin">
                                Quelle difficultée voulez vous attribuer à la question : 
                                    <p class="minimarge">
                                        <? echo $nomdelaquestion; ?>   
                                    </p>
                            </td>
                            <td style="width: 100px;" class="tdadmin">
                                <form action="update_car.php" method="POST"> 
                                    <select name="categconfirm">   
                                       <?php
                                            $recup = $BDD->query('SELECT * FROM categories WHERE valide = 1');
                                            $recup->execute();
                                        ?>
                                        <?php foreach ($recup as $row){?>
                                           <option value="<?= $row['nom'] ?>"> <?echo htmlspecialchars_decode($row['nom']); ?></option>
                                        <?php } ?>
                                    </select>
                                    <button type="submit" value="$reponse" class="butredac" >Modifier la carte</button>
                                </form>
                                <form action="update_car.php" method="POST"> 
                                    <button name="no" type="submit" class="butredac" >Annuler et retourner à la gestion des cartes</button>
                                </form>
                            </td>
                        </tr>
                        <tr>
                        </tr>
                    </tbody>
                </table>
                <?
                die();
        }
        if(isset($_POST['categconfirm'])){
             $categorie = $_POST['categconfirm'];
             $questionancienne = $rowid['carteamodif'];
             $changecardincards3 = ("UPDATE `cartes` SET `theme` = '$categorie' WHERE `cartes`.`id` = $questionancienne;");
             $BDD->exec($changecardincards3);
             header("Refresh:0");

        }
        if(isset($_POST['difficulty'])){
            $question = $_POST['difficulty'];
            $whersquestion = $BDD->prepare("SELECT * FROM cartes WHERE `cartes`.`id` = :id");
            $whersquestion->bindValue(':id', $question);
            $whersquestion->execute();
            $questionname = $whersquestion->fetch(PDO::FETCH_ASSOC);

            $savequestion = $BDD->prepare("UPDATE `users` SET `carteamodif` = :carteamodif WHERE `users`.`id` = :id");
            $savequestion->bindParam(':carteamodif', $question);
            $savequestion->bindParam(':id', $iduser);
            $savequestion->execute();

            $preguntaname = $BDD->prepare("UPDATE `users` SET `oldcardname` = :oldcardname WHERE `users`.`id` = :id");
            $preguntaname->bindParam(':oldcardname', $nomdelaquestion);
            $preguntaname->bindParam(':id', $iduser);
            $preguntaname->execute();
            ?>  
                <table class="tabadmin" style="width: 643px; height: 75px;">
                    <tbody>
                        <tr>
                            <td style="width: 161px;" class="tdadmin">
                                Quelle difficultée voulez vous attribuer à la question : 
                                    <p class="minimarge">
                                        <? echo $nomdelaquestion; ?>   
                                    </p>
                            </td>
                            <td style="width: 100px;" class="tdadmin">
                                <form action="update_car.php" method="POST"> 
                                    <select name="difficultyconfirm">
                                        <option value="Facile" selected="selected">Facile</option>
                                        <option value="Normale">Normale</option>
                                        <option value="Difficile">Difficile</option>
                                    </select>
                                    <button type="submit" value="$reponse" class="butredac" >Modifier la carte</button>
                                </form>
                                <form action="update_car.php" method="POST"> 
                                    <button name="no" type="submit" class="butredac" >Annuler et retourner à la gestion des cartes</button>
                                </form>
                            </td>
                        </tr>
                        <tr>
                        </tr>
                    </tbody>
                </table>
                <?
                die();
        }
        if(isset($_POST['difficultyconfirm'])){
             $difficulty = $_POST['difficultyconfirm'];
             $questionancienne = $rowid['carteamodif'];
             $changecardincards4 = $BDD->prepare("UPDATE `cartes` SET `difficulty` = :difficulty WHERE `cartes`.`id` = :id");
             $changecardincards4->bindParam(':difficulty', $difficulty);
             $changecardincards4->bindParam(':id', $questionancienne);
             $changecardincards4->execute();

             //$changecardincards4 = ("UPDATE `cartes` SET `difficulty` = '$difficulty' WHERE `cartes`.`id` = $questionancienne;");
       
             header("Refresh:0");

        }
        if(isset($_POST['delete'])){
            ?>  
                <table class="tabadmin" style="width: 643px; height: 75px;">
                    <tbody>
                        <tr>
                            <td style="width: 161px;" class="tdadmin">Voulez vous vraiment supprimer cette carte ?
                                <p style="color: red;">
                                    (Attention cela supprimera les decks dans lesquels cette question est présente !)
                                </p> 
                            </td>
                            <form action="update_car.php" method="POST"> 
                                <td style="width: 100px;" class="tdadmin"> <button name="yes" type="submit" value="<?= $_POST['delete'] ?>" class="butpanadel" >Oui</button></td>
                                <td style="width: 100px;" class="tdadmin"><button name="no" type="submit" class="butpanadel" >Non</button> </td>
                            </form>
                        </tr>
                        <tr>
                        </tr>
                    </tbody>
                </table>
                <?
                die();
        }
        if(isset($_POST['yes'])){
                $question = $_POST['yes'];
                $data = $BDD->prepare('DELETE FROM cartes WHERE question = :question');
                $data->bindParam(':question', $question);
                $data->execute();
                $data2 = $BDD->prepare('DELETE FROM deck WHERE q1 = :question OR q2 = :question OR q3 = :question OR q4 = :question OR q5 = :question OR q6 = :question OR q7 = :question OR q8 = :question OR q9 = :question OR q10 = :question');
                $data2->bindParam(':question', $question);
                $data2->execute();
                header("Refresh:0");
            }
            if(isset($_POST['no'])){
                header("Refresh:0");
            }
    }
    
  
    
?>
        

        <h2 class="hredac"> Bienvenue <?php echo $user; ?> dans la gestions de vos cartes </h2>
        <h3 class="hredac"> Voici la liste de vos cartes choisissez ce que vous voulez faire ! </h3>
        <table class="tabadmin" style="width: 75%; height: 75px;">
            <tbody>
                <tr>
                    <td style="width: 15%;" class="tdredac">Question</td>
                    <td style="width: 15%;" class="tdredac">Réponse</td>
                    <td style="width: 15%;" class="tdredac">Catégorie</td>
                    <td style="width: 15%;" class="tdredac">Difficulté</td>
                    <td style="width: 34%;" class="tdredac">Actions</td>
                </tr>
                <!-- On utilise foreach pour sélectionner toutes les cartes valides disponnibles pour quelles soient toutes affiché, cette section correspond aau tableau permettant de modifier complétement les cates-->
                <?php foreach ($resultat_card as $row){?>
                    <tr>
                        <td style="width: 15%;" class="tdredacq"><?echo $row['question']; ?></td>
                        <td style="width: 15%;" class="tdredacq"><?echo $row['reponse']; ?></td>
                        <td style="width: 15%;" class="tdredacq"><?echo htmlspecialchars_decode($row['theme']); ?></td>
                        <td style="width: 15%;" class="tdredacq"><?echo $row['difficulty']; ?></td>
                        <td style="width: 20%;" class="tdredacq">
                            <form action="update_car.php" method="POST">
                                <button name="question" type="submit" value="<?= $row['id'] ?>" class="butredac" >Modifier la question</button>
                                <button name="reponse" type="submit" value="<?= $row['id'] ?>" class="butredac" >Modifier la réponse</button>
                                <button name="categorie" type="submit" value="<?= $row['id'] ?>" class="butredac" >Modifier la catégorie</button>
                                <button name="difficulty" type="submit" value="<?= $row['id'] ?>" class="butredac" >Modifier la difficulté</button>
                                <button name="delete" type="submit" value="<?= $row['question'] ?>" class="butredac" >Supprimer la carte</button>
                            </form>
                        </td>
                    <tr>
                <?php } ?>
            </tbody>
        </table>

    </body> 
</html>