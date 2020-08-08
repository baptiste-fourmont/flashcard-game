<?
   $titre = "Changer mes infos";
   include '../header.php'; 
   
     if(!is_login()){
        Redirect('../util/connexion.php');
    }
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $pays = Secu($_POST['pays']);
        $Prenom = Secu($_POST['Prenom']  );
        $Nom = Secu($_POST['Nom']);
        $Telephone = Secu($_POST['Telephone']);
        $adresse = Secu($_POST['adresse']);
        $user = Secu($_SESSION['user']);
        // Valide permet de vérifier si les valeurs ne sont pas nulles

        $valide = true;
        if(empty($pays)){
            $valide = false;
        }
        if(empty($Prenom)){
            $valide = false;
        }
        if(empty($Nom)){
            $valide = false;
        }
        if(empty($Telephone)){
            $valide = false;
        }
        if(empty($adresse)){
            $valide = false;
        }

        if(!longueur($Telephone,10)){
            $valide = false;
            echo "Aucune modification n'a été effectuée veuillez vérifier la longueur de votre numéro de téléphone";
        }

        if($valide){
            $data_user = $BDD->prepare('SELECT user FROM users WHERE user = :user');
            $data_user ->bindParam(':user', $user, PDO::PARAM_STR );
            $data_user->execute();
            $row = $data_user->fetch(PDO::FETCH_ASSOC);
            if( !($data_user->rowCount() > 0)) {
                die("L'utilisateur n'existe pas");
            }else{
            // On va quoter la valeur au cas ou il y a une erreur
            $sql = "UPDATE users SET pays = :pays, Prenom = :Prenom, Nom = :Nom, Telephone = :Telephone, adresse = :adresse WHERE user= :user";
            $data = $BDD->prepare($sql);
            $data->bindParam(':user', $user, PDO::PARAM_STR );
            $data->bindParam(':pays', $pays, PDO::PARAM_STR );
            $data->bindParam(':Prenom', $Prenom, PDO::PARAM_STR );
            $data->bindParam(':Nom', $Nom, PDO::PARAM_STR );
            $data->bindParam(':Telephone', $Telephone, PDO::PARAM_INT ); // On vérifie si tel a bien que des int;
            $data->bindParam(':adresse', $adresse, PDO::PARAM_STR );
            $data->execute();
            
            echo 'Vos informations ont été mis à jour';
           }
        }
        

        
    }
?>
        
      <form action="profile.php" method="POST" class="form">
      <p>
        <h1>Informations</h1>
      </p>
      <p class="minimarge">
        <label for="pays">Pays</label>
      </p>
        <input type="text" name="pays" required="required" pattern="[A-Za-z0-9]{1,20}"  >
      <p>
        <label for="Prenom">Prenom</label>
      </p>
      <input name="Prenom" type="text" required="required" pattern="[A-Za-z0-9]{1,20}">
      <p>
        <label for="Nom">Nom</label>
      </p>
      <input name="Nom" type="text" required="required" pattern="[A-Za-z0-9]{1,20}">
      <p>
        <label for="Telephone">Numéro de téléphone</label>
      </p>
      <input name="Telephone" type="number" required="required" pattern="[A-Za-z0-9]{1,20}">
      <p>
        <label for="adresse">Adresse</label>
      </p>
        <input name="adresse" type="text" required="required">
      <p class="minimarge">
        <input type="submit" class="boutgray" value="Envoyer">
        <button type="reset" value="reset" class="boutgray">Réintialiser</button>
      </p>
    </form>

        

    </body> 
</html>