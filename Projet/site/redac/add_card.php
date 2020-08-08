<?
     /*
        La page de création d'une carte
        Le titre de la page
        Ainsi que l'attribution du header pour la navbar et le fichier function qui est indispensable pour la connexion à la BDD
    */    
    $titre = "Ajouter une carte";
    include '../header.php'; 

     if(!is_login()){
         Redirect('../util/connexion.php');
     }

    if(!is_redac()){
        Redirect('../index.php');
    }
    
    // On récupére toutes les cartes avec fetchALL et on stock le résultat grâce à une variable $resultat_card
    $GetCard = $BDD->prepare("SELECT * FROM cartes");
    $GetCard->execute();
    $resultat_card = $GetCard->fetchAll(); 
    
    // On vérifie si la méthode utilisé est bien POST
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        /*
            On récolte les information nécessaire
            valide nous permet de vérifier si les informations rempli ne sont pas vide
        */
        $valide = true;
        // On utilise htmlspecialchars_decode on a eu quelque problème avec  les espace on les prends en compte avec cette fonction
        $question = Secu($_POST['question']);
        $reponse = Secu($_POST['reponse']);
        $createur = Secu($_SESSION['user']);
        $theme = Secu($_POST['theme']);
        $difficulty = Secu($_POST['difficulty']);
       
        $validite = 1; // 1 true; 0; false
        
        if(empty($question)){
            die('Vous devez insérer une question.');
            $valide = false;
        }
        if(empty($reponse)){
            die('Vous devez insérer une réponse.');
            $valide = false;
        }
        
        // On lance une rêquete SQL
        $data_question= $BDD->prepare('SELECT * FROM cartes WHERE question = :question');
        $data_question->bindParam(':question', $question);
        $data_question->execute();
        // On vérifie si la question existe déjà si elle n'existe pas alors on peut insérer la question
         if( !($data_question->rowCount() > 0)) { 
            // On vérifie qu'enne n'est pas vide
            if($valide){
                $valide = false;
                $validee = 0;
 
                $data = $BDD->prepare("INSERT INTO cartes (question, reponse, theme, difficulty ,  createur, valide) VALUES (:question, :reponse, :theme, :difficulty, :createur, :valide  )");
                // BindValue on veut quelle soit non modifiable après
                $data->bindParam(':question', $question); 
                $data->bindParam(':reponse', $reponse);
                $data->bindParam(':theme', $theme);
                $data->bindParam(':difficulty', $difficulty);
                $data->bindParam(':createur', $createur);
                $data->bindParam(':valide', $validite);
                $data->execute();
                echo 'Votre carte a bien été ajoutée';
            
             // Chaque refresh ajoute une carte question vide donc au cas ou 
          
                }else{
                    echo 'Vous devez remplir les champs nécessaires';
                }
          }else{
            echo 'Cette carte existe déjà';
         }
    }
?>

   

 
        <table class="tabadmin" style="width: 30%; height: 75px;">
            <tbody>
                <tr>
                    <td style="width: 15%;" class="tdredac">Ajouter une carte</td>
                </tr>
                    <tr>
                        <td style="width: 20%;" class="tdredacq">
                            <form action="add_card.php" method="POST" >
                                <p>
                                    <label for="question">Question</label>
                                </p>
                                <input type="text" name="question" required="required">
                                <p class="minimarge">
                                    <label for="reponse">Réponse</label>
                                </p>
                                <input type="text" name="reponse" required="required">
                                <p>
                                    Difficulté :
                                </p>
                                <select name="difficulty">
                                    <option value="Facile" selected="selected">Facile</option>
                                    <option value="Normale">Normale</option>
                                    <option value="Difficile">Difficile</option>
                                   </select>
                                 
                                <p>
                                	Thème :
                                </p> 
                                <select name="theme">   
                                    <?php
                                        /*
                                            On séléctionnes toutes les catégories qui ont été validé par l'administrateur 
                                            Pour sélectionner uniquement celle qui sont correct
                                            On utilise foreach pour récupérer tout les cartes valides
                                        */
                                        $recup = $BDD->query('SELECT * FROM categories WHERE valide = 1');
                                        $recup->execute();
                                    ?>
                                    <?php foreach ($recup as $row){?>
                                        <option value="<?= $row['nom'] ?>"> <?echo $row['nom']; ?></option>
                                    <?php } ?>
                                </select>
                                <p class="minimarge">
	                                <input type="submit" class="boutgray" value="Envoyer">
	                                <button type="reset" value="reset" class="boutgray">Réintialiser</button>
                                </p>
                            </form>
                        </td>
                    </tr>
            </tbody>
        </table>
    </body> 
</html>