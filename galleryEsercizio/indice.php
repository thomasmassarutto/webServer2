<?php
require_once("config.php");
require_once("funzioni.php");
?>
<html>
    <head>
        <title>.: pwlsGalleryIndice :.</title>
    </head>
    <body>
        <h1>pwlsGallery: indice delle fotografie</h1>

        <?php

        $lista_file= caricaDirectoryDaS3('tommygallerybucket');;

        if(count($lista_file) > 0) {

            echo "<table>\n", "\t<tr>\n","\t\t<td>", generaLinkImmagineDaS3(0, $lista_file[0], 'tommygallerybucket'), "</td>\n";

              for ($i = 1; $i < count($lista_file); $i++) {

                if($i % $immagini_per_riga == 0)
                    echo "\t</tr>\n\t<tr>\n";

                echo "\t\t<td>", 
                generaLinkImmagineDaS3($i, $lista_file[$i], 'tommygallerybucket'), "</td>\n";
                }

                echo "\t</tr>\n </table>\n";
                } else {

                echo "\t<p>Non &egrave; presente alcuna immagine</p>\n";
                }
        ?>
                <hr/>
        <?php
        include("pie_pagina.php");
        ?>
    </body>
</html>