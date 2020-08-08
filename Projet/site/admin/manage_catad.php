<?php 
     /*
        La page permet aux admins de gérer les catégories 
        Le titre de la page: Gérer les catégories
        Ainsi que l'attribution du header pour la navbar et le fichier function qui est indispensable pour la connexion à la BDD
    */   
     $titre = "Gérer les catégories";
     include '../header.php';
    
     if(!is_login()){
         Redirect('../util/connexion.php');
     }


    if(!is_admin()){
        Redirect('../index.php');
    } 
    

    $user = Secu($_SESSION['user']);
    $GetDeck = $BDD->prepare("SELECT * FROM deck WHERE nom = :user");
    $GetDeck->bindValue(':user',$user);
    $GetDeck->execute();
    $resultat_deck = $GetDeck->fetchAll();

    // On sélectionne les catégories valide
    $GetCat = $BDD->prepare("SELECT * FROM categories WHERE valide = 1");
    $GetCat->execute();
    $resultat_cat = $GetCat->fetchAll();

   
    
  
    // On vérifie que la requete est bien POST on peut utiliser === ou ==
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        // On vérifie que la méthode utilisé est bien delete
       	if(isset($_POST['delete'])){
            ?>  
                <table class="tabadmin" style="width: 643px; height: 75px;">
                    <tbody>
                        <tr>
                            <td style="width: 161px;" class="tdadmin">Voulez vous vraiment supprimer cette catégorie ?
                                <p style="color: red;">
                                    (Attention cela supprimera les decks de cette catégorie et mettra la catégorie "Par défaut" au cartes de cette catégorie !)
                                </p> 
                            </td>
                            <form action="manage_catad.php" method="POST"> 
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
        // On vérifie que la méthode utiliser est yes
        if(isset($_POST['yes'])){
            $theme = $_POST['yes'];
        
            $data = $BDD->prepare("UPDATE `cartes` SET `theme` = 'Par défaut' WHERE `cartes`.`theme` = :theme");
            $data->bindParam(':theme', $theme);
            $data->execute();
      
            $data2 = $BDD->prepare('DELETE FROM deck WHERE categorie = :theme');
            $data2->bindParam(':theme', $theme);
            $data2->execute();
            $data3 = $BDD->prepare('DELETE FROM categories WHERE nom = :theme');
            $data3->bindParam(':theme', $theme);
            $data3->execute();
            // Permet de rafraichir la page
            header("Refresh:0");
        }
        if(isset($_POST['no'])){
            // Permet de rafraichir la page
            header("Refresh:0");
        }
    }

    
?>


		<form action="manage_catad.php" method="POST">
	        <table class="tabadmin" style="width: 20%; height: 75px;">
	            <tbody>
	                <tr>
	                    <td style="width: 60%;" class="tdredac">
	                    	Nom de la catégorie
	                    </td>
	                    <td style="width: 40%;" class="tdredac">
	                		Action :
	                	</td>
	                </tr>
	                <tr>
                          <!--
                 On séléctionnes toutes les catégories qui ont été validé par l'administrateur 
                Pour sélectionner uniquement celle qui sont correct
                On affiche avec foreach tout les nom de toutes les catégories valides
                                        -->
	                	<?php foreach ($resultat_cat as $row){?>
                            <tr>
    	                		<td style="width: 60%;" class="tdredacq">
    	                			<?echo htmlspecialchars_decode($row['nom']); ?>
    	                		</td>
    	                		<td style="width: 40%;" class="tdredacq">
                                    <button name="delete" type="submit" value="<?= $row['nom']; ?>" class="butredac" >Supprimer la catégorie</button>
    	                		</td>
                            </tr>
                     	<?php } ?>
	            </tbody>
	        </table>
        </form>
    </body> 
</html>