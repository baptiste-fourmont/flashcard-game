<?php 
    /*
        La page pour Jouer au jeu de cartes
        Le titre de la page: Jeu
              --> Elle permet de jouer et donne l'intêret principal du site cad dire de jouer au jeu de cartes.
                  Vous devez répondre à 10 questions et avoir le plus de points possible afin d'améliorer ses connaissances.
        Ainsi que l'attribution du header pour la navbar et le fichier function qui est indispensable pour la connexion à la BDD

        Certaines des fonctions n'ont pas fonctionné / on voualait que absolument tout soit enregistré dans la BDD mais nous n'avons pas complétement réussi
        Lors que l'on supprime une question / une catégorie et qu'il y a un deck qui utilisé soit cette carte supprimé / thème supprimé 
            --> Directement on la set en "Par défault" ce qui signifie que ces éléments ont été supprimés
    */ 
    $titre = "Jouer";
    include '../header.php';
        
    $user = $_SESSION['user'];

    // on veux récupère les catégories valide pour pouvoir les afficher
    $GetCategorie = $BDD->prepare("SELECT * FROM categories WHERE valide = 1  ORDER BY `categories`.`nom` ASC");
    $GetCategorie->execute();
    $resultat_categorie = $GetCategorie->fetchAll();

    // On récupère les deck qui ont été validé soit par l'administrateur ou soit qui contient bien 10 questions
    $GetDeck = $BDD->prepare("SELECT * FROM deck WHERE valide = 1");
    $GetDeck->execute();
    $resultat_deck = $GetDeck->fetchAll();

    // On trie les points de tous les utilisateurs  pas ordre décroissant c'est à dire de plus de point à 0 point
    $ClassPlayer = $BDD->prepare("SELECT * FROM `users` ORDER BY cast(`point` as unsigned) DESC ");
    $ClassPlayer->execute();
    $resultat_classplayer = $ClassPlayer->fetchAll();

    // On vérifie que l'utilisateur est bien connecté à la page sinon on le redirige
    if(!is_login()){
      // Voir function.php pour avoir plus de détail sur cette fonction
      Redirect('../util/connexion.php');
    }
    
    // On vérifie que la méthode utilisé est post
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
    	if(isset($_POST['signalementj'])){
    		$jeuenc = $BDD->prepare('SELECT * FROM users WHERE user = :user');
            $jeuenc->bindParam(':user', $user, PDO::PARAM_STR );
            $jeuenc->execute();
            $row = $jeuenc->fetch(PDO::FETCH_ASSOC);
            $game = htmlspecialchars_decode($row['jeu']);
            $signalvalue = Secu($_POST['signalementj']);
            
            // FIX avec XSS PDO INJECTION ERREUR avec '
    		// $sql = "INSERT INTO reports (auteur,ndd,probleme) VALUES('$user','$game','$signalvalue')";
            $insert_reports = $BDD->prepare("INSERT INTO reports (auteur,ndd,probleme) VALUES (:auteur, :ndd, :probleme )");
            $insert_reports->bindParam(':auteur', $user);
            $insert_reports->bindParam(':ndd', $game);
            $insert_reports->bindParam(':probleme', $signalvalue);
            $insert_reports->execute();
            echo "Signalement envoyé merci de votre aide";
    	}

	    if(isset($_POST['signal'])){ // Ici le joueur va créer un signalement qui sera visible via le panel admin

	        	?>
	        	    <table class="tabadmin" style="width: 643px; height: 75px;">
	                    <tbody>
	                        <tr>
	                            <td style="width: 161px;" class="tdadmin">
	                                Quel problème avez-vous rencontré avec ce quiz ? (Attention tout abus sera sanctionnable !) 
	                            </td>
	                            <td style="width: 100px;" class="tdadmin">
	                                <form action="jouer.php" method="POST"> 
	                                    <input type="textarea" name="signalementj" required="required">
	                                    <button type="submit" class="butredac">Envoyer le signalement</button>
	                                </form>
	                                <form action="jouer.php" method="POST"> 
	                                    <button name="no" type="submit" class="butredac" >Annuler et retourner à la liste des jeux</button>
	                                </form>
	                            </td>
	                        </tr>
	                        <tr>
	                        </tr>
	                    </tbody>
	                </table>
	                <?
	                die();
        	echo "coucou";
        	die();
        }

    	if(isset($_POST['tabscore'])){
            
            $jeuenc = $BDD->prepare('SELECT * FROM users WHERE user = :user');
            $jeuenc->bindParam(':user', $user, PDO::PARAM_STR );
            $jeuenc->execute();
            $row = $jeuenc->fetch(PDO::FETCH_ASSOC);
            $game = htmlspecialchars_decode($row['jeu']);

            // On récupère les informations du deck en cours 
			$GetDeckEncours = $BDD->prepare("SELECT * FROM deck WHERE nomdeck = :nomdeck");
			$GetDeckEncours->bindValue(':nomdeck',$game);
			$GetDeckEncours->execute();
			$resultat_deck = $GetDeckEncours->fetch(PDO::FETCH_ASSOC);

           // On récupère les information du joueur
			$getscore = $BDD->prepare("SELECT * FROM users WHERE user = :user");
            $getscore->bindValue(':user',$user);
			$getscore->execute();
			$col = $getscore->fetch(PDO::FETCH_ASSOC);
			$scorefinal = $col['ptsactu'];
        
            // On ajoute le score final dans la BDD lors qu'il est strictement supérieur à 0, il est inutile d'ajouter un score à 0 puis ce qu'il est NULL
			if($scorefinal>0){
				$scorefi = "UPDATE JeuEnCours SET scorefinal =$scorefinal WHERE USER='$user'";
           		$BDD->exec($scorefi);
                // Lors que la game est fini on ajoute +1 au jeu qui est en cours dans le deck pour dire que ce joué soit classé dans les favoris
           		$setplaycount = "UPDATE deck SET playcount = playcount+1  WHERE nomdeck='$game'";
           		$BDD->exec($setplaycount);
			}

            // Lors que le jeu est fini on passe la variable dans users directement comme quoi le jeu est fini dans toutes la session
			$segamefinie = "UPDATE JeuEnCours SET fini =1 WHERE USER='$user'";
			$BDD->exec($segamefinie);
        
             // On récupère les informations du jeu en cours 
		    $getstat = $BDD->prepare("SELECT * FROM JeuEnCours WHERE user = :user");
		    $getstat->bindValue(':user',$user);
		    $getstat->execute();
		    $colb = $getstat->fetch(PDO::FETCH_ASSOC);
           
            // On stock le score final
			$scoreaffiche = $colb['scorefinal'];

			
            /*
                On se situe à la fin du quizz ou on affiche les résultats
                --> On affiche des résultats différents selon le résultat obtenu 
                    Avec les informations du deck a été jouée: ( score / catégorie / thème en cours ) 
                    
                    La meilleur note est >7
                    Le second supérieur à 4 est strictement inférieur a 7, on affiche Bien joué
                    En dessous de 4: on affiche Attention pour que le joueur prenne conscience de bien recommencé le quizz pour qu'il prenne conscience qu'il doit s'améliorer
            */ 
			if($scorefinal > 7){
				 ?>
				    <div class="cartebon">
			  	   	<p class="nquestion">Fin du Quiz</p>
			  	   	<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
			  	   	<p class="themeq"><? echo $game ?></p>
			  	  	<h1 class="litmarge"> Félicitations, Votre score est de :</h1>
			  	  	<h1> <? echo $scoreaffiche; ?>/10 </h1>
			  	  	<form action='jouer.php' method='POST'>
			  	  	<p class="litmarge"><button type='submit' nom='envoyer' class='button_good' input name="mesres">Retourner au menu des jeux</button></p>
			  	  	<form action='jouer.php' method='POST'>
				  	  <p class="litmarge"><button class = "button_good"name="signal" type="submit" value="$game">Un problème avec le quiz ? Le signaler</button></p>
				  	</form>
					</div>
			  	<?
			  		$ptstot = "UPDATE users SET point= point+$scorefinal WHERE USER='$user'";
            		$BDD->exec($ptstot);
            		$pts = "UPDATE users SET ptsactu =0 WHERE USER='$user'";
           			$BDD->exec($pts);
			  		die();
			}
			elseif ($scorefinal < 4) {
			   ?>
				    <div class="cartebad">
			  	   	<p class="nquestion">Fin du Quiz</p>
			  	   	<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
			  	   	<p class="themeq"><? echo $game ?></p>
			  	  	<h1 class="litmarge"> Attention, votre score est de :</h1>
			  	  	<h1> <? echo $scoreaffiche; ?>/10 </h1>
			  	  	<form action='jouer.php' method='POST'>
			  	  	<p class="litmarge"><button type='submit' nom='envoyer' class='button_bad' input name="mesres">Retourner au menu des jeux</button></p>
			  	  	<p class="litmarge"><button class = "button_bad"name="signal" type="submit" value="$game">Un problème avec le quiz ? Le signaler</button></p>
				  	</div>


			  <?
			  $ptstot = "UPDATE users SET point= point+$scorefinal WHERE USER='$user'";
              $BDD->exec($ptstot);
              $pts = "UPDATE users SET ptsactu =0 WHERE USER='$user'";
           	  $BDD->exec($pts);
			  die();
			}
			else{
				  	?>
	
					    <div class="cartefin">
				  	   	<p class="nquestion">Fin du Quiz</p>
				  	   	<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
				  	   	<p class="themeq"><? echo $game ?></p>
				  	  	<h1 class="litmarge"> Bien joué, votre score est de :</h1>
				  	  	<h1> <? echo $scoreaffiche; ?>/10 </h1>
				  	  	<form action='jouer.php' method='POST'>
				  	  	<p class="litmarge"><button type='submit' nom='envoyer' class='boutfin' input name="mesres">Retourner au menu des jeux</button></p>
				  	  	<p class="litmarge"><button class = "boutfin"name="signal" type="submit" value="$game">Un problème avec le quiz ? Le signaler</button></p>
					  	</div>

			  		<?
			  			$ptstot = "UPDATE users SET point= point+$scorefinal WHERE USER='$user'";
            			$BDD->exec($ptstot);
            			$pts = "UPDATE users SET ptsactu =0 WHERE USER='$user'";
           				$BDD->exec($pts);
			  			die();
			}

    	}
    	if(isset($_POST['q10v'])){ //Je sais que le code n'est pas propre et que cela aurait pu être fait via une fonction pour la vérif et l'affichage des questions mais j'obtenais de nombreuses erreurs inconnues via la fonction donc j'ai abandonné l'idée d'une fonction
    		$valq = "10";
    		$repn = "r10";
            
            $jeuenc = $BDD->prepare('SELECT * FROM users WHERE user = :user');
            $jeuenc->bindParam(':user', $user, PDO::PARAM_STR );
            $jeuenc->execute();
            $row = $jeuenc->fetch(PDO::FETCH_ASSOC); // On récupère les infos de l'user pour récuperer le jeu auquel celui ci joue 
            $game = htmlspecialchars_decode($row['jeu']); // htmlspecialchars_decode est utilisé pour éviter tout erreur d'affichage en lisant la bdd mais le pronblème a été réglé donc il n'est pas utile ici


            $q = Secu($_POST['q10v']);
            $r = $BDD->prepare("UPDATE JeuEnCours SET r10 = :r10 WHERE user = :user");
            $r->bindParam(':user', $user, PDO::PARAM_STR );
            $r->bindParam(':r10', $q, PDO::PARAM_STR );
            $r->execute();  // On définit ici la case r10 de l'user dans jeuencours afin de pouvoir stocker la réponse initialement pour pouvoir revoir son résultat plus tard mais l'idée fut abandonnée

            $GetDeckEncours = $BDD->prepare("SELECT * FROM deck WHERE nomdeck = :nomdeck");
            $GetDeckEncours->bindValue(':nomdeck',$game);
            $GetDeckEncours->execute();
            $resultat_deck = $GetDeckEncours->fetch(PDO::FETCH_ASSOC);
            $cache_question = $resultat_deck['q10']; // On récupère la question du deck original afin de pouvoir vérifier plus tard.

            $data_q = $BDD->prepare('SELECT * FROM cartes WHERE question = :question AND reponse = :reponse');
         	$data_q->bindParam(':question', $cache_question); // On cherche donc à voir si la réponse entrée par l'user correspond à la réponse que la question est censée avoir dans la bdd
         	$data_q->bindParam(':reponse', $q);
         	$data_q->execute();


            $data_card = $BDD->prepare('SELECT * FROM cartes WHERE question = :question');
            $data_card->bindParam(':question', $cache_question);
            $data_card->execute();
            $result_card = $data_card->fetch(PDO::FETCH_ASSOC);
            $reponsevraie = $result_card['reponse']; // On récupère ici la vraie réponse au cas ou l'utilisateur a faux

         	if ( $data_q->rowCount()>0){ //SI plus d'une colonne existe c'est que la réponse de l'user est bien la bonne ont peut donc ajouter 1pt
         		$getrepnext = $BDD->prepare("SELECT * FROM JeuEnCours WHERE user = :user");
            	$getrepnext->bindValue(':user',$user);
				$getrepnext->execute();
				$repn = $getrepnext->fetch(PDO::FETCH_ASSOC);
				$prochainerep = $repn['fini']; // ON fait cela afin de pouvoir vérifier la prochaine réponse afin d'éviter la dupplication de points en rafraichissant la page car quand on ajoute un point, on met "pt" dans la case de la rep suivante
				
         		if($prochainerep==0){ // Si la condition n'es pas respectée c'est que l'utilisateur a essayé de rafraichir la page pour réessayer d"executer la fonction addpts
	         		 $r10 = $BDD->prepare("UPDATE JeuEnCours SET r10 = :r10 WHERE user = :user");
                    $r10->bindParam(':user', $user, PDO::PARAM_STR );
                    $r10->bindParam(':r10', $q, PDO::PARAM_STR );
                    $r10->execute();
	         		$pts = "UPDATE users SET ptsactu =ptsactu+1 WHERE USER='$user'";
	            	$BDD->exec($pts);
	            	$antidupli = "UPDATE JeuEnCours SET fini = 1 WHERE USER='$user'";
            		$BDD->exec($antidupli); // On set fini à 1 afin d'éviter l'ajout de points infini
            }

         	  ?>
                <!--
                    Ces deux div="cartebon" et "cartebad" vont nous permettre d'afficher la réponse et des couleurs différentes en fonction de si la réponse est juste ou si la réponse est mauvaise
                    On affichera la div "cartebad" si la réponse est mauvaise avec un fond rouge 
                                        "cartebon" si la réponse est juste

                    On récupère le thème en cours avec $resultat_deck['categorie'] qui contient des informations du deck 
                    $valq correspond au numéro de la question 
                    $game correspond au nom du jeu en cours
                    $q affiche la réponse qu'il doit y a voir 
                -->
	    		<div class="cartebon">
	   			<p class="nquestion">Question N°<? echo "$valq";?></p>
	   			<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
	   			<p class="themeq"><? echo $game ?></p>
	  			<h1 class="litmarge">Bonne réponse, la réponse était bien :</h1>
	  			<h1>
	  				 <?php echo $q; ?>
	  			</h1> 
	  			<form action='jouer.php' method='POST'>
	  			<p class="litmarge"><button type='submit' nom='envoyer' class='button_good' input name="tabscore">Voir mes résultats</button></p>
	  			</div>
	  		<?php
	  			die();
         		}else{
            	?>
			    		<div class="cartebad">
		   			<p class="nquestion">Question N°<? echo "$valq";?></p>
		   			<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
		   			<p class="themeq"><? echo $game ?></p>
		  			<h1 class="litmarge">Mauvaise réponse, la réponse était :</h1>
		  			<h1>
		  				 <?php echo $reponsevraie; ?>
		  			</h1> 
		  			<form action='jouer.php' method='POST'>
		  			<p class="litmarge"><button type='submit' nom='envoyer' class='button_bad' input name="tabscore">Voir mes résultats</button></p>
		  			</div> 
	  			<?php
	  				die();
         	}

    	}

        /*
           On ne va pas commenter 10x la même situation
           Je vais vous décrire le procédé d'une facon simple  voir en haut pour la vérification de réponse une fois envoyée
        */
    	if(isset($_POST['q10'])){  
			  $nbq = '10'; 
			  $pregu = 'q10';
			  $formu = "q10";        
			  $jeuenc = $BDD->prepare('SELECT * FROM users WHERE user = :user');
			  $jeuenc->bindParam(':user', $user, PDO::PARAM_STR );
			  $jeuenc->execute();
			  $row = $jeuenc->fetch(PDO::FETCH_ASSOC);
			  $game = htmlspecialchars_decode($row['jeu']);


			        

			  $GetDeckEncours = $BDD->prepare("SELECT * FROM deck WHERE nomdeck = :nomdeck");
			  $GetDeckEncours->bindValue(':nomdeck',$game);
			  $GetDeckEncours->execute();
			  $resultat_deck = $GetDeckEncours->fetch(PDO::FETCH_ASSOC);
			  $cache_question = $resultat_deck[$pregu];

			  $data_q = $BDD->prepare('SELECT * FROM cartes WHERE question = :question AND reponse = :reponse');
			  $data_q->bindParam(':question', $cache_question);
			  $data_q->bindParam(':reponse', $pregu);
			  $data_q->execute();
			  ?>
				    <div class="carte">
			  	   	<p class="nquestion">Question N°<? echo "$nbq"?></p>
			  	   	<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
			  	   	<p class="themeq"><? echo $game ?></p>
			  	  	<h1 class="litmarge"><? echo $resultat_deck[$pregu]; ?></h1>
			  	  	<form action='jouer.php' method='POST'>
			  	  	<input name="q10v" type="text">
			  	  	<p class="litmarge"><button type='submit' nom='envoyer' class='button_site'>Envoyer</button></p>
				  	</div>
			  <?
			  die;
		}
    	if(isset($_POST['q9v'])){
    		$valq = "9";
    		$repn = "r9";

            $jeuenc = $BDD->prepare('SELECT * FROM users WHERE user = :user');
            $jeuenc->bindParam(':user', $user, PDO::PARAM_STR );
            $jeuenc->execute();
            $row = $jeuenc->fetch(PDO::FETCH_ASSOC);
            $game = htmlspecialchars_decode($row['jeu']);


            $q = Secu($_POST['q9v']);
            $r = $BDD->prepare("UPDATE JeuEnCours SET r9 = :r9 WHERE user = :user");
            $r->bindParam(':user', $user, PDO::PARAM_STR );
            $r->bindParam(':r9', $q, PDO::PARAM_STR );
            $r->execute();

            $GetDeckEncours = $BDD->prepare("SELECT * FROM deck WHERE nomdeck = :nomdeck");
            $GetDeckEncours->bindValue(':nomdeck',$game);
            $GetDeckEncours->execute();
            $resultat_deck = $GetDeckEncours->fetch(PDO::FETCH_ASSOC);
            $cache_question = $resultat_deck['q9'];

            $data_q = $BDD->prepare('SELECT * FROM cartes WHERE question = :question AND reponse = :reponse');
         	$data_q->bindParam(':question', $cache_question);
         	$data_q->bindParam(':reponse', $q);
         	$data_q->execute();


            $data_card = $BDD->prepare('SELECT * FROM cartes WHERE question = :question');
            $data_card->bindParam(':question', $cache_question);
            $data_card->execute();
            $result_card = $data_card->fetch(PDO::FETCH_ASSOC);
            $reponsevraie = $result_card['reponse'];

         	if ( $data_q->rowCount()>0){
         		$getrepnext = $BDD->prepare("SELECT * FROM JeuEnCours WHERE user = :user");
            	$getrepnext->bindValue(':user',$user);
				$getrepnext->execute();
				$repn = $getrepnext->fetch(PDO::FETCH_ASSOC);
				$prochainerep = $repn['r10']; // ON fait cela afin de pouvoir vérifier la prochaine réponse afin d'éviter la dupplication de points en rafraichissant la page car quand on ajoute un point, on met "pt" dans la case de la rep suivante
				
         		if($prochainerep==NULL){ // Si la condition n'es pas respectée c'est que l'utilisateur a essayé de rafraichir la page pour réessayer d"executer la fonction addpts
	         		 $r9 = $BDD->prepare("UPDATE JeuEnCours SET r9 = :r9 WHERE user = :user");
                    $r9->bindParam(':user', $user, PDO::PARAM_STR );
                    $r9->bindParam(':r9', $q, PDO::PARAM_STR );
                    $r9->execute();
	         		$pts = "UPDATE users SET ptsactu =ptsactu+1 WHERE USER='$user'";
	            	$BDD->exec($pts);
	            	$antidupli = "UPDATE JeuEnCours SET r10 = 0 WHERE USER='$user'";
            		$BDD->exec($antidupli);
            }

         	  ?>
	    		<div class="cartebon">
	   			<p class="nquestion">Question N°<? echo "$valq";?></p>
	   			<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
	   			<p class="themeq"><? echo $game ?></p>
	  			<h1 class="litmarge">Bonne réponse, la réponse était bien :</h1>
	  			<h1>
	  				 <?php echo $q; ?>
	  			</h1> 
	  			<form action='jouer.php' method='POST'>
	  			<p class="litmarge"><button type='submit' nom='envoyer' class='button_good' input name="q10">Question suivante</button></p>
	  			</div>
	  		<?php
	  			die();
         		}else{
            	?>
			    		<div class="cartebad">
		   			<p class="nquestion">Question N°<? echo "$valq";?></p>
		   			<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
		   			<p class="themeq"><? echo $game ?></p>
		  			<h1 class="litmarge">Mauvaise réponse, la réponse était :</h1>
		  			<h1>
		  				 <?php echo $reponsevraie; ?>
		  			</h1> 
		  			<form action='jouer.php' method='POST'>
		  			<p class="litmarge"><button type='submit' nom='envoyer' class='button_bad' input name="q10">Question suivante</button></p>
		  			</div> 
	  			<?php
	  				die();
         	}

    	}
    	if(isset($_POST['q9'])){  
			  $nbq = '9'; 
			  $pregu = 'q9';
			  $formu = "q9";        
			  $jeuenc = $BDD->prepare('SELECT * FROM users WHERE user = :user');
			  $jeuenc->bindParam(':user', $user, PDO::PARAM_STR );
			  $jeuenc->execute();
			  $row = $jeuenc->fetch(PDO::FETCH_ASSOC);
			  $game = htmlspecialchars_decode($row['jeu']);


			        

			  $GetDeckEncours = $BDD->prepare("SELECT * FROM deck WHERE nomdeck = :nomdeck");
			  $GetDeckEncours->bindValue(':nomdeck',$game);
			  $GetDeckEncours->execute();
			  $resultat_deck = $GetDeckEncours->fetch(PDO::FETCH_ASSOC);
			  $cache_question = $resultat_deck[$pregu];

			  $data_q = $BDD->prepare('SELECT * FROM cartes WHERE question = :question AND reponse = :reponse');
			  $data_q->bindParam(':question', $cache_question);
			  $data_q->bindParam(':reponse', $pregu);
			  $data_q->execute();
			  ?>
				    <div class="carte">
			  	   	<p class="nquestion">Question N°<? echo "$nbq"?></p>
			  	   	<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
			  	   	<p class="themeq"><? echo $game ?></p>
			  	  	<h1 class="litmarge"><? echo $resultat_deck[$pregu]; ?></h1>
			  	  	<form action='jouer.php' method='POST'>
			  	  	<input name="q9v" type="text">
			  	  	<p class="litmarge"><button type='submit' nom='envoyer' class='button_site'>Envoyer</button></p>
				  	</div>
			  <?
			  die;
		}
    	if(isset($_POST['q8v'])){
    		$valq = "8";
    		$repn = "r8";

            $jeuenc = $BDD->prepare('SELECT * FROM users WHERE user = :user');
            $jeuenc->bindParam(':user', $user, PDO::PARAM_STR );
            $jeuenc->execute();
            $row = $jeuenc->fetch(PDO::FETCH_ASSOC);
            $game = htmlspecialchars_decode($row['jeu']);


            $q = Secu($_POST['q8v']);
            $r = $BDD->prepare("UPDATE JeuEnCours SET r8 = :r8 WHERE user = :user");
            $r->bindParam(':user', $user, PDO::PARAM_STR );
            $r->bindParam(':r8', $q, PDO::PARAM_STR );
            $r->execute();

            $GetDeckEncours = $BDD->prepare("SELECT * FROM deck WHERE nomdeck = :nomdeck");
            $GetDeckEncours->bindValue(':nomdeck',$game);
            $GetDeckEncours->execute();
            $resultat_deck = $GetDeckEncours->fetch(PDO::FETCH_ASSOC);
            $cache_question = $resultat_deck['q8'];

            $data_q = $BDD->prepare('SELECT * FROM cartes WHERE question = :question AND reponse = :reponse');
         	$data_q->bindParam(':question', $cache_question);
         	$data_q->bindParam(':reponse', $q);
         	$data_q->execute();


            $data_card = $BDD->prepare('SELECT * FROM cartes WHERE question = :question');
            $data_card->bindParam(':question', $cache_question);
            $data_card->execute();
            $result_card = $data_card->fetch(PDO::FETCH_ASSOC);
            $reponsevraie = $result_card['reponse'];

         	if ( $data_q->rowCount()>0){
         		$getrepnext = $BDD->prepare("SELECT * FROM JeuEnCours WHERE user = :user");
            	$getrepnext->bindValue(':user',$user);
				$getrepnext->execute();
				$repn = $getrepnext->fetch(PDO::FETCH_ASSOC);
				$prochainerep = $repn['r9']; // ON fait cela afin de pouvoir vérifier la prochaine réponse afin d'éviter la dupplication de points en rafraichissant la page car quand on ajoute un point, on met "pt" dans la case de la rep suivante
				
         		if($prochainerep==NULL){ // Si la condition n'es pas respectée c'est que l'utilisateur a essayé de rafraichir la page pour réessayer d"executer la fonction addpts
	         		 $r8 = $BDD->prepare("UPDATE JeuEnCours SET r8 = :r8 WHERE user = :user");
                    $r8->bindParam(':user', $user, PDO::PARAM_STR );
                    $r8->bindParam(':r8', $q, PDO::PARAM_STR );
                    $r8->execute();
	         		$pts = "UPDATE users SET ptsactu =ptsactu+1 WHERE USER='$user'";
	            	$BDD->exec($pts);
	            	$antidupli = "UPDATE JeuEnCours SET r9 = 0 WHERE USER='$user'";
            		$BDD->exec($antidupli);
            }

         	  ?>
	    		<div class="cartebon">
	   			<p class="nquestion">Question N°<? echo "$valq";?></p>
	   			<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
	   			<p class="themeq"><? echo $game ?></p>
	  			<h1 class="litmarge">Bonne réponse, la réponse était bien :</h1>
	  			<h1>
	  				 <?php echo $q; ?>
	  			</h1> 
	  			<form action='jouer.php' method='POST'>
	  			<p class="litmarge"><button type='submit' nom='envoyer' class='button_good' input name="q9">Question suivante</button></p>
	  			</div>
	  		<?php
	  			die();
         		}else{
            	?>
			    		<div class="cartebad">
		   			<p class="nquestion">Question N°<? echo "$valq";?></p>
		   			<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
		   			<p class="themeq"><? echo $game ?></p>
		  			<h1 class="litmarge">Mauvaise réponse, la réponse était :</h1>
		  			<h1>
		  				 <?php echo $reponsevraie; ?>
		  			</h1> 
		  			<form action='jouer.php' method='POST'>
		  			<p class="litmarge"><button type='submit' nom='envoyer' class='button_bad' input name="q9">Question suivante</button></p>
		  			</div> 
	  			<?php
	  				die();
         	}

    	}
    	if(isset($_POST['q8'])){  
			  $nbq = '8'; 
			  $pregu = 'q8';
			  $formu = "q8";        
			  $jeuenc = $BDD->prepare('SELECT * FROM users WHERE user = :user');
			  $jeuenc->bindParam(':user', $user, PDO::PARAM_STR );
			  $jeuenc->execute();
			  $row = $jeuenc->fetch(PDO::FETCH_ASSOC);
			  $game = htmlspecialchars_decode($row['jeu']);


			        

			  $GetDeckEncours = $BDD->prepare("SELECT * FROM deck WHERE nomdeck = :nomdeck");
			  $GetDeckEncours->bindValue(':nomdeck',$game);
			  $GetDeckEncours->execute();
			  $resultat_deck = $GetDeckEncours->fetch(PDO::FETCH_ASSOC);
			  $cache_question = $resultat_deck[$pregu];

			  $data_q = $BDD->prepare('SELECT * FROM cartes WHERE question = :question AND reponse = :reponse');
			  $data_q->bindParam(':question', $cache_question);
			  $data_q->bindParam(':reponse', $pregu);
			  $data_q->execute();
			  ?>
				    <div class="carte">
			  	   	<p class="nquestion">Question N°<? echo "$nbq"?></p>
			  	   	<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
			  	   	<p class="themeq"><? echo $game ?></p>
			  	  	<h1 class="litmarge"><? echo $resultat_deck[$pregu]; ?></h1>
			  	  	<form action='jouer.php' method='POST'>
			  	  	<input name="q8v" type="text">
			  	  	<p class="litmarge"><button type='submit' nom='envoyer' class='button_site'>Envoyer</button></p>
				  	</div>
			  <?
			  die;
		}
    	if(isset($_POST['q7v'])){
    		$valq = "7";
    		$repn = "r7";

            $jeuenc = $BDD->prepare('SELECT * FROM users WHERE user = :user');
            $jeuenc->bindParam(':user', $user, PDO::PARAM_STR );
            $jeuenc->execute();
            $row = $jeuenc->fetch(PDO::FETCH_ASSOC);
            $game = htmlspecialchars_decode($row['jeu']);


            $q = Secu($_POST['q7v']);
          
            
            
            $r = $BDD->prepare("UPDATE JeuEnCours SET r7 = :r7 WHERE user = :user");
            $r->bindParam(':user', $user, PDO::PARAM_STR );
            $r->bindParam(':r7', $q, PDO::PARAM_STR );
            $r->execute();

            $GetDeckEncours = $BDD->prepare("SELECT * FROM deck WHERE nomdeck = :nomdeck");
            $GetDeckEncours->bindValue(':nomdeck',$game);
            $GetDeckEncours->execute();
            $resultat_deck = $GetDeckEncours->fetch(PDO::FETCH_ASSOC);
            $cache_question = $resultat_deck['q7'];

            $data_q = $BDD->prepare('SELECT * FROM cartes WHERE question = :question AND reponse = :reponse');
         	$data_q->bindParam(':question', $cache_question);
         	$data_q->bindParam(':reponse', $q);
         	$data_q->execute();


            $data_card = $BDD->prepare('SELECT * FROM cartes WHERE question = :question');
            $data_card->bindParam(':question', $cache_question);
            $data_card->execute();
            $result_card = $data_card->fetch(PDO::FETCH_ASSOC);
            $reponsevraie = $result_card['reponse'];

         	if ( $data_q->rowCount()>0){
         		$getrepnext = $BDD->prepare("SELECT * FROM JeuEnCours WHERE user = :user");
            	$getrepnext->bindValue(':user',$user);
				$getrepnext->execute();
				$repn = $getrepnext->fetch(PDO::FETCH_ASSOC);
				$prochainerep = $repn['r8']; // ON fait cela afin de pouvoir vérifier la prochaine réponse afin d'éviter la dupplication de points en rafraichissant la page car quand on ajoute un point, on met "pt" dans la case de la rep suivante
				
         		if($prochainerep==NULL){ // Si la condition n'es pas respectée c'est que l'utilisateur a essayé de rafraichir la page pour réessayer d"executer la fonction addpts
	         	
                    $r7 = $BDD->prepare("UPDATE JeuEnCours SET r7 = :r7 WHERE user = :user");
                    $r7->bindParam(':user', $user, PDO::PARAM_STR );
                    $r7->bindParam(':r7', $q, PDO::PARAM_STR );
                    $r7->execute();
	         		$pts = "UPDATE users SET ptsactu =ptsactu+1 WHERE USER='$user'";
	            	$BDD->exec($pts);
	            	$antidupli = "UPDATE JeuEnCours SET r8 = 0 WHERE USER='$user'";
            		$BDD->exec($antidupli);
            }

         	  ?>
	    		<div class="cartebon">
	   			<p class="nquestion">Question N°<? echo "$valq";?></p>
	   			<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
	   			<p class="themeq"><? echo $game ?></p>
	  			<h1 class="litmarge">Bonne réponse, la réponse était bien :</h1>
	  			<h1>
	  				 <?php echo $q; ?>
	  			</h1> 
	  			<form action='jouer.php' method='POST'>
	  			<p class="litmarge"><button type='submit' nom='envoyer' class='button_good' input name="q8">Question suivante</button></p>
	  			</div>
	  		<?php
	  			die();
         		}else{
            	?>
			    		<div class="cartebad">
		   			<p class="nquestion">Question N°<? echo "$valq";?></p>
		   			<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
		   			<p class="themeq"><? echo $game ?></p>
		  			<h1 class="litmarge">Mauvaise réponse, la réponse était :</h1>
		  			<h1>
		  				 <?php echo $reponsevraie; ?>
		  			</h1> 
		  			<form action='jouer.php' method='POST'>
		  			<p class="litmarge"><button type='submit' nom='envoyer' class='button_bad' input name="q8">Question suivante</button></p>
		  			</div> 
	  			<?php
	  				die();
         	}

    	}
    	if(isset($_POST['q7'])){  
			  $nbq = '7'; 
			  $pregu = 'q7';
			  $formu = "q7";        
			  $jeuenc = $BDD->prepare('SELECT * FROM users WHERE user = :user');
			  $jeuenc->bindParam(':user', $user, PDO::PARAM_STR );
			  $jeuenc->execute();
			  $row = $jeuenc->fetch(PDO::FETCH_ASSOC);
			  $game = htmlspecialchars_decode($row['jeu']);


			        

			  $GetDeckEncours = $BDD->prepare("SELECT * FROM deck WHERE nomdeck = :nomdeck");
			  $GetDeckEncours->bindValue(':nomdeck',$game);
			  $GetDeckEncours->execute();
			  $resultat_deck = $GetDeckEncours->fetch(PDO::FETCH_ASSOC);
			  $cache_question = $resultat_deck[$pregu];

			  $data_q = $BDD->prepare('SELECT * FROM cartes WHERE question = :question AND reponse = :reponse');
			  $data_q->bindParam(':question', $cache_question);
			  $data_q->bindParam(':reponse', $pregu);
			  $data_q->execute();
			  ?>
				    <div class="carte">
			  	   	<p class="nquestion">Question N°<? echo "$nbq"?></p>
			  	   	<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
			  	   	<p class="themeq"><? echo $game ?></p>
			  	  	<h1 class="litmarge"><? echo $resultat_deck[$pregu]; ?></h1>
			  	  	<form action='jouer.php' method='POST'>
			  	  	<input name="q7v" type="text">
			  	  	<p class="litmarge"><button type='submit' nom='envoyer' class='button_site'>Envoyer</button></p>
				  	</div>
			  <?
			  die;
		}
    	if(isset($_POST['q6v'])){
    		$valq = "6";
    		$repn = "r6";

            $jeuenc = $BDD->prepare('SELECT * FROM users WHERE user = :user');
            $jeuenc->bindParam(':user', $user, PDO::PARAM_STR );
            $jeuenc->execute();
            $row = $jeuenc->fetch(PDO::FETCH_ASSOC);
            $game = htmlspecialchars_decode($row['jeu']);


            $q = Secu($_POST['q6v']);
       
            $r = $BDD->prepare("UPDATE JeuEnCours SET r6 = :r6 WHERE user = :user");
            $r->bindParam(':user', $user, PDO::PARAM_STR );
            $r->bindParam(':r6', $q, PDO::PARAM_STR );
            $r->execute();

            $GetDeckEncours = $BDD->prepare("SELECT * FROM deck WHERE nomdeck = :nomdeck");
            $GetDeckEncours->bindValue(':nomdeck',$game);
            $GetDeckEncours->execute();
            $resultat_deck = $GetDeckEncours->fetch(PDO::FETCH_ASSOC);
            $cache_question = $resultat_deck['q6'];

            $data_q = $BDD->prepare('SELECT * FROM cartes WHERE question = :question AND reponse = :reponse');
         	$data_q->bindParam(':question', $cache_question);
         	$data_q->bindParam(':reponse', $q);
         	$data_q->execute();


            $data_card = $BDD->prepare('SELECT * FROM cartes WHERE question = :question');
            $data_card->bindParam(':question', $cache_question);
            $data_card->execute();
            $result_card = $data_card->fetch(PDO::FETCH_ASSOC);
            $reponsevraie = $result_card['reponse'];

         	if ( $data_q->rowCount()>0){
         		$getrepnext = $BDD->prepare("SELECT * FROM JeuEnCours WHERE user = :user");
            	$getrepnext->bindValue(':user',$user);
				$getrepnext->execute();
				$repn = $getrepnext->fetch(PDO::FETCH_ASSOC);
				$prochainerep = $repn['r7']; // ON fait cela afin de pouvoir vérifier la prochaine réponse afin d'éviter la dupplication de points en rafraichissant la page car quand on ajoute un point, on met "pt" dans la case de la rep suivante
				
         		if($prochainerep==NULL){ // Si la condition n'es pas respectée c'est que l'utilisateur a essayé de rafraichir la page pour réessayer d"executer la fonction addpts
	         
                    $r6 = $BDD->prepare("UPDATE JeuEnCours SET r6 = :r6 WHERE user = :user");
                    $r6->bindParam(':user', $user, PDO::PARAM_STR );
                    $r6->bindParam(':r6', $q, PDO::PARAM_STR );
                    $r6->execute();
	         		$pts = "UPDATE users SET ptsactu =ptsactu+1 WHERE USER='$user'";
	            	$BDD->exec($pts);
	            	$antidupli = "UPDATE JeuEnCours SET r7 = 0 WHERE USER='$user'";
            		$BDD->exec($antidupli);
            }

         	  ?>
	    		<div class="cartebon">
	   			<p class="nquestion">Question N°<? echo "$valq";?></p>
	   			<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
	   			<p class="themeq"><? echo $game ?></p>
	  			<h1 class="litmarge">Bonne réponse, la réponse était bien :</h1>
	  			<h1>
	  				 <?php echo $q; ?>
	  			</h1> 
	  			<form action='jouer.php' method='POST'>
	  			<p class="litmarge"><button type='submit' nom='envoyer' class='button_good' input name="q7">Question suivante</button></p>
	  			</div>
	  		<?php
	  			die();
         		}else{
            	?>
			    		<div class="cartebad">
		   			<p class="nquestion">Question N°<? echo "$valq";?></p>
		   			<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
		   			<p class="themeq"><? echo $game ?></p>
		  			<h1 class="litmarge">Mauvaise réponse, la réponse était :</h1>
		  			<h1>
		  				 <?php echo $reponsevraie; ?>
		  			</h1> 
		  			<form action='jouer.php' method='POST'>
		  			<p class="litmarge"><button type='submit' nom='envoyer' class='button_bad' input name="q7">Question suivante</button></p>
		  			</div> 
	  			<?php
	  				die();
         	}

    	}
    	if(isset($_POST['q6'])){  
			  $nbq = '6'; 
			  $pregu = 'q6';
			  $formu = "q6";        
			  $jeuenc = $BDD->prepare('SELECT * FROM users WHERE user = :user');
			  $jeuenc->bindParam(':user', $user, PDO::PARAM_STR );
			  $jeuenc->execute();
			  $row = $jeuenc->fetch(PDO::FETCH_ASSOC);
			  $game = htmlspecialchars_decode($row['jeu']);


			        

			  $GetDeckEncours = $BDD->prepare("SELECT * FROM deck WHERE nomdeck = :nomdeck");
			  $GetDeckEncours->bindValue(':nomdeck',$game);
			  $GetDeckEncours->execute();
			  $resultat_deck = $GetDeckEncours->fetch(PDO::FETCH_ASSOC);
			  $cache_question = $resultat_deck[$pregu];

			  $data_q = $BDD->prepare('SELECT * FROM cartes WHERE question = :question AND reponse = :reponse');
			  $data_q->bindParam(':question', $cache_question);
			  $data_q->bindParam(':reponse', $pregu);
			  $data_q->execute();
			  ?>
				    <div class="carte">
			  	   	<p class="nquestion">Question N°<? echo "$nbq"?></p>
			  	   	<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
			  	   	<p class="themeq"><? echo $game ?></p>
			  	  	<h1 class="litmarge"><? echo $resultat_deck[$pregu]; ?></h1>
			  	  	<form action='jouer.php' method='POST'>
			  	  	<input name="q6v" type="text">
			  	  	<p class="litmarge"><button type='submit' nom='envoyer' class='button_site'>Envoyer</button></p>
				  	</div>
			  <?
			  die;
		}
    	if(isset($_POST['q5v'])){
    		$valq = "5";
    		$repn = "r5";

            $jeuenc = $BDD->prepare('SELECT * FROM users WHERE user = :user');
            $jeuenc->bindParam(':user', $user, PDO::PARAM_STR );
            $jeuenc->execute();
            $row = $jeuenc->fetch(PDO::FETCH_ASSOC);
            $game = htmlspecialchars_decode($row['jeu']);


            $q = Secu($_POST['q5v']);
           
            $r = $BDD->prepare("UPDATE JeuEnCours SET r5 = :r5 WHERE user = :user");
            $r->bindParam(':user', $user, PDO::PARAM_STR );
            $r->bindParam(':r5', $q, PDO::PARAM_STR );
            $r->execute();
          

            $GetDeckEncours = $BDD->prepare("SELECT * FROM deck WHERE nomdeck = :nomdeck");
            $GetDeckEncours->bindValue(':nomdeck',$game);
            $GetDeckEncours->execute();
            $resultat_deck = $GetDeckEncours->fetch(PDO::FETCH_ASSOC);
            $cache_question = $resultat_deck['q5'];

            $data_q = $BDD->prepare('SELECT * FROM cartes WHERE question = :question AND reponse = :reponse');
         	$data_q->bindParam(':question', $cache_question);
         	$data_q->bindParam(':reponse', $q);
         	$data_q->execute();


            $data_card = $BDD->prepare('SELECT * FROM cartes WHERE question = :question');
            $data_card->bindParam(':question', $cache_question);
            $data_card->execute();
            $result_card = $data_card->fetch(PDO::FETCH_ASSOC);
            $reponsevraie = $result_card['reponse'];

         	if ( $data_q->rowCount()>0){
         		$getrepnext = $BDD->prepare("SELECT * FROM JeuEnCours WHERE user = :user");
            	$getrepnext->bindValue(':user',$user);
				$getrepnext->execute();
				$repn = $getrepnext->fetch(PDO::FETCH_ASSOC);
				$prochainerep = $repn['r6']; // ON fait cela afin de pouvoir vérifier la prochaine réponse afin d'éviter la dupplication de points en rafraichissant la page car quand on ajoute un point, on met "pt" dans la case de la rep suivante
				
         		if($prochainerep==NULL){ // Si la condition n'es pas respectée c'est que l'utilisateur a essayé de rafraichir la page pour réessayer d"executer la fonction addpts
	         
                      $r5 = $BDD->prepare("UPDATE JeuEnCours SET r5 = :r5 WHERE user = :user");
                      $r5->bindParam(':user', $user, PDO::PARAM_STR );
                      $r5->bindParam(':r5', $q, PDO::PARAM_STR );
                      $r5->execute();

	         		$pts = "UPDATE users SET ptsactu =ptsactu+1 WHERE USER='$user'";
	            	$BDD->exec($pts);
	            	$antidupli = "UPDATE JeuEnCours SET r6 = 0 WHERE USER='$user'";
            		$BDD->exec($antidupli);
            }

         	  ?>
	    		<div class="cartebon">
	   			<p class="nquestion">Question N°<? echo "$valq";?></p>
	   			<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
	   			<p class="themeq"><? echo $game ?></p>
	  			<h1 class="litmarge">Bonne réponse, la réponse était bien :</h1>
	  			<h1>
	  				 <?php echo $q; ?>
	  			</h1> 
	  			<form action='jouer.php' method='POST'>
	  			<p class="litmarge"><button type='submit' nom='envoyer' class='button_good' input name="q6">Question suivante</button></p>
	  			</div>
	  		<?php
	  			die();
         		}else{
            	?>
			    		<div class="cartebad">
		   			<p class="nquestion">Question N°<? echo "$valq";?></p>
		   			<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
		   			<p class="themeq"><? echo $game ?></p>
		  			<h1 class="litmarge">Mauvaise réponse, la réponse était :</h1>
		  			<h1>
		  				 <?php echo $reponsevraie; ?>
		  			</h1> 
		  			<form action='jouer.php' method='POST'>
		  			<p class="litmarge"><button type='submit' nom='envoyer' class='button_bad' input name="q6">Question suivante</button></p>
		  			</div> 
	  			<?php
	  				die();
         	}

    	}
    	if(isset($_POST['q5'])){  
			  $nbq = '5'; 
			  $pregu = 'q5';
			  $formu = "q5";        
			  $jeuenc = $BDD->prepare('SELECT * FROM users WHERE user = :user');
			  $jeuenc->bindParam(':user', $user, PDO::PARAM_STR );
			  $jeuenc->execute();
			  $row = $jeuenc->fetch(PDO::FETCH_ASSOC);
			  $game = htmlspecialchars_decode($row['jeu']);


			        

			  $GetDeckEncours = $BDD->prepare("SELECT * FROM deck WHERE nomdeck = :nomdeck");
			  $GetDeckEncours->bindValue(':nomdeck',$game);
			  $GetDeckEncours->execute();
			  $resultat_deck = $GetDeckEncours->fetch(PDO::FETCH_ASSOC);
			  $cache_question = $resultat_deck[$pregu];

			  $data_q = $BDD->prepare('SELECT * FROM cartes WHERE question = :question AND reponse = :reponse');
			  $data_q->bindParam(':question', $cache_question);
			  $data_q->bindParam(':reponse', $pregu);
			  $data_q->execute();
			  ?>
				    <div class="carte">
			  	   	<p class="nquestion">Question N°<? echo "$nbq"?></p>
			  	   	<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
			  	   	<p class="themeq"><? echo $game ?></p>
			  	  	<h1 class="litmarge"><? echo $resultat_deck[$pregu]; ?></h1>
			  	  	<form action='jouer.php' method='POST'>
			  	  	<input name="q5v" type="text">
			  	  	<p class="litmarge"><button type='submit' nom='envoyer' class='button_site'>Envoyer</button></p>
				  	</div>
			  <?
			  die;
		}
    	if(isset($_POST['q4v'])){
    		$valq = "4";
    		$repn = "r4";

            $jeuenc = $BDD->prepare('SELECT * FROM users WHERE user = :user');
            $jeuenc->bindParam(':user', $user, PDO::PARAM_STR );
            $jeuenc->execute();
            $row = $jeuenc->fetch(PDO::FETCH_ASSOC);
            $game = htmlspecialchars_decode($row['jeu']);


            $q = Secu($_POST['q4v']);
          
            $r = $BDD->prepare("UPDATE JeuEnCours SET r4 = :r4 WHERE user = :user");
            $r->bindParam(':user', $user, PDO::PARAM_STR );
            $r->bindParam(':r4', $q, PDO::PARAM_STR );
            $r->execute();
        

            $GetDeckEncours = $BDD->prepare("SELECT * FROM deck WHERE nomdeck = :nomdeck");
            $GetDeckEncours->bindValue(':nomdeck',$game);
            $GetDeckEncours->execute();
            $resultat_deck = $GetDeckEncours->fetch(PDO::FETCH_ASSOC);
            $cache_question = $resultat_deck['q4'];

            $data_q = $BDD->prepare('SELECT * FROM cartes WHERE question = :question AND reponse = :reponse');
         	$data_q->bindParam(':question', $cache_question);
         	$data_q->bindParam(':reponse', $q);
         	$data_q->execute();


            $data_card = $BDD->prepare('SELECT * FROM cartes WHERE question = :question');
            $data_card->bindParam(':question', $cache_question);
            $data_card->execute();
            $result_card = $data_card->fetch(PDO::FETCH_ASSOC);
            $reponsevraie = $result_card['reponse'];

         	if ( $data_q->rowCount()>0){
         		$getrepnext = $BDD->prepare("SELECT * FROM JeuEnCours WHERE user = :user");
            	$getrepnext->bindValue(':user',$user);
				$getrepnext->execute();
				$repn = $getrepnext->fetch(PDO::FETCH_ASSOC);
				$prochainerep = $repn['r5']; // ON fait cela afin de pouvoir vérifier la prochaine réponse afin d'éviter la dupplication de points en rafraichissant la page car quand on ajoute un point, on met "pt" dans la case de la rep suivante
				
         		if($prochainerep==NULL){ // Si la condition n'es pas respectée c'est que l'utilisateur a essayé de rafraichir la page pour réessayer d"executer la fonction addpts
	         		
                    $r4 = $BDD->prepare("UPDATE JeuEnCours SET r4 = :r4 WHERE user = :user");
                    $r4->bindParam(':user', $user, PDO::PARAM_STR );
                    $r4->bindParam(':r4', $q, PDO::PARAM_STR );
                    $r4->execute();
	           		
	         		$pts = "UPDATE users SET ptsactu =ptsactu+1 WHERE USER='$user'";
	            	$BDD->exec($pts);
	            	$antidupli = "UPDATE JeuEnCours SET r5 = 0 WHERE USER='$user'";
            		$BDD->exec($antidupli);
            }

         	  ?>
	    		<div class="cartebon">
	   			<p class="nquestion">Question N°<? echo "$valq";?></p>
	   			<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
	   			<p class="themeq"><? echo $game ?></p>
	  			<h1 class="litmarge">Bonne réponse, la réponse était bien :</h1>
	  			<h1>
	  				 <?php echo $q; ?>
	  			</h1> 
	  			<form action='jouer.php' method='POST'>
	  			<p class="litmarge"><button type='submit' nom='envoyer' class='button_good' input name="q5">Question suivante</button></p>
	  			</div>
	  		<?php
	  			die();
         		}else{
            	?>
			    		<div class="cartebad">
		   			<p class="nquestion">Question N°<? echo "$valq";?></p>
		   			<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
		   			<p class="themeq"><? echo $game ?></p>
		  			<h1 class="litmarge">Mauvaise réponse, la réponse était :</h1>
		  			<h1>
		  				 <?php echo $reponsevraie; ?>
		  			</h1> 
		  			<form action='jouer.php' method='POST'>
		  			<p class="litmarge"><button type='submit' nom='envoyer' class='button_bad' input name="q5">Question suivante</button></p>
		  			</div> 
	  			<?php
	  				die();
         	}

    	}
    	if(isset($_POST['q4'])){  
			  $nbq = '4'; 
			  $pregu = 'q4';
			  $formu = "q4";        
			  $jeuenc = $BDD->prepare('SELECT * FROM users WHERE user = :user');
			  $jeuenc->bindParam(':user', $user, PDO::PARAM_STR );
			  $jeuenc->execute();
			  $row = $jeuenc->fetch(PDO::FETCH_ASSOC);
			  $game = htmlspecialchars_decode($row['jeu']);


			        

			  $GetDeckEncours = $BDD->prepare("SELECT * FROM deck WHERE nomdeck = :nomdeck");
			  $GetDeckEncours->bindValue(':nomdeck',$game);
			  $GetDeckEncours->execute();
			  $resultat_deck = $GetDeckEncours->fetch(PDO::FETCH_ASSOC);
			  $cache_question = $resultat_deck[$pregu];

			  $data_q = $BDD->prepare('SELECT * FROM cartes WHERE question = :question AND reponse = :reponse');
			  $data_q->bindParam(':question', $cache_question);
			  $data_q->bindParam(':reponse', $pregu);
			  $data_q->execute();
			  ?>
				    <div class="carte">
			  	   	<p class="nquestion">Question N°<? echo "$nbq"?></p>
			  	   	<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
			  	   	<p class="themeq"><? echo $game ?></p>
			  	  	<h1 class="litmarge"><? echo $resultat_deck[$pregu]; ?></h1>
			  	  	<form action='jouer.php' method='POST'>
			  	  	<input name="q4v" type="text">
			  	  	<p class="litmarge"><button type='submit' nom='envoyer' class='button_site'>Envoyer</button></p>
				  	</div>
			  <?
			  die;
		}
    	if(isset($_POST['q3v'])){
    		$valq = "3";
    		$repn = "r3";

            $jeuenc = $BDD->prepare('SELECT * FROM users WHERE user = :user');
            $jeuenc->bindParam(':user', $user, PDO::PARAM_STR );
            $jeuenc->execute();
            $row = $jeuenc->fetch(PDO::FETCH_ASSOC);
            $game = htmlspecialchars_decode($row['jeu']);


            $q = Secu($_POST['q3v']);
           // $r = "UPDATE JeuEnCours SET r3='$q' WHERE user='$user'";
            $r = $BDD->prepare("UPDATE JeuEnCours SET r3 = :r3 WHERE user = :user");
            $r->bindParam(':user', $user, PDO::PARAM_STR );
            $r->bindParam(':r3', $q, PDO::PARAM_STR );
            $r->execute();
           	//$BDD->exec($r);

            $GetDeckEncours = $BDD->prepare("SELECT * FROM deck WHERE nomdeck = :nomdeck");
            $GetDeckEncours->bindValue(':nomdeck',$game);
            $GetDeckEncours->execute();
            $resultat_deck = $GetDeckEncours->fetch(PDO::FETCH_ASSOC);
            $cache_question = $resultat_deck['q3'];

            $data_q = $BDD->prepare('SELECT * FROM cartes WHERE question = :question AND reponse = :reponse');
         	$data_q->bindParam(':question', $cache_question);
         	$data_q->bindParam(':reponse', $q);
         	$data_q->execute();


            $data_card = $BDD->prepare('SELECT * FROM cartes WHERE question = :question');
            $data_card->bindParam(':question', $cache_question);
            $data_card->execute();
            $result_card = $data_card->fetch(PDO::FETCH_ASSOC);
            $reponsevraie = $result_card['reponse'];

         	if ( $data_q->rowCount()>0) {
         		$getrepnext = $BDD->prepare("SELECT * FROM JeuEnCours WHERE user = :user");
            	$getrepnext->bindValue(':user',$user);
				$getrepnext->execute();
				$repn = $getrepnext->fetch(PDO::FETCH_ASSOC);
				$prochainerep = $repn['r4']; // ON fait cela afin de pouvoir vérifier la prochaine réponse afin d'éviter la dupplication de points en rafraichissant la page car quand on ajoute un point, on met "pt" dans la case de la rep suivante
				
         		if($prochainerep==NULL){ // Si la condition n'es pas respectée c'est que l'utilisateur a essayé de rafraichir la page pour réessayer d"executer la fonction addpts
	         		//$r3 = "UPDATE JeuEnCours SET r3='$q' WHERE user='$user'";
	           		//$BDD->exec($r3);
                    $r3 = $BDD->prepare("UPDATE JeuEnCours SET r3 = :r3 WHERE user = :user");
                    $r3->bindParam(':user', $user, PDO::PARAM_STR );
                    $r3->bindParam(':r3', $q, PDO::PARAM_STR );
                    $r3->execute();
	         		$pts = "UPDATE users SET ptsactu =ptsactu+1 WHERE USER='$user'";
	            	$BDD->exec($pts);
	            	$antidupli = "UPDATE JeuEnCours SET r4 = 0 WHERE USER='$user'";
            		$BDD->exec($antidupli);
            }

         	  ?>
	    		<div class="cartebon">
	   			<p class="nquestion">Question N°<? echo "$valq";?></p>
	   			<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
	   			<p class="themeq"><? echo $game ?></p>
	  			<h1 class="litmarge">Bonne réponse, la réponse était bien :</h1>
	  			<h1>
	  				 <?php echo $q; ?>
	  			</h1> 
	  			<form action='jouer.php' method='POST'>
	  			<p class="litmarge"><button type='submit' nom='envoyer' class='button_good' input name="q4">Question suivante</button></p>
	  			</div>
	  		<?php
	  			die();
         		}else{
            	?>
		    		<div class="cartebad">
		   			<p class="nquestion">Question N°<? echo "$valq";?></p>
		   			<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
		   			<p class="themeq"><? echo $game ?></p>
		  			<h1 class="litmarge">Mauvaise réponse, la réponse était :</h1>
		  			<h1>
		  				 <?php echo $reponsevraie; ?>
		  			</h1> 
		  			<form action='jouer.php' method='POST'>
		  			<p class="litmarge"><button type='submit' nom='envoyer' class='button_bad' input name="q4">Question suivante</button></p>
		  			</div> 
	  			<?php
	  				die();
         	}

    	}
    	if(isset($_POST['q3'])){  
			  $nbq = '3'; 
			  $pregu = 'q3';
			  $formu = "q3";        
			  $jeuenc = $BDD->prepare('SELECT * FROM users WHERE user = :user');
			  $jeuenc->bindParam(':user', $user, PDO::PARAM_STR );
			  $jeuenc->execute();
			  $row = $jeuenc->fetch(PDO::FETCH_ASSOC);
			  $game = htmlspecialchars_decode($row['jeu']);


			        

			  $GetDeckEncours = $BDD->prepare("SELECT * FROM deck WHERE nomdeck = :nomdeck");
			  $GetDeckEncours->bindValue(':nomdeck',$game);
			  $GetDeckEncours->execute();
			  $resultat_deck = $GetDeckEncours->fetch(PDO::FETCH_ASSOC);
			  $cache_question = $resultat_deck[$pregu];

			  $data_q = $BDD->prepare('SELECT * FROM cartes WHERE question = :question AND reponse = :reponse');
			  $data_q->bindParam(':question', $cache_question);
			  $data_q->bindParam(':reponse', $pregu);
			  $data_q->execute();
			  ?>
				    <div class="carte">
			  	   	<p class="nquestion">Question N°<? echo "$nbq"?></p>
			  	   	<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
			  	   	<p class="themeq"><? echo $game ?></p>
			  	  	<h1 class="litmarge"><? echo $resultat_deck[$pregu]; ?></h1>
			  	  	<form action='jouer.php' method='POST'>
			  	  	<input name="q3v" type="text">
			  	  	<p class="litmarge"><button type='submit' nom='envoyer' class='button_site'>Envoyer</button></p>
				  	</div>
			  <?
			  die;
		}
    	if(isset($_POST['q2v'])){
    		$valq = "2";
    		$repn = "r2";
            
            $jeuenc = $BDD->prepare('SELECT * FROM users WHERE user = :user');
            $jeuenc->bindParam(':user', $user, PDO::PARAM_STR );
            $jeuenc->execute();
            $row = $jeuenc->fetch(PDO::FETCH_ASSOC);
            $game = htmlspecialchars_decode($row['jeu']);


            $q = Secu($_POST['q2v']);
            // $r = "UPDATE JeuEnCours SET r2='$q' WHERE user='$user'";
            $r = $BDD->prepare("UPDATE JeuEnCours SET r2 = :r2 WHERE user = :user");
            $r->bindParam(':user', $user, PDO::PARAM_STR );
            $r->bindParam(':r2', $q, PDO::PARAM_STR );
            $r->execute();
            
           	// $BDD->exec($r);

            $GetDeckEncours = $BDD->prepare("SELECT * FROM deck WHERE nomdeck = :nomdeck");
            $GetDeckEncours->bindValue(':nomdeck',$game);
            $GetDeckEncours->execute();
            $resultat_deck = $GetDeckEncours->fetch(PDO::FETCH_ASSOC);
            $cache_question = $resultat_deck['q2'];

            $data_q = $BDD->prepare('SELECT * FROM cartes WHERE question = :question AND reponse = :reponse');
         	$data_q->bindParam(':question', $cache_question);
         	$data_q->bindParam(':reponse', $q);
         	$data_q->execute();


            $data_card = $BDD->prepare('SELECT * FROM cartes WHERE question = :question');
            $data_card->bindParam(':question', $cache_question);
            $data_card->execute();
            $result_card = $data_card->fetch(PDO::FETCH_ASSOC);
            $reponsevraie = $result_card['reponse'];

         	if ( $data_q->rowCount()>0) {
         		$getrepnext = $BDD->prepare("SELECT * FROM JeuEnCours WHERE user = :user");
            	$getrepnext->bindValue(':user',$user);
				$getrepnext->execute();
				$repn = $getrepnext->fetch(PDO::FETCH_ASSOC);
				$prochainerep = $repn['r3']; // ON fait cela afin de pouvoir vérifier la prochaine réponse afin d'éviter la dupplication de points en rafraichissant la page car quand on ajoute un point, on met "pt" dans la case de la rep suivante
				
         		if($prochainerep==NULL){ // Si la condition n'es pas respectée c'est que l'utilisateur a essayé de rafraichir la page pour réessayer d"executer la fonction addpts
                    $r2 = $BDD->prepare("UPDATE JeuEnCours SET r2 = :r2 WHERE user = :user");
                    $r2->bindParam(':user', $user, PDO::PARAM_STR );
                    $r2->bindParam(':r2', $q, PDO::PARAM_STR );
                    $r2->execute();

	         		//$r2 = "UPDATE JeuEnCours SET r2='$q' WHERE user='$user'";
	           		//$BDD->exec($r2);
	         		$pts = "UPDATE users SET ptsactu =ptsactu+1 WHERE USER='$user'";
	            	$BDD->exec($pts);
	            	$antidupli = "UPDATE JeuEnCours SET r3 = 0 WHERE USER='$user'";
            		$BDD->exec($antidupli);
            }

         	  ?>
	    		<div class="cartebon">
	   			<p class="nquestion">Question N°<? echo "$valq";?></p>
	   			<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
	   			<p class="themeq"><? echo $game ?></p>
	  			<h1 class="litmarge">Bonne réponse, la réponse était bien :</h1>
	  			<h1>
	  				 <?php echo $q; ?>
	  			</h1> 
	  			<form action='jouer.php' method='POST'>
	  			<p class="litmarge"><button type='submit' nom='envoyer' class='button_good' input name="q3">Question suivante</button></p>
	  			</div>
	  		<?php
	  			die();
         		}else{
            	?>
		    		<div class="cartebad">
		   			<p class="nquestion">Question N°<? echo "$valq";?></p>
		   			<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
		   			<p class="themeq"><? echo $game ?></p>
		  			<h1 class="litmarge">Mauvaise réponse, la réponse était :</h1>
		  			<h1>
		  				 <?php echo $reponsevraie; ?>
		  			</h1> 
		  			<form action='jouer.php' method='POST'>
		  			<p class="litmarge"><button type='submit' nom='envoyer' class='button_bad' input name="q3">Question suivante</button></p>
		  			</div> 
	  			<?php
	  				die();
         	}

    	}

    	if(isset($_POST['q2'])){  

			  $nbq = '2'; 
			  $pregu = 'q2';
			  $pregun = 'q3';
			  $formu = "q2";        
			  $jeuenc = $BDD->prepare('SELECT * FROM users WHERE user = :user');
			  $jeuenc->bindParam(':user', $user, PDO::PARAM_STR );
			  $jeuenc->execute();
			  $row = $jeuenc->fetch(PDO::FETCH_ASSOC);
			  $game = htmlspecialchars_decode($row['jeu']);


			        

			  $GetDeckEncours = $BDD->prepare("SELECT * FROM deck WHERE nomdeck = :nomdeck");
			  $GetDeckEncours->bindValue(':nomdeck',$game);
			  $GetDeckEncours->execute();
			  $resultat_deck = $GetDeckEncours->fetch(PDO::FETCH_ASSOC);
			  $cache_question = $resultat_deck[$pregu];

			  $data_q = $BDD->prepare('SELECT * FROM cartes WHERE question = :question AND reponse = :reponse');
			  $data_q->bindParam(':question', $cache_question);
			  $data_q->bindParam(':reponse', $pregu);
			  $data_q->execute();
			  ?>
				    <div class="carte">
			  	   	<p class="nquestion">Question N°<? echo "$nbq"?></p>
			  	   	<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
			  	   	<p class="themeq"><? echo $game ?></p>
			  	  	<h1 class="litmarge"><? echo $resultat_deck[$pregu]; ?></h1>
			  	  	<form action='jouer.php' method='POST'>
			  	  	<input name="q2v" type="text">
			  	  	<p class="litmarge"><button type='submit' nom='envoyer' class='button_site'>Envoyer</button></p>
				  	</div>
			  <?
			  die;
		}



    	if(isset($_POST['q1r'])){
            
            $jeuenc = $BDD->prepare('SELECT * FROM users WHERE user = :user');
            $jeuenc->bindParam(':user', $user, PDO::PARAM_STR );
            $jeuenc->execute();
            $row = $jeuenc->fetch(PDO::FETCH_ASSOC);
            $game = htmlspecialchars_decode($row['jeu']);


            $resetq = $BDD->prepare("UPDATE `JeuEnCours` SET `r1` = NULL, `r2` = NULL, `r3` = NULL, `r4` = NULL, `r5` = NULL, `r6` = NULL, `r7` = NULL, `r8` = NULL, `r9` = NULL, `r10` = NULL, `fini` = 0, `scorefinal` =0  WHERE `JeuEnCours`.`user` = :user");
            $resetq->bindParam(':user', $user, PDO::PARAM_STR );
            $resetq->execute();

            $q1 = Secu($_POST['q1r']);

            $r1 = $BDD->prepare("UPDATE JeuEnCours SET r1= :r1 WHERE user = :user");
            $r1->bindParam(':user', $user, PDO::PARAM_STR );
            $r1->bindParam(':r1', $q1, PDO::PARAM_STR );
            $r1->execute();

            $theme = $BDD->prepare("UPDATE JeuEnCours SET theme= :theme WHERE user = :user");
            $theme->bindParam(':user', $user, PDO::PARAM_STR );
            $theme->bindParam(':theme', $game, PDO::PARAM_STR );
            $theme->execute();
         
            //$theme = "UPDATE JeuEnCours SET theme='$game' WHERE user='$user'";
            //$BDD->exec($theme);

            $GetDeckEncours = $BDD->prepare("SELECT * FROM deck WHERE nomdeck = :nomdeck");
            $GetDeckEncours->bindValue(':nomdeck',$game);
            $GetDeckEncours->execute();
            $resultat_deck = $GetDeckEncours->fetch(PDO::FETCH_ASSOC);
            $cache_question1 = $resultat_deck['q1'];

            $data_q1 = $BDD->prepare('SELECT * FROM cartes WHERE question = :question AND reponse = :reponse');
         	$data_q1->bindParam(':question', $cache_question1);
         	$data_q1->bindParam(':reponse', $q1);
         	$data_q1->execute();


            $data_card = $BDD->prepare('SELECT * FROM cartes WHERE question = :question');
            $data_card->bindParam(':question', $cache_question1);
            $data_card->execute();
            $result_card = $data_card->fetch(PDO::FETCH_ASSOC);
            $reponsevraie = $result_card['reponse'];

         	if ( $data_q1->rowCount()>0) {
         		$getrepnext = $BDD->prepare("SELECT * FROM JeuEnCours WHERE user = :user");
            	$getrepnext->bindValue(':user',$user);
				$getrepnext->execute();
				$repn = $getrepnext->fetch(PDO::FETCH_ASSOC);
				$prochainerep = $repn['r2']; // ON fait cela afin de pouvoir vérifier la prochaine réponse afin d'éviter la dupplication de points en rafraichissant la page car quand on ajoute un point, on met "pt" dans la case de la rep suivante
				
         		if($prochainerep==NULL){ // Si la condition n'es pas respectée c'est que l'utilisateur a essayé de rafraichir la page pour réessayer d"executer la fonction addpts
	         		
                    $r1 = $BDD->prepare("UPDATE JeuEnCours SET r1= :r1 WHERE user = :user");
                    $r1->bindParam(':user', $user, PDO::PARAM_STR );
                    $r1->bindParam(':r1', $q1, PDO::PARAM_STR );
                    $r1->execute();
                    
                    
	         		$pts = "UPDATE users SET ptsactu =ptsactu+1 WHERE USER='$user'";
	            	$BDD->exec($pts);
	            	$antidupli = "UPDATE JeuEnCours SET r2 = 0 WHERE USER='$user'";
            		$BDD->exec($antidupli);
            }

         	  ?>
	    		<div class="cartebon">
	   			<p class="nquestion">Question N°1</p>
	   			<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
	   			<p class="themeq"><? echo $game ?></p>
	  			<h1 class="litmarge">Bonne réponse, la réponse était bien :</h1>
	  			<h1>
	  				 <?php echo $q1; ?>
	  			</h1>
	  			<form action='jouer.php' method='POST'>
	  			<p class="litmarge"><button type='submit' nom='envoyer' class='button_good' input name="q2">Question suivante</button></p>
	  			</div>
	  		<?php
	  			die();
         		}else{
            	?>
		    		<div class="cartebad">
		   			<p class="nquestion">Question N°1</p>
		   			<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
		   			<p class="themeq"><? echo $game ?></p>
		  			<h1 class="litmarge">Mauvaise réponse, la réponse était :</h1>
		  			<h1>
		  				 <?php echo $reponsevraie; ?>
		  			</h1> 
		  			<form action='jouer.php' method='POST'>
		  			<p class="litmarge"><button type='submit' nom='envoyer' class='button_bad' input name="q2">Question suivante</button></p>
		  			</div> 
	  			<?php
	  				die();
         	}

    	}

    	if(isset($_POST['continue'])){

            $_SESSION['point'] = 0;
            $_SESSION['jeu'] = $_POST['continue'];
            $cache = $_SESSION['jeu'];

            //$jeu = "UPDATE users SET jeu='$cache' WHERE USER='$user'";
            // $BDD->exec($jeu);
            $jeu = $BDD->prepare("UPDATE users SET jeu = :jeu WHERE USER = :user");
            $jeu->bindParam(':user', $user, PDO::PARAM_STR );
            $jeu->bindParam(':jeu', $cache, PDO::PARAM_STR );
            $jeu->execute();
            
            $pts = "UPDATE users SET ptsactu =0 WHERE USER='$user'";
            $BDD->exec($pts);

            $jeuenc = $BDD->prepare('SELECT * FROM users WHERE user = :user');
            $jeuenc->bindParam(':user', $user, PDO::PARAM_STR );
            $jeuenc->execute();
            $row = $jeuenc->fetch(PDO::FETCH_ASSOC);
            $game = htmlspecialchars_decode($row['jeu']);

            $GetDeckEncours = $BDD->prepare("SELECT * FROM deck WHERE nomdeck = :nomdeck");
            $GetDeckEncours->bindValue(':nomdeck',$game);
            $GetDeckEncours->execute();
            $resultat_deck = $GetDeckEncours->fetch(PDO::FETCH_ASSOC);
    		?>
	    		<div class="carte">
	   			<p class="nquestion">Question N°1</p>
	   			<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
	   			<p class="themeq"><? echo $game; ?></p>
	  			<h1 class="litmarge"><? echo $resultat_deck['q1']; ?></h1>
	  			<form action='jouer.php' method='POST'>
	  			<input name="q1r" type="text">
	  			<p class="litmarge"><button type='submit' nom='envoyer' class='button_site'>Envoyer</button></p>
	  		</div>
  			<?
    		die;
    	}
    	if(isset($_POST['continuerefaire'])){
            // Comme le joueur recommence on remet ses points en cours à 0 pour qu'il recommence la partie
            $pts = "UPDATE users SET ptsactu =0 WHERE USER='$user'";
            $BDD->exec($pts);
            
            // PDO::PARAM_STR n'est pas utile puis ce que c'est la valeur par défaut
            $jeuenc = $BDD->prepare('SELECT * FROM users WHERE user = :user');
            $jeuenc->bindParam(':user', $user, PDO::PARAM_STR );
            $jeuenc->execute();
            $row = $jeuenc->fetch(PDO::FETCH_ASSOC);
            $game = htmlspecialchars_decode($row['jeu']);

            // On recupère les information du deck en cours
            $GetDeckEncours = $BDD->prepare("SELECT * FROM deck WHERE nomdeck = :nomdeck");
            $GetDeckEncours->bindValue(':nomdeck',$game);
            $GetDeckEncours->execute();
            $resultat_deck = $GetDeckEncours->fetch(PDO::FETCH_ASSOC);
    		?>
	    		<div class="carte">
	   			<p class="nquestion">Question N°1</p>
	   			<p class="themeq"><? echo "Thème : ".$resultat_deck['categorie']; ?></p>
	   			<p class="themeq"><? echo $game; ?></p>
	  			<h1 class="litmarge"><? echo $resultat_deck['q1']; ?></h1>
	  			<form action='jouer.php' method='POST'>
	  			<input name="q1r" type="text">
	  			<p class="litmarge"><button type='submit' nom='envoyer' class='button_site'>Envoyer</button></p>
	  		</div>
  			<?
    		die;
    	}

        }
 
?>
<html>
<head>
</head>
<body>
<div class="line1"></div>
<div class="line2"></div>

<div class="row">
  <div class="column left">
     <!--
        On se situe dans la partie à Gauche de jouer.php
        Cette zone permet d'enregistrer les informations obtenu lors du jeu en cours du thème etc sur la partie joué pour qu'elle s'affiche en haut au dessus des decks les plus jouées
    -->
    <form action="jouer.php" method="POST">
    
    <? // On récupère les informations de l'utilisateur connecté sur la page et on récupère les informations des parties précédentes comme un objet
        $getlastgame = $BDD->prepare("SELECT * FROM JeuEnCours WHERE user = :user");
       $getlastgame->bindValue(':user',$user);
	   $getlastgame->execute();
	   $lastg = $getlastgame->fetch(PDO::FETCH_ASSOC);
        // on vérifie qu'il a déjà jouer une partie
       if ( $getlastgame->rowCount()>0) {
       // lastg['fini'] == 1 correspond à savoir si le joueur a bien terminé la patie en cours
	   if($lastg['fini']==1){
	    ?>
    <h2>Ma dernière partie</h2>
       <div class="lastg" name="block">
            <?php  
                // On affiche le thème de la dernière partie
                echo "<h1>";
                echo  $lastg['theme'];
                echo "</h1>";
            ?>
	     <p class="qname">

            <?php 
                // On affiche le score final de la derrière partie / 10 puis ce qu'il y a 10 questions
                echo "Score obtenu : ".$lastg['scorefinal']."/10";
            ?>
	    </p>
	    <p>
            <button name="continuerefaire" type="submit" value="<?= $row['nomdeck'] ?>">Refaire ce quiz</button>
	    </p>
	   </div>
	   <? } }?>
    <h2 class="minimarge">QUIZ disponibles</h2>
    <!-- Grâce à la fonction au tout début du fichier qui récupérait tout les deck valide, on afiiche les noms indivduellement ainsi que leurs catégories et leurs noms -->
    <?php foreach ($resultat_deck as $row){?>
       <div class="lastg" name="block">
            <?php 
                echo "<h1>";
                echo htmlspecialchars_decode($row['nomdeck']);
                echo "</h1>";	
            ?>
	     <p class="qname">

            <?php 
          		echo '<p class="qname"> Difficulté : '.$row['difficulte'].'</p>';
                echo htmlspecialchars_decode($row['categorie'])." par ".htmlspecialchars_decode($row['nom']);
            ?>
	    </p>
	    <p>
            <!-- On a créer directement un bouton ici pour que le joueur puis ce jouer à tout les quizz qu'ils souhaitent -->
            <button name="continue" type="submit" value="<?= $row['nomdeck'] ?>">Faire ce quiz</button>
	    </p>
	   </div>
     <?php } ?>
  </div>
  </form>

			  <div class="column middle">
                <!--
                     Cette section correspond à celle du milieu 
                     On affiche toutes les catégories valide et on vérifie combie il y a de nombre deck appartenant à cette catégorie
                -->
				<h2>
			  		Thèmes Disponibles 
			  	</h2>
			    	<div class="grid-container">
					<?php foreach ($resultat_categorie as $row){?>
			                <div class="grid-item" value="<?= $row['nom'] ?>"><?echo htmlspecialchars_decode($row['nom']);
				                 $getnbdeck = $BDD->prepare("SELECT * FROM deck WHERE categorie = :categorie AND valide = 1");
							     $getnbdeck->bindValue(':categorie',$row['nom']);
								 $getnbdeck->execute();?>
								 <p>Nombre de jeux : <? echo $getnbdeck->rowCount() ?> </p>
								 <form action="categorie.php" method="POST">
								 	<button class = "buttoncat"name="affichercat" type="submit" value="<?= $row['nom'] ?>">Voir les quiz</button>
								 </form> 
			                </div>
			         <?php } ?>
					</div>
				</div>

  <div class="column right">
    <h2>
    	Meilleurs joueurs
    </h2>
      <!-- On classe tout les joueurs en fonction des points -->
    <?php foreach ($resultat_classplayer as $row){?>
      <div class="bestj">
		  <h1><?php echo $row['userMAJ']; ?></h1>
		  <p>
            <!-- On affiche les points obtenus des joueurs -->
		  	Nombre de points total : <?php echo $row['point']; ?>
		  </p>
		  <p>
		  	Dernier jeu joué : <?php
                                // Afin de pas afficher une erreur on vérifie si le joueur a bien une session en cours
		  						 if($row['jeu'] ==NULL){
		  						 	echo "Aucun";
		  						 }
		  						 else{
                                     // Dernier jeu auquel il a joué
		  						 	echo $row['jeu']; 
		  						 }
		  						?>
		  </p>
		</div>
        <p class="minimarge"></p>
     <?php } ?>
		
  </div>
</div>

</body>
</html>

