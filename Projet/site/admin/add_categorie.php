<?
     $titre = "Ajouter une catégorie de carte";
     include '../header.php'; 
     $user = Secu($_SESSION['user']);

    if(!is_login()){
         Redirect('../util/connexion.php');
    }

    if(!is_admin()){
        Redirect('../index.php');
    }  

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $nom = Secu($_POST['nom']);
        $valide = true;

        if(empty($nom)){
            die('Vous devez insérer une Catégorie.');
            $valide = false;
        }
        
        // On se prépare a compter si le nom de la catégorie existe déjà pour éviter de dupliquer la catégorie
        $data = $BDD->prepare('SELECT * FROM categories WHERE nom = :nom');
        $data->bindParam(':nom', $nom);
        $data->execute();
        // On met à 1 car comme c'est un admin qui l'ajoute forcément elle est considéré comme valide
        $set_validite = 1;
       
       if( !($data->rowCount() > 0)) { 
        if($valide){
            //$sql = "INSERT INTO categories (nom,valide,auteur) VALUES('$nom','$set_validite','$user')";
            $add_cad = $BDD->prepare("INSERT INTO categories (nom,valide,auteur) VALUES (:nom, :valide, :auteur )");
            $add_cad->bindParam(':nom', $nom);
            $add_cad->bindParam(':valide', $set_validite);
            $add_cad->bindParam(':auteur', $user);
            $add_cad->execute();
            // $BDD->exec($sql); FIX ERREUR XSS par une préparation PDO au lieu d'un exec classique
            echo "La Catégorie a été ajoutée.";
        }else{
            echo "Veuillez insérer une Catégorie s'il vous plait";
        }
      }else{
          echo "La catégorie existe déjà";
      }
    }

    
    
?>

        <form action="add_categorie.php" method="POST">
          <table class="tabadmin" style="width: 20%; height: 75px;">
              <tbody>
                  <tr>
                      <td style="width: 100%;" class="tdredac">
                        Ajouter une nouvelle catégorie
                      </td>
                  </tr>
                  <tr>
                    <td style="width: 60%;" class="tdredacq">
                      <p class="minimarge">
                        <label for="nom">Nom de la catégorie à ajouter</label>
                      </p>
                      <p class="minimarge">
                        <input type="text" name="nom" required="required">
                      </p>
                      <p class="minimarge">
                        <input type="submit" class="boutgray" value="Envoyer">
                      </p>
                    </td>
                  </tr>
              </tbody>
          </table>
        </form>
    </body> 
</html>