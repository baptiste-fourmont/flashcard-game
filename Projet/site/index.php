<?php 
    $titre = "Accueil !";
    include 'header.php'; 
    /* 
        On utilise HTTP_HOST afin de pouvoir utiliser les rédirections peu importe si il est en local ou sur n'importe quelle site
        Par exemple pour tester le site si une personne l'installe sur sa machine il pourra directement être fonctionnel.        
    */
   
?>
        <form class="doagame">
            <p class="minimarge">
                <h1 style="text-align: center;color: black;margin-top: 5%;">
                    Bienvenue sur Quizoo
                </h1>
                <h4 style="text-align: center;color: black;">
                    Le but ? <br>
                    Répondre à 10 questions sur un thème donné en essayant d'obtenir un maximum de bonnes réponses afin de pouvoir renforcer sa culture personelle.<br>
                    Tu peux aussi prendre le choix de devenir rédacteur afin de partager tes connaissances avec les autres utilisateurs et contribuer au développement du site !<br><br><br>
                </h4>
                <h1 style="text-align: center;color: black;">
                     Qu'attends-tu pour nous rejoindre et relever le défi ?<br>
                     <a href="util/connexion.php" style="text-decoration: none;">Relever le défi</a>
                </h1>
            </p>
        </form>
	</body>
</html>