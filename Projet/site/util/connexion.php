<?
      /*
        La page de connexion du compte 
        Le titre de la page
        Ainsi que l'attribution du header pour la navbar et le fichier function qui est indispensable pour la connexion à la BDD
    */    
    $titre = "Page de connexion";
    include '../header.php'; 
    
    // On vérifie si il est bien connecté et dès qu'il est connecté on lui envoie sur la page de son profil pour pas qu'il puisse changer de compte
    if( isset( $_SESSION['user']) ){
         
          Redirect("../jeu/jouer.php");
    }


    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $valide = true;
        $user = strtolower(Secu($_POST['user']));
        $paswd = Secu($_POST['paswd']);

        if(empty($user)){
            die('Vous devez insérer un nom d\'utilisateur.');
            $valide = false;
        }
        if(empty($paswd)){
            die('Vous devez insérer un mot de passe.');
            $valide = false;
        }

        if($valide){ 
            // On vérifie si l'user existe
            $data_user = $BDD->prepare('SELECT * FROM users WHERE user = :user');
            $data_user->bindParam(':user', $user);
            $data_user->execute();
            // On stocke les données de l'utilisateur comme un objet avec PDO::FETCH_ASSOC
            $result_user = $data_user->fetch(PDO::FETCH_ASSOC);

            if ( $data_user->rowCount()>0) {
                // Mainteant on vérifie la combinaison mdp / user
                $hashpsswd = $result_user['pswd'];
                if( !(password_verify ($paswd , $hashpsswd))) {
                    echo 'Mot de passe incorrect';
                }else{
                    // On a vérifié les INPUT | USER | MDP Maintenant login
                    // On récupére les données dans la db directement au lieu de récuperer le $pswd et $user qui sont peut-être infectées
                    $sth = $BDD->prepare('SELECT * FROM users WHERE user = :user' );
                    $sth->bindValue(':user', $user);
                    $sth->execute();
                    $result_usertype = $sth->fetch(PDO::FETCH_ASSOC);

                    header('Cache-Control: no cache');
                    session_cache_limiter('private_no_expire'); // pour éviter les erreurs lors de précédent
                    $_SESSION['user'] = $result_usertype['user'];
                    $_SESSION['pswd'] = $result_usertype['pswd'];
                    $_SESSION['UserType'] = $result_usertype['UserType'];
                    
                    echo 'Connecté';
                     Redirect("../jeu/jouer.php");
                }
            }else{
                echo 'Ce nom d\'utilisateur n\'existe pas';
            }
        }
    }
?>

        <form class ="form" action="connexion.php" method="POST">
           <p class="litmarge">
           </p>
            <h1>Connexion</h1>
           <p class="litmarge">
           	<label for="user">Nom d'utilisateur</label>
            <!-- <input id="user"> -->
           </p>
            <input name="user" type="text" required="required" pattern="[A-Za-z0-9]{1,20}">
            <p><label for="pswd">Mot de Passe</label></p>
            <!-- <input id="pswd"> -->
             <input name="paswd" type="password" required="required">
             <p class="litmarge">
             	<button type="submit" class="boutgray" value="Envoyer">Se connecter</button>
             </p>
             <p class="minimarge"> <a href="creation.php">Pas encore de compte ?<br></a></p>
        </form>

    </body> 
</html>