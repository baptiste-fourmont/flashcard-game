<?
    /*
        La page de création du compte 
        Le titre de la page
        Ainsi que l'attribution du header pour la navbar et le fichier function qui est indispensable pour la connexion à la BDD
    */    
    $titre = "Création du compte";
    include '../header.php';

    $user = "";
    
    // On vérifie que la méthode utiliser et bien POST
    if($_SERVER["REQUEST_METHOD"] == "POST") {
    
        // On récupère les informations nécessaire 
        $valide = true;
        $user = strtolower(Secu($_POST['user']));
        $userMAJ = Secu($_POST['user']);
        $pswd = Secu($_POST['pswd']);
        $pswd_c = Secu($_POST['pswd_c']);
        $UserType = Secu($_POST['UserType']);
        $mail = Secu($_POST['mail']);

        // On vérifie en PHP si les variables ne sont pas vides
        if(empty($user)){
            echo 'Utilisateur vide';
            $valide = false;
        }
        if(empty($pswd)){
            echo 'Mot de passe vide';
            $valide = false;
        }
        if(empty($pswd_c)){
            echo 'Mot de passe confirmé vide';
            $valide = false;
        }

        // On check si les deux mdp correspondes 
        if($pswd_c != $pswd){
            echo 'Les mots de passe ne sont pas identitques';
            $valide = false;
        }
        // On lance une requetre préparer pour récupérer les informations lié à l'utilisateur afin de voir si il existe
            $data = $BDD->prepare('SELECT * FROM users WHERE user = :user');
            $data->bindParam(':user', $user);
            $data->execute();
        
            // On vérifie si l'utilisateur existe avec rowCount() on peut compter le nombre de case et si il y en a plus que 0 ca veut dire que le nom d'utilisateur à déjà été emprunté
            if( !($data->rowCount() > 0)) { 
                if($valide){
                    $options = [
                      'cost' => 12,
                    ];
                    $score_default = 0;
                    $ptsactu = 0;
                    // On crypte le mot de passe afin 
                    $pswdenc = password_hash(Secu($_POST['pswd']), PASSWORD_BCRYPT, $options);
                    // $sql = "INSERT INTO users (user, userMAJ, pswd, UserType ,  mail, ptsactu) VALUES('$user', '$userMAJ', '$pswdenc', $UserType , '$mail', 0)"; FIX ERREUR XSS
                    $add_user = $BDD->prepare("INSERT INTO users (user, userMAJ, pswd, UserType ,  mail, ptsactu) VALUES (:user, :userMAJ, :pswd, :UserType, :mail, :ptsactu )");
                    $add_user->bindParam(':user', $user);
                    $add_user->bindParam(':userMAJ', $userMAJ);
                    $add_user->bindParam(':pswd', $pswdenc);
                    $add_user->bindParam(':UserType', $UserType);
                    $add_user->bindParam(':mail', $mail);
                    $add_user->bindParam(':ptsactu', $ptsactu);
                    $add_user->execute();

                    $add_userc = $BDD->prepare("INSERT INTO JeuEnCours (user, scorefinal) VALUES (:user, :scorefinal )");
                    $add_userc->bindParam(':user', $user);
                    $add_userc->bindParam(':scorefinal', $score_default);
                    $add_userc->execute();
                    
                    
                    //$sql2 = "INSERT INTO JeuEnCours (user,scorefinal) VALUES('$user','$score_default')";
                 
                    echo 'Votre compte a été crée avec succès vous pouvez désormais vous connecter !';
                    header("Location: connexion.php"); 

                }
            }else{
                echo 'Ce nom d\'utilisateur est déjà pris';
            }
        }

?>
    <form class = "formlarge" action="creation.php" method="POST">
      <h1>Inscription</h1>
      <p class="minimarge">
        <label for="user">Nom d'utilisateur</label>
      </p>
      <input type="text" name="user" required="required" pattern="[A-Za-z0-9]{1,20}" value="<?php echo $user; ?>">
      <p>
        <label for="pswd">Adresse e-mail</label>
      </p>
      <input name="mail" type="email" required="required">
      <p>
        <label for="mail">Mot de Passe</label>
      </p>
      <input name="pswd" type="password" required="required">
      <p>
        <label for="pswd_c">Confirmation Mot de Passe</label>
      </p>
      <input name="pswd_c" type="password" required="required">
     

      <p class="minimarge">
        <label>Grade souhaité :</label>
      </p>
      <p>
        <input type="radio" name="UserType" value="1" required> Utilisateur
      </p>
      <p>
        <input type="radio" name="UserType" value="2"> Rédacteur (Tout abus sera sanctionné !)
      </p>
      
      <p class="minimarge">
        <button type="submit" class="boutgray" value="Envoyer">Envoyer</button>
        <button type="reset" class="boutgray" value="reset">Réintialiser</button>
      </p>
      <p class="minimarge">Déjà membre? <a href="connexion.php">Connecte toi</a></p>
    </form>

    </body> 
</html>