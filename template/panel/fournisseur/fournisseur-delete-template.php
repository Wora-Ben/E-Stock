<?php
global $info;
include(dirname(__DIR__, 2) . '/frag-deb.html');


echo <<<EOT
<div class="container container-message">
    <div class="error">
        <ul>
            <li>
                Vous voulez vraiment supprimer ce fournisseur ? 
            </li>
            <li>
                Raison sociale : $info->raison_sociale_fournisseur
            </li>
            <li>
                N° SIREN : $info->n_siren
            </li>
        </ul>
        <div class="error-btn-container">
           <form action="?confirmDelete" method="POST">
                <input name="idFournisseur" type="hidden" value="{$info->id_fournisseur}">
                <input name="confirmDelete" class="btn btn-supprimer" type="submit" value="Supprimer">
                <a class="btn cancel-btn" href="fournisseurs.php">Annuler</a>
            </form>
        </div>
    </div>
</div>
EOT;


include(dirname(__DIR__, 2) . '/frag-fin.html');
?>