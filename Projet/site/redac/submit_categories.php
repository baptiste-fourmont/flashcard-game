<?
/*
        La page pour envoyer une demande au admin une catégorie
        Le titre de la page: Ajouter une catégorie de carte
        Ainsi que l'attribution du header pour la navbar et le fichier function qui est indispensable pour la connexion à la BDD
*/
$titre = "Ajouter une catégorie de carte";
include '../header.php';

if (!is_login())
{
    Redirect('../util/connexion.php');
}

if (!is_redac())
{
    Redirect('../index.php');
}

// On récupère toute les catégories valide qu'on stocke dans $resultat_categories
$user = Secu($_SESSION['user']);
$GetCategories = $BDD->prepare("SELECT * FROM categories WHERE valide = 1");
$GetCategories->execute();
$resultat_categories = $GetCategories->fetchAll();

// On vérifie que la méthode est POST
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $nom = Secu($_POST['nom']);
    $valide = true;

    // on vérifie si les variable utilisé ne sont pas vides
    if (empty($nom))
    {
        die('Vous devez insérer une Catégorie.');
        $valide = false;
    }

    // On se prépare a compter si le nom de la catégorie existe déjà pour éviter de dupliquer la catégorie
    $data = $BDD->prepare('SELECT * FROM categories WHERE nom = :nom');
    $data->bindParam(':nom', $nom);
    $data->execute();
    // On met 0 pour non-valide et 1 pour validé
    $set_validite = 0;
    // On vérifie si il n'y a pas déjà de catégorie existante du même nom pour la dubliquer
    if (!($data->rowCount() > 0))
    {
        if ($valide)
        {
            $add_cad = $BDD->prepare("INSERT INTO categories (nom,valide,auteur) VALUES (:nom, :valide, :auteur )");
            $add_cad->bindParam(':nom', $nom);
            $add_cad->bindParam(':valide', $set_validite);
            $add_cad->bindParam(':auteur', $user);
            $add_cad->execute();

            echo "La Catégorie a été demandée merci de votre aide !";
        }
        else
        {
            echo "Veuillez insérer une Catégorie s'il vous plait";
        }
    }
    else
    {
        echo "La catégorie existe déjà";
    }
}

?>


    <form action="submit_categories.php" method="POST">
          <table class="tabadmin" style="width: 25%; height: 75px;">
              <tbody>
                  <tr>
                      <td style="width: 50%;" class="tdredac">
                        Nom de la catégorie voulue : 
                      </td>
                      <td style="width: 50%;" class="tdredac">
                      Action
                    </td>
                  </tr>
                  <tr>
                    <td style="width: 60%;" class="tdredacq">
                      <input type="text" name="nom" required="required">
                    </td>
                    <td style="width: 40%;" class="tdredacq">
                      <input type="submit" class="boutgray" value="Envoyer">
                    </td>
                  </tr>

                  <tr>
                    <td style="width: 100%;" class="tdredac">
                      Rappel des catégories existantes
                    </td>
                  </tr>
                  <tr>
                      <!--
                 On séléctionnes toutes les catégories qui ont été validé par l'administrateur 
                Pour sélectionner uniquement celle qui sont correct
                On affiche avec foreach tout les nom de toutes les catégories valides
                                        -->
                    <?php foreach ($resultat_categories as $row)
{ ?>
                            <tr>
                          <td style="width: 100%;" class="tdredacq">
                            <? echo htmlspecialchars_decode($row['nom']); ?>
                          </td>
                            </tr>
                      <?php
} ?>
              
              </tbody>
          </table>
        </form>
    </body> 
</html>
