<?
     /*
        La page permet aux admins de gérer les catégories 
        Le titre de la page: Gérer les catégories
        Ainsi que l'attribution du header pour la navbar et le fichier function qui est indispensable pour la connexion à la BDD
    */   
     $titre = "Gérer les Catégories";
     include '../header.php'; 


    if(!is_login()){
         Redirect('../util/connexion.php');
    }

    if(!is_admin()){
        Redirect('../index.php');
    } 
    
    // On récupère toutes les catégories non vérifies ou qui ont été désactiver
    $user = Secu($_SESSION['user']);
    $GetCategorie = $BDD->prepare("SELECT * FROM categories WHERE valide = 0");
    $GetCategorie->execute();
    $resultat_categorie = $GetCategorie->fetchAll();

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        // Pas besoin de récupéer les caractères spéciaux dans le nom du deck
        
        

        // On vérifie que le bouton supprimer a bien été selectionne
        if(isset($_POST['supprimer'])){
            $nom = (Secu($_POST['supprimer']));
            $data = $BDD->prepare('DELETE FROM categories WHERE nom = :nom');
            $data->bindParam(':nom', $nom);
            $data->execute();
            echo 'La catégorie a été supprimée';
            // Permet de rafraichir la page
            header("Refresh:0");
        }
        if(isset($_POST['update'])){
        	$nom = (Secu($_POST['update']));
            $data = $BDD->prepare('UPDATE categories SET valide = 1 WHERE nom = :nom');
            $data->bindParam(':nom', $nom);
            $data->execute();
            echo 'La catégorie a été ajoutée';
            header("Refresh:0");
        }
    }
?>





        <form action="manage_categories.php" method="POST">
            <table class="tabadmin" style="width: 30%; height: 75px;">
                <tbody>
                    <tr>
                        <td style="width: 60%;" class="tdredac">
                            Nom des catégories demandées
                        </td>
                        <td style="width: 20%;" class="tdredac">
                            Par : 
                        </td>
                        <td style="width: 40%;" class="tdredac">
                            Action :
                        </td>
                    </tr>
                           <!--
                 On séléctionnes toutes les catégories qui ont été validé par l'administrateur 
                Pour sélectionner uniquement celle qui sont correct
                On affiche avec foreach tout les nom de toutes les catégories non valides
                                        -->
                        <?php foreach ($resultat_categorie as $row){?>
                            <tr>
                                <td style="width: 60%;" class="tdredacq">
                                    <?echo htmlspecialchars_decode($row['nom']); ?>
                                </td>
                                <td style="width: 60%;" class="tdredacq">
                                    <?echo $row['auteur']; ?>
                                </td>
                                <td style="width: 40%;" class="tdredacq">
                                    <button name="update" type="submit" value="<?= $row['nom']; ?>" class="butredac" >Accepter la demande</button>
                                    <button name="supprimer" type="submit" value="<?= $row['nom']; ?>" class="butredac" >Supprimer la demande</button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tr>
                </tbody>
            </table>
        </form>
    </body> 
</html>
