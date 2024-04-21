<?php
global $refArticle, $desArticle, $idArticle;
include(dirname(__DIR__, 2) . '/frag-deb.html');


echo <<<EOT
<div class="container container-message">
    <div class="error">
        <ul>
            <li>
                Vous voulez vraiment supprimer l'article : 
            </li>
            <li>
                Référence : $refArticle
            </li>
            <li>
                Désignation : $desArticle
            </li>
        </ul>
        <div class="error-btn-container">
           <form action="?aa" method="POST">
                <input name="idArticle" type="hidden" value="{$idArticle}">
                <input name="confirmDelete" class="btn btn-supprimer" type="submit" value="Supprimer l'article">
                <a class="btn cancel-btn" href="articles.php">annuler</a>
            </form>
        </div>
    </div>
</div>
EOT;


include(dirname(__DIR__, 2) . '/frag-fin.html');
?>