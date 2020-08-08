<?
    $titre = "Changer son mot de passe";
    include '../header.php'; 

   if(!is_login()){
         Redirect('../util/connexion.php');
    }

    // echo print_r($_SESSION['UserType']);



    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $valide = true;
        $pswd = Secu($_POST['pswd']);
        $pswd_changer = Secu($_POST['pswd_changer']);
        $pswd_changer_c = Secu($_POST['pswd_changer_c']);
        $user = Secu($_SESSION['user']);

        // On check si les deux mdp correspondes 
        if($pswd_changer != $pswd_changer_c){
            echo 'Le mot de passe ne peut être changé car les 2 nouveaux doivent être identiques';
            $valide = false;
        }

        // On check si le nouveau mdp correspond à l'ancien
        if($pswd == $pswd_changer && $pswd_changer_c == $pswd){
            echo "Votre nouveau mot de passe est identique à l'ancien !";
            $valide = false;
        }

        if($valide){
            // On prépare une requête pour récupéer les informations l'utilisateur
            $data = $BDD->prepare('SELECT * FROM users WHERE user = :user');
            $data->bindParam(':user', $user, PDO::PARAM_STR );
            $data->execute();
            // On le stock comme un objet afin de pouvoir récupérer les informations nécessaire
            $row = $data->fetch(PDO::FETCH_ASSOC);
            $hashpsswd = $row['pswd'];
            if( !(password_verify ($pswd , $hashpsswd))) {
                echo("Votre ancien mot de passe n'est pas le bon");
            }else{
                $options = [
                      'cost' => 12,
                    ];
                    // On crypt le mot de passe 
                    $pswdenc = password_hash(Secu($_POST['pswd_changer_c']), PASSWORD_BCRYPT, $options);
                //$sql = "UPDATE users SET pswd='$pswdenc' WHERE USER='$user'";
                //$BDD->exec($sql);
                $update_pswd = $BDD->prepare('UPDATE users SET pswd = :pswd WHERE USER = :USER');
                $update_pswd->bindParam(':USER', $user, PDO::PARAM_STR );
                $update_pswd->bindParam(':pswd', $pswdenc, PDO::PARAM_STR );
                $update_pswd->execute();
                Redirect('profile.php');
                echo 'Votre mot de passe a été modifié avec succès';
            } 
        }else{
            // die("Vous devez remplir les champs nécessaires");
        }
    }
?>
    <form action="pswd_user.php" method="POST" class="form">
        <?php  if (isset($_POST['submit'])) : ?>
        <?php endif ?>
            <p class="minimarge">
                <h2>Changez votre mot de passe</h2>
            </p>
            <p class="minimarge">
                <label for="pswd">Ancien mot de passe</label>
            </p>
            <input name="pswd" type="password" required="required">

            <p>
                <label for="pswd_changer">Nouveau mot de passe</label>
            </p>
            <input name="pswd_changer" type="password" required="required">

            <p>
                <label for="pswd_changer_c">Confirmer nouveau mot de passe</label>
            </p>
            <input name="pswd_changer_c" type="password" required="required">
            
      <p>
        <input type="submit" class="boutgray" value="Envoyer">
        <button type="reset" value="reset" class="boutgray">Réintialiser</button>
    </p>
        
           
    </form>

    </body> 
</html>