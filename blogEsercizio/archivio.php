<?php
require("config.php");
require("funzioni.php");
?>

<html>
    <head> 
        <title> <?php echo $TITOLO; ?> </title>
        <link rel="stylesheet" type="text/css" href="stile.php" />
    </head>
    <body>
        <div id="intestazione">
        <h1><?php echo $TITOLO; ?></h1>
        <h2>di <?php echo $UTENTE; ?></h2>
            <div id="menu">
            <a href="indice.php">Home</a>
            <a href="inserisci.php">Inserisci</a>
            <a href="archivio.php">Archivio</a>
            </div> 
        </div> 
        <div id="blog">
        <h1>Archivio dei post</h1>

        <?php
        $numero = numeroPost();
        $pagine = ceil($numero / $POSTPERPAGINA); // arrotondamentro al sup
        if (!isset($_GET["pagina"]))
            $pagina = 1;
        else
            $pagina = $_GET["pagina"];
            // stampo i post $POSTPERPAGINA alla volta, se ce ne sono doi piu genero nuova pagina
            $contenuto = leggi( numeroPost() - (($pagina -1) * $POSTPERPAGINA), 
                                $POSTPERPAGINA);
            // stampo i $contenuto: array dei post
            if (count($contenuto) > 0) {
                foreach ($contenuto as $post) {
                    echo "<div class=\"post\">\n<h3>", $post['Titolo']['S'], "</h3>\n";
                    echo "<p>", $post['Testo']['S'], "</p>\n";
                    echo "<p class=\"info\">Pubblicato il: ", $post['DataPubblicazione']['S'], " da ", $UTENTE, "</p>\n</div>\n";
                }
            }
            

        echo "<p>Pagine: ";
        for ($i = 1; $i <= $pagine; $i++)
            echo "<a href=\"", $_SERVER["PHP_SELF"],"?pagina=", $i, "\">", $i, "</a> ";
            echo "</p>\n";
        ?>
        </div>
        <hr/>
    </body>
</html>

