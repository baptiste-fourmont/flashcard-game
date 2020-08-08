<? 	
    /*
        La page pour gérer les membres
        Le titre de la page: Gestion inscrit
        Ainsi que l'attribution du header pour la navbar et le fichier function qui est indispensable pour la connexion à la BDD
    */ 
	$titre = "Gestion Inscrits";
	include '../header.php';


    if( !is_admin()){
        Redirect('../index.php');
    }
	$user = $_SESSION['user'];

    // On creer une requete qui selectionne les users et leurs grades en fonction décroissant les admins en haut et user en bas par exmple
	$ClassPlayer = $BDD->prepare("SELECT * FROM `users`ORDER BY `users`.`UserType` DESC");
    $ClassPlayer->execute();
    $resultat_classplayer = $ClassPlayer->fetchAll();

    // On vérifie que la requete est bien POST
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        // On vérifie que le bouton a été bien utilisé
    	if(isset($_POST['deleteacc'])){
    		?>	
    			<table class="tabadmin" style="width: 643px; height: 75px;">
					<tbody>
						<tr>
							<td style="width: 161px;" class="tdadmin">Voulez vous vraiment supprimer le compte de <? echo $_POST['deleteacc'] ;?> 
								<p style="color: red;">
									(Attention cela supprimera les decks crées par celui-ci (mais pas les questions))
								</p> 
							</td>
							<form action="accmanage.php" method="POST"> 
								<td style="width: 100px;" class="tdadmin"> <button name="yes" type="submit" value="<?= $_POST['deleteacc'] ?>" class="butpanadel" >Oui</button></td>
								<td style="width: 100px;" class="tdadmin"><button name="no" type="submit"value="<?= $_POST['deleteacc'] ?>" class="butpanadel" >Non</button> </td>
							</form>
						</tr>
						<tr>
						</tr>
					</tbody>
				</table>
				<?
				die();
			}
            // On vérifie que le boutton yes a bien été selectionné
			if(isset($_POST['yes'])){
				$nomduser = $_POST['yes'];
	    		$data = $BDD->prepare('DELETE FROM users WHERE user = :user');
	            $data->bindParam(':user', $nomduser);
	            $data->execute();
	    		$data2 = $BDD->prepare('DELETE FROM JeuEnCours WHERE user = :user');
	            $data2->bindParam(':user', $nomduser);
	            $data2->execute();
	            $data3 = $BDD->prepare('DELETE FROM deck WHERE nom = :user');
	            $data3->bindParam(':user', $nomduser);
	            $data3->execute();
                // On rafraichit la page
	            header("Refresh:0");
			}
            // On verifie que le button non a été selectioné
			if(isset($_POST['no'])){
                // On rafraichit la page
				header("Refresh:0");
			}


        // On vérifie que le bouton promote a bien été selectionné il permet de mettre des utilisateurs en rédacteur
    	if(isset($_POST['promote'])){
    		$iduser = $_POST['promote'];
    		$promote = ("UPDATE `users` SET `UserType` = '2' WHERE `users`.`id` = $iduser;");
    		$BDD->exec($promote);
            // On rafraichit la page
	        header("Refresh:0");
    	}
        
        // On vérifie que le boutton demote a bien été selectionné il permet de rendre n'importe quelle utilisateur de le mettre  en utilisateur
    	if(isset($_POST['demote'])){
    		$iduser = $_POST['demote'];
    		$demote = ("UPDATE `users` SET `UserType` = '1' WHERE `users`.`id` = $iduser;");
    		$BDD->exec($demote);
    		header("Refresh:0");
    	} 

    }

?>

	<table class="tabadmin" style="width: 643px; height: 75px;">
		<tbody>
			<tr>
				<td style="width: 161px;" class="tdadmin">Nom de l'utilisateur</td>
				<td style="width: 139px;" class="tdadmin">Adresse mail</td>
				<td style="width: 81px;" class="tdadmin">Rang</td>
				<td style="width: 118px;" class="tdadmin">Nombre de points</td>
				<td style="width: 142px;" class="tdadmin">Actions sur l'utilisateur</td>
			</tr>
			<?php foreach ($resultat_classplayer as $row){?>
				<tr>
					<td style="width: 161px;" class="tdnom"><?php echo $row['user']; ?></td>
					<td style="width: 139px;" class="tdnom"><?php echo $row['mail']; ?></td>
					<td style="width: 81px;" class="tdnom"><?php if ($row['UserType']==1){
																	echo "Utilisateur";
																}elseif($row['UserType']==2){
																	echo "Rédacteur";
																}else{
																	echo "Administrateur";
																}?>											
					</td>
					<td style="width: 118px;" class="tdnom"><?php echo $row['point']; ?></td>
					<td style="width: 142px;" class="tdnom"><?php if ($row['UserType']==1){
																	?>
																	<form action="accmanage.php" method="POST"> 
																		<button name="promote" type="submit" value="<?= $row['id'] ?>" class="butpanadpro" >Promouvoir en rédacteur</button>
																		<button name="deleteacc" type="submit"value="<?= $row['user'] ?>" class="butpanadpro" >Supprimer le compte de l'utilisateur</button>
																	</form> <?
																}elseif($row['UserType']==2){
																	?>
																	<form action="accmanage.php" method="POST"> 
																		<button name="demote" type="submit" value="<?= $row['id'] ?>" class="butpanadpro" >Destituer en utilisateur</button>
																		<button name="deleteacc" type="submit" value="<?= $row['user'] ?>" class="butpanadpro" >Supprimer le compte de l'utilisateur</button>
																	</form> <?
																}else{
																	echo "/";
																}?>											
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</body>
</html>