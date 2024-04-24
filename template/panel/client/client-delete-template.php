<?php
global $info;
include(dirname(__DIR__, 2) . '/frag-deb.html');


echo <<<EOT
<div class="container container-message">
    <div class="error">
        <ul>
            <li>
                Vous voulez vraiment supprimer cette client ? 
            </li>
            <li>
                Raison sociale : $info->raison_sociale_client
            </li>
            <li>
                N° SIREN : $info->n_siren
            </li>
        </ul>
        <div class="error-btn-container">
           <form action="?confirmDelete" method="POST">
                <input name="id_client" type="hidden" value="{$info->id_client}">
                <input name="confirmDelete" class="btn btn-supprimer" type="submit" value="Supprimer l'article">
                <a class="btn cancel-btn" href="articles.php">annuler</a>
            </form>
        </div>
    </div>
</div>
EOT;


include(dirname(__DIR__, 2) . '/frag-fin.html');
?>