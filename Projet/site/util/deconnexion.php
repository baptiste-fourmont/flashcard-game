<?php
   
   require '../header.php';
    $titre = "Deconnexion";
   
    if(!is_login()){
        Redirect('connexion.php');
    }

   if ( isset( $_SESSION['user'] ) ) {
        /*
            On utilise session_unset pour enlever la session 
            Et on d?truit la session pour qu'elle n'est existe plus
        */
      session_unset();
      session_destroy();
      Redirect("../index.php");
      echo "Vous avez ?t? deconnect?";
      exit();
   } else {
        Redirect("../index.php");
   }  
?>