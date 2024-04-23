<?php
global $info;
include(dirname(__DIR__, 2) . '/frag-deb.html');


echo <<<EOT
<div class="container container-message">
    <div class="error">
        <ul>
            <li>
                Vous voulez vraiment supprimer cet achat ? 
            </li>
            <li>
                Reference article : $info->reference_article
            </li>
            <li>
                Article : $info->designation_article
            </li>
            <li>
                Quantite : $info->quantite
            </li>
            <li>
                Prix : $info->prix_unitaire_ht
            </li>
            <li>
                Client : $info->raison_sociale_client
            </li>
            <li>
                Date : $info->date
            </li>
        </ul>
        <div class="error-btn-container">
           <form action="?confirmDelete" method="POST">
                <input name="idVente" type="hidden" value="{$info->id_vente}">
                <input name="confirmDelete" class="btn btn-supprimer" type="submit" value="Supprimer">
                <a class="btn cancel-btn" href="ventes.php">Annuler</a>
            </form>
        </div>
    </div>
</div>
EOT;


include(dirname(__DIR__, 2) . '/frag-fin.html');
?>