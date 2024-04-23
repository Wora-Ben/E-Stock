<?php
global $info;
global $error;
global $request;
include(dirname(__DIR__, 2) . '/frag-deb.html');

echo <<<EOT
<form action="" method="POST">
<input hidden value="$info->id_client" name="idClient">
    <div class="container">
        <div class="heading-text">
            <p>Nouveau client</p>
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
                <td><label>Raison sociale client</label></td>
                <td><input type="text" name="raisonScClient" 
                           value="$info->raison_sociale_client"></td>
                <td><label>N° SIREN</label></td>
                <td><input type="text" name="nSirenClient"
                           value="$info->n_siren"></td>
                <td><label>Siège social</label></td>
                <td><input type="text" name="adrClient"
                           value="$info->adresse_client"></td>
                <td><label>Téléphone</label></td>
                <td><input type="text" name="telClient"
                           value="$info->telephone_client"></td>
            <tr>
                <td><label>Email adresse</label></td>
                <td><input type="text" name="emailClient"
                           value="$info->email_client"></td>
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
                <td><label>Mode de livraison</label></td>

                <td>
                    <select name="modeLivraison">
                    <option value="Charge_Client"
EOT;
if ($info->mode_livraison == "Charge_Client") {
    echo "selected";
}
echo <<<EOT
>Charge de client</option>
                    <option value="Notre_Charge" 
EOT;
if ($info->mode_livraison == "Notre_Charge") {
    echo "selected";
}
echo <<<EOT
>Notre charge</option>
                    </select>
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
