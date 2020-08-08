<?
     /*
        La page permet aux admins de gérer les plaintes
        Le titre de la page: Gérer les plaintes
        Ainsi que l'attribution du header pour la navbar et le fichier function qui est indispensable pour la connexion à la BDD
    */   
     $titre = "Gérer les plaintes";
     include '../header.php'; 


    if(!is_login()){
         Redirect('../util/connexion.php');
    }

    if(!is_admin()){
        Redirect('../index.php');
    } 
    
    // On récupère toutes les demandes de plaintes
    $user = Secu($_SESSION['user']);
    $GetComplaint = $BDD->prepare("SELECT * FROM reports");
    $GetComplaint->execute();
    $resultat_complaint = $GetComplaint->fetchAll();

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        // Pas besoin de récupéer les caractères spéciaux dans le nom du deck
              

        // On vérifie que le bouton supprimer a bien été selectionne
        if(isset($_POST['supprimer'])){
            $nom = (Secu($_POST['supprimer']));
            $data = $BDD->prepare('DELETE FROM reports WHERE id = :id');
            $data->bindParam(':id', $nom);
            $data->execute();
            echo 'La catégorie a été supprimée';
            // Permet de rafraichir la page
            header("Refresh:0");
        }
    }
?>





        <form action="manage_complaints.php" method="POST">
            <table class="tabadmin" style="width: 30%; height: 75px;">
                <tbody>
                    <tr>
                        <td style="width: 60%;" class="tdredac">
                            Plainte
                        </td>
                        <td style="width: 20%;" class="tdredac">
                            Deck problèmatique 
                        </td>
                        <td style="width: 40%;" class="tdredac">
                            Auteur de la plainte
                        </td>
                        <td style="width: 40%;" class="tdredac">
                            Action :
                        </td>
                    </tr>
                        <?php foreach ($resultat_complaint as $row){?>
                            <tr>
                                <td style="width: 60%;" class="tdredacq">
                                    <?echo $row['probleme']; ?>
                                </td>
                                <td style="width: 60%;" class="tdredacq">
                                    <?echo $row['ndd']; ?>
                                </td>
                                <td style="width: 60%;" class="tdredacq">
                                    <?echo $row['auteur']; ?>
                                </td>
                                <td style="width: 40%;" class="tdredacq">
                                    <button name="supprimer" type="submit" value="<?= $row['id']; ?>" class="butredac" >Supprimer la plainte</button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tr>
                </tbody>
            </table>
        </form>
    </body> 
</html>
