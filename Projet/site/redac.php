<?
/* Panel du Rédacteur
 Il permet de lier plus facilement les fonctions utilisables du rédacteur */
$titre = "Panel Rédacteur";
include 'header.php';
$user = Secu($_SESSION['user']);

if (!is_login())
{
    Redirect('/util/connexion.php');
}

if (!is_redac())
{
    Redirect('index.php');
}

?>
        <h2 class="hredac"> Bienvenue <?php echo $user; ?> sur le panel rédacteur </h2>
        <h3 class="hredac"> Que souhaitez vous faire ? </h3>
        <table class="tabadmin" style="width: 643px; height: 75px;">
            <tbody>
                <tr>
                    <td style="width: 161px;" class="tdredac"><a href="redac/update_car.php">Gérer mes cartes</a></td>
                </tr>
                <tr>
                    <td style="width: 161px;" class="tdredac"><a href="redac/add_card.php">Créer une carte</a></td>
                </tr>
                <tr>
                    <td style="width: 161px;" class="tdredac"><a href="redac/manage_deck.php">Gérer mes decks</a></td>
                </tr>
                <tr>
                    <td style="width: 161px;" class="tdredac"><a href="redac/creerdeck.php">Créer un deck</a></td>
                </tr>
                <tr>
                    <td style="width: 161px;" class="tdredac"><a href="redac/submit_categories.php">Faire une demande de nouvelle catégorie</a></td>
                </tr>
            </tbody>
        </table>


    </body> 
</html>
