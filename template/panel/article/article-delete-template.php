<?php
global $info;
include(dirname(__DIR__, 2) . '/frag-deb.html');


echo <<<EOT
<div class="container container-message">
    <div class="error">
        <ul>
            <li>
                Vous voulez vraiment supprimer l'article : 
            </li>
            <li>
                Référence : $info->reference_article
            </li>
            <li>
                Désignation : $info->designation_article
            </li>
        </ul>
        <div class="error-btn-container">
           <form action="?confirmDelete" method="POST">
                <input name="idArticle" type="hidden" value="{$info->id_article}">
                <input name="confirmDelete" class="btn btn-supprimer" type="submit" value="Supprimer l'article">
                <a class="btn cancel-btn" href="articles.php">annuler</a>
            </form>
        </div>
    </div>
</div>
EOT;


include(dirname(__DIR__, 2) . '/frag-fin.html');
?>