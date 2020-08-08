<?
/*
        La page du profil du compte 
        Le titre de la page
        Ainsi que l'attribution du header pour la navbar et le fichier function qui est indispensable pour la connexion à la BDD
*/
$titre = "Mon Profil";
include '../header.php';
$user = Secu($_SESSION['user']);
if (!is_login())
{
    Redirect('../util/connexion.php');
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    if (isset($_POST['deleteaccount']))
    {
?>	
    			<table class="tabadmin" style="width: 643px; height: 75px;">
					<tbody>
						<tr>
							<td style="width: 161px;" class="tdadmin">Voulez vous vraiment supprimer votre compte <? echo $user; ?> 
								<p style="color: red;">
									(Attention cela supprimera les decks crées par vous-même si vous en avez crée (mais pas les questions))
								</p> 
							</td>
							<form action="profile.php" method="POST"> 
								<td style="width: 100px;" class="tdadmin"> <button name="yes" type="submit" value="<?=$user ?>" class="butpanadel" >Oui</button></td>
								<td style="width: 100px;" class="tdadmin"><button name="no" type="submit"value="$user" class="butpanadel" >Non</button> </td>
							</form>
						</tr>
						<tr>
						</tr>
					</tbody>
				</table>
				<?
        die();
    }
    if (isset($_POST['yes']))
    {
        $data = $BDD->prepare('DELETE FROM users WHERE user = :user');
        $data->bindParam(':user', $user);
        $data->execute();
        $data2 = $BDD->prepare('DELETE FROM JeuEnCours WHERE user = :user');
        $data2->bindParam(':user', $user);
        $data2->execute();
        $data3 = $BDD->prepare('DELETE FROM deck WHERE nom = :user');
        $data3->bindParam(':user', $user);
        $data3->execute();
        session_unset();
        session_destroy();
        Redirect('../util/connexion.php');
        echo 'Votre compte a été supprimé';
    }
    if (isset($_POST['no']))
    {
        header("Refresh:0");
    }
    if (isset($_POST['nom']))
    {
        $themesel = $_POST['nom'];
        echo $themesel;
        $data = $BDD->prepare('UPDATE `users` SET `graph` = :themesel WHERE `users`.`user` = :user;');
        $data->bindParam(':user', $user, PDO::PARAM_STR);
        $data->bindParam(':themesel', $themesel, PDO::PARAM_STR);
        $data->execute();
        header("Refresh:0");
    }
}

?>


        <h2 class="hredac"> Bienvenue <?php echo $user; ?> sur votre profil </h2>
        <h3 class="hredac"> Que souhaitez vous faire ? </h3>
        <table class="tabadmin" style="width: 643px; height: 75px;">
            <tbody>
                <tr>
                    <td style="width: 161px;" class="tdredac">
                    	<a href="changeinfos.php">
                    		Modifier mes informations personnelles
                    	</a>
                    </td>
                </tr>
                <tr>
                    <td style="width: 161px;" class="tdredac">
                    	<a href="pswd_user.php">
                    		Modifier mon mot de passe
                    	</a>
                    </td>
                </tr>
                <tr>
                    <td style="width: 161px;" class="tdredac">
                    	<a href="../util/deconnexion.php">
                    		Se déconnecter
                    	</a>
                    </td>
                </tr>
                <tr>
                    <td style="width: 161px;" class="tdredac">
                    	<form action='profile.php' method='POST'>
                    		<button class = "buttoncat" name="deleteaccount" type="submit" value="$user">Supprimer mon compte</button>
                    	</form>
                    </td>
                </tr>
            </tbody>
        </table>



        <table class="tabadmin">
            <tbody>
                <tr>
                    <td class="tdredac">
                        Thème à utiliser : 
                    </td>
                    <td class="tdredac">
                        <FORM action="profile.php" id="theme" method="POST">
                            <SELECT name="nom" size="1">
                            <OPTION value = "">Par défaut</OPTION>
                            <OPTION value = "1">Exotique
                            <OPTION value = "2">Nuageux
                            <OPTION value = "3">Améthyste
                            <OPTION value = "4">Chaleur
                            </SELECT>
                            <button type='submit' nom='theme'>Changer</button>
                        </FORM>
                    </td>
                </tr>
            </tbody>


    </body> 
</html>
