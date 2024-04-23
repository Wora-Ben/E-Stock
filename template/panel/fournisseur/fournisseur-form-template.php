<?php
global $info;
global $error;
global $request;
include(dirname(__DIR__, 2) . '/frag-deb.html');

echo <<<EOT
<form action="" method="POST">
<input hidden value="$info->id_fournisseur" name="idFournisseur">
    <div class="container">
        <div class="heading-text">
            <p>Nouveau fourinsseur</p>
        </div>
EOT;
if ($error) {
    echo "<div class=\"error-notification\"><ul>";
    foreach ($error as $value) {
        echo "<li>$value</li>";
    }
    echo "</ul></div>";
}

echo <<<EOT
    </div>
    <div class="container">
        <table>
            <tbody>
            <tr>
                <td><label>Raison sociale fourinsseur</label></td>
                <td><input type="text" name="raisonScFournisseur" 
                           value="$info->raison_sociale_fournisseur"></td>
                <td><label>N° SIREN</label></td>
                <td><input type="text" name="nSirenFournisseur"
                           value="$info->n_siren"></td>
                <td><label>Siège social</label></td>
                <td><input type="text" name="adrFournisseur"
                           value="$info->adresse_fournisseur"></td>
                <td><label>Téléphone</label></td>
                <td><input type="text" name="telFournisseur"
                           value="$info->telephone_fournisseur"></td>
            <tr>
                <td><label>Email adresse</label></td>
                <td><input type="text" name="emailFournisseur"
                           value="$info->email_fournisseur"></td>
                <td><label>Mode de paiement</label></td>
                <td>
                    <select name="modePaiement">
                    <option value="Cheque"
EOT;
if ($info->mode_paiement == "Cheque") {
    echo "selected";
}
echo <<<EOT
>Chéque</option>
                    <option value="Espece" 
EOT;
if ($info->mode_paiement == "Espece") {
    echo "selected";
}
echo <<<EOT
>Espèce</option>
                    </select>
                </td>
                <td><label>Délai de paiement</label></td>
                <td><input type="text" name="delaiPaiement"
                           value="$info->delai_paiement"></td>
                <td><label>Nom interlocuteur</label></td>
                <td><input type="text" name="nomInterlocuteur"
                           value="$info->nom_interlocuteur"></td>

                </tr>
                <tr>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="1" class="submit-tr"><input type="submit" name="$request" class="btn btn-submit" value="Enregistrer"></td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
EOT;
include(dirname(__DIR__, 2) . '/frag-fin.html');
?>
