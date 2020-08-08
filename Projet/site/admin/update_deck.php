<?php 
      /*
        La page pour modifier son deck
        Le titre de la page: Succès modification du DECK réussi
              --> Cette page est juste une redirection de la page manage deck que la seconde form  effectue la modification du deck qui redirige sur cette page ou les données sont traités
        Ainsi que l'attribution du header pour la navbar et le fichier function qui est indispensable pour la connexion à la BDD
    */ 
     $titre = "Succès modification du DECK réussi";
     include '../header.php';
     

    if(!is_login()){
         Redirect('../util/connexion.php');
     }

    if(!is_admin()){
        Redirect('../index.php');
    } 

         // On récupère sur la seconde form les questiosn remplis et on récupère le thème en cours ainsi que le jeu en cours
     $q1 = Secu($_POST['q1']);
     $q2 = Secu($_POST['q2']);
     $q3 = Secu($_POST['q3']);
     $q4 = Secu($_POST['q4']);
     $q5 = Secu($_POST['q5']);
     $q6 = Secu($_POST['q6']);
     $q7 = Secu($_POST['q7']);
     $q8 = Secu($_POST['q8']);
     $q9 = Secu($_POST['q9']);
     $q10 = Secu($_POST['q10']);
     $theme = Secu($_POST['theme']);
     $nomdecks = Secu($_SESSION['jeu']);
     $nom = Secu($_SESSION['user']);
     $difficulte = Secu($_POST['difficulte']);
        
     // On fait une uuptable du deck en fonction des questions rempli et on met que le deck est bien valide car il comprend bien 10 question et on vérifie que l'utilisateur de page est bien le créateur du deck et on vérifie que le nomdeck appartient bien à l'user
     $data = $BDD->prepare('UPDATE deck SET q1 = :q1, q2 = :q2, q3 = :q3, q4 = :q4, q5 = :q5, q6 = :q6, q7 = :q7, q8 = :q8, q9 = :q9, q10 = :q10, valide = 1, difficulte = :difficulte WHERE nomdeck = :nomdeck');
     $data->bindParam(':q1', $q1, PDO::PARAM_STR );
     $data->bindParam(':q2', $q2, PDO::PARAM_STR );
     $data->bindParam(':q3', $q3, PDO::PARAM_STR );
     $data->bindParam(':q4', $q4, PDO::PARAM_STR );
     $data->bindParam(':q5', $q5, PDO::PARAM_STR ); 
     $data->bindParam(':q6', $q6, PDO::PARAM_STR );
     $data->bindParam(':q7', $q7, PDO::PARAM_STR );
     $data->bindParam(':q8', $q8, PDO::PARAM_STR );
     $data->bindParam(':q9', $q9, PDO::PARAM_STR );
     $data->bindParam(':q10', $q10, PDO::PARAM_STR );
     $data->bindParam(':nomdeck', $nomdecks, PDO::PARAM_STR );
     $data->bindParam(':difficulte', $difficulte, PDO::PARAM_STR );
     $data->execute();   
    
    echo "Deck mis à jour!";
    // On enlève le jeu de la session sélectionné
    $_SESSION['jeu'] = "";
                                
?>