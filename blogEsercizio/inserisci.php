<?php
require_once("config.php"); 
require_once("funzioni.php");
?>
<html>
    <head>
        <title> <?php echo $TITOLO?></title>
        <link rel="stylesheet" type="text/css" href="stile.php" />
    </head>
    
    <body>
        <?php if ($_SERVER["REQUEST_METHOD"] == "GET") {
        ?>
        <!--FORM-->
        <h1>Invia un post</h1>
            <form method="post" <?php echo "action=\"", $_SERVER["PHP_SELF"], "\""; ?> >
            Username: <input type="text" name="utente" size="10"/>
            Password: <input type="password" name="password" size="10"/> <br/> 
            <hr/>
            Titolo: <input type="text" name="titolo" size="50"/> <br/>
            Contenuto: <br/>
            <textarea name="contenuto" rows="10" cols="60"> 
            </textarea> 
            <br/>
            <input type="submit" value="Pubblica"/>
    </form>

        <?php
            } else { // la pagina è stata chiamata con il POST
                if( !utenteValido($_POST["utente"], $_POST["password"])) { 
        ?> 
                    <h2>Errore</h2>
                    <p>Non sei autorizzato all’inserimento. Torna al <a href="indice.php">blog</a>.</p>
        <?php 
                } else {
                    registra($_POST["titolo"], $_POST["contenuto"]);
                    echo "<p>Il post &egrave; stato pubblicato. Torna al <a href=\"indice.php\">Blog</a>.</p>";
                }
            }
        ?>
    </body>
</html>