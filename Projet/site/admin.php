<?
/* Panel de l'administrateur
       Il permet de lier plus facilement les fonctions utilisables de l'administrateur 
*/
$titre = "Panel Rédacteur";
include 'header.php';
$user = Secu($_SESSION['user']);

if (!is_login())
{
    Redirect('/util/connexion.php');
}

if (!is_admin())
{
    Redirect('index.php');
}

?>

        <h2 class="hredac"> Bienvenue <?php echo $user; ?> sur le panel Admin </h2>
        <h3 class="hredac"> Que souhaitez vous faire ? </h3>
        <table class="tabadmin" style="width: 643px; height: 75px;">
            <tbody>
                <tr>
                    <td style="width: 161px;" class="tdredac"><a href=" admin/accmanage.php">Gérer les différents comptes</a></td>
                </tr>
                <tr>
                    <td style="width: 161px;" class="tdredac"><a href="admin/update_car.php">Gérer les cartes</a></td>
                </tr>
                <tr>
                    <td style="width: 161px;" class="tdredac"><a href="admin/manage_deckad.php">Gérer les decks</a></td>
                </tr>
                <tr>
                    <td style="width: 161px;" class="tdredac"><a href="admin/manage_catad.php">Gérer les catégories</a></td>
                </tr>
                <tr>
                    <td style="width: 161px;" class="tdredac"><a href="admin/add_categorie.php">Créer une nouvelle catégorie</a></td>
                </tr>
                <tr>
                    <td style="width: 161px;" class="tdredac"><a href="admin/manage_categories.php">Voir les demandes de nouvelles catégories</a></td>
                </tr>
                <tr>
                    <td style="width: 161px;" class="tdredac"><a href="admin/manage_complaints.php">Gérer les plaintes</a></td>
                </tr>
            </tbody>
        </table>


    </body> 
</html>
