<?   
    /*
        On vérifie que chaque personne à bien une session qui se lance 
    */

    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }
    
    /*
        setlocale permet directement de récupérer l'horaire francaise si on veut par exemple enregistré l'heure à laquelle l'utilisateur s'est inscrit sur le site etcc avec timestamp
        Nous avons fait le choix d'utiliser PDO plutôt que MYSQLI car il est plus simple à utiliser et on peut prévenir les injections XSS plus facilement avec bindParam ou bien bindValue 
        On prépare ainsi les réqûtes à l'avance et chaque variable rentré sera vérifié avec la fonction Secu() qui permettra d'éviter les injections connu telles '=1 ou bien d'autres attaques similaire 

        Modifier vos informations de la DB juste en dessous
        Merci
    */ 
    setlocale (LC_TIME, 'fr_FR.utf8','fra');
    define("hostdb", "localhost");
    define("userdb", "root");
    define("userpassword", "rootentrain26");
    define("namedb", "project");
   
    try{
       
        $BDD = new PDO("mysql:host=".hostdb.";dbname=".namedb."", "".userdb."", "".userpassword."");
        $BDD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            echo($e->getMessage());
        }

    /*
        Nous avions remarqué que lors que l'on faisait trop de redirection avec header on avait une erreur comme quoi on avait effectué trop de redirection.
        header_sent() est une fonction déjà existante qui permet de vérifier si les headers se sont bien lancé.
        En créant cette foncton on peut plus facilement prévenir contre cette erreur trop de redirection effectué
    */
    function Redirect($var){
        if(headers_sent()){
            exit();
        }
        else{
            header("Location:".$var);
            exit();
        }
    }
    // Permet de vérifier si la session existe sans unotificied variable
    function is_login(){
        if (!isset($_SESSION['user']) || $_SESSION['user'] == ''){
            return false;
        }else{
            return true;
        }
    }
    
    /*
        Cette fonction sécu a été construite afin de se prévenir contre les injections XSS.
        Avec htmlentites on verifie les caractères éligibles puis htmlspecialchar permet de convertir les caractère spéciaux et trim permet de supprimer les espaces
    */
    function Secu($var){
        // ERREUR SOUS CHROME TENDU QUE FIREFOR n'a pas de problème avec les apostrophes pq je sais pas
		//$var = htmlentities(htmlspecialchars(trim($var),ENT_QUOTES, "UTF-8"));
        $var = $var;
		return $var;
    }
    
    /*
        On l'utilise pour pouvoir voir si la longueur du nombre correspond bien à celle voulu
    */
    function longueur($var,$nombre){
        $num_length = strlen((string)$var);
         if($num_length == $nombre) {
             return true;
        }
    }
    
    /*
        Cette fonction directement trouvé sur le wiki de PHP permet de vérifier si la session a bien été lancé mais nous avons préféré utilisé
        
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }
        
        Qui est plus conventionel
    */
    function is_session_started()
    {
    if ( php_sapi_name() !== 'cli' ) {
        if ( version_compare(phpversion(), '5.4.0', '>=') ) {
            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
        } else {
            return session_id() === '' ? FALSE : TRUE;
        }
    }
    return FALSE;
    }


    /*
        UserType correspond aux grades de chaque utilisateur
        1: user
        2: redac
        3: admin

       Ces fonctions nous permettent de vérifier si chaque personne peut bien accèder à une page.
       isset($_SESSION['user']) permet de nous prévenir contre les erreur unotificied
    */
    function is_admin(){
        if($_SESSION['UserType'] == 3 && isset($_SESSION['user'])){
            return true;
        }   
        return false;
    }
    
    function is_redac(){
        if($_SESSION['UserType'] == 2 && isset($_SESSION['user'])){
            return true;
        }   
        return false;
    }

     function is_user(){
        if($_SESSION['UserType'] == 1 && isset($_SESSION['user'])){
            return true;
        }   
        return false;
     }

    function check_card(){  
       global $BDD,$rep,$que;
       $rep = Secu($_POST['rep']);
       $que = Secu($_POST['que']);
       // Pas besoin de check la question elle sera déjà 
       if(empty($rep)){
            die('Vous devez entré une réponse.');
        }
    }

    function usertype(){
        if(is_admin() == true){
            return "Administrateur";
        }
        elseif (is_redac() == true) {
            return "Redacteur";
        }
        else{
            return "Utilisateur";
        }
    }

    function deleteMyAccount(){
        if(isset($user) && is_login()){
            $user = Secu($_SESSION['user']);
            $data = $BDD->prepare('DELETE FROM users WHERE user = :user');
            $data->bindParam(':user', $user);
            $data->execute();
            session_unset();
            session_destroy();
            Redirect('../util/connexion.php');
            echo 'Votre compte a été supprimé';
            exit();
        }
    }




?>