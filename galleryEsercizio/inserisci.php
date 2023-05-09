<html>
    <head>
        <title>.: pwlsGalleryinserisci :.</title>
    </head>
    <body>
    <?php
    // includi il file di configurazione e le funzioni necessarie
    require_once ("config.php");
    require_once ("funzioni.php");

    // controlla se il file è stato caricato correttamente
    if (!isset($_FILES["nomefile"])) {
        die("File non ricevuto\n");
    }

    // ottieni informazioni sul file caricato
    $tmp_nome = $_FILES["nomefile"]["tmp_name"];
    $tipo = $_FILES["nomefile"]["type"];
    $nome = $_FILES["nomefile"]["name"];

    // controlla se il tipo e il formato del file sono supportati
    if (!controllaTipo($tipo) || !controllaFormato($nome)) {
        die("File di tipo sconosciuto\n");
    }

    // sposta il file nella directory delle immagini
    if (move_uploaded_file($tmp_nome, DIR_IMMAGINI . "/" . $nome)) {
        // mostra un messaggio di conferma con un link all'indice del sito
        echo "<p>Inserimento effettuato, torna all'<a href=\"indice.php\">indice</a></p>\n";
    } else {
        // mostra un messaggio di errore se lo spostamento del file non ha successo
        echo "<p>Non sono riuscito a spostare il file, controlla i permessi</p>\n";
    }
?>

    </body>
</html>