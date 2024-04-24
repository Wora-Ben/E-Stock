<?php
global $info;
global $error;
global $request;
include(dirname(__DIR__, 2) . '/frag-deb.html');
require(dirname(__DIR__, 3) . '/controller/Article.php');
require(dirname(__DIR__, 3) . '/controller/Client.php');
echo <<<EOT
<form action="" method="POST">
<input hidden value="$info->id_vente" name="idVente">
    <div class="container">
        <div class="heading-text">
            <p>Vente</p>
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
    <div class="container form-container">
        <table>
            <tbody>
            <tr>
                <td><label>Article</label></td>
                <td>
                <select name="idArticle" class="select-container">
EOT;
$articles = Article::articlesInfos();
foreach ($articles as $article){
    if (isset($_POST['idArticle']) && $article->id_article == $_POST['idArticle']) {
        echo "<option value=\"$article->id_article\" selected>$article->designation_article</option>";
        continue;
    }
echo <<<EOT
    <option value="$article->id_article">$article->designation_article</option>
EOT;

}

echo <<<EOT
                </select>
                <td><label>Quantite</label></td>
                <td><input type="text" name="quantite"
                           value="$info->quantite"></td>
                <td><label>Mode de livraison</label></td>
                <td>
                    <select name="modeLivraison" class="select-container">
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
                    </td>
    </tr>
    <tr>
                <td><label>Prix unitaire HT</label></td>
                <td><input type="text" name="prixUnitaire"
                           value="$info->prix_unitaire_ht"></td>
                <td><label>Fournisseur</label></td>
                <td>
                <select name="idClient" class="select-container">
EOT;
$clients = Client::clientsInfos();
foreach ($clients as $client){
    if (isset($_POST['idClient']) && $client->id_client == $_POST['idClient']) {
        echo "<option value=\"$client->id_client\" selected>$client->raison_sociale_client</option>";
        continue;
    }
    echo <<<EOT
    <option value="$client->id_client">$client->raison_sociale_client</option>
EOT;

}
echo <<<EOT
                </td>
                <tr>
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
