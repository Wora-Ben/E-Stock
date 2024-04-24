<?php
global $info;
global $error;
global $request;
include(dirname(__DIR__, 2) . '/frag-deb.html');
require(dirname(__DIR__, 3) . '/controller/Article.php');
require(dirname(__DIR__, 3) . '/controller/Fournisseur.php');
echo <<<EOT
<form action="" method="POST">
<input hidden value="$info->id_achat" name="idAchat">
    <div class="container">
        <div class="heading-text">
            <p>Achat</p>
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
                <select class="select-container" name="idArticle">
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
</td>
                <td><label>Quantite</label></td>
                <td><input type="text" name="quantite"
                           value="$info->quantite"></td>
    <tr>
                <td><label>Prix unitaire HT</label></td>
                <td><input type="text" name="prixUnitaire"
                           value="$info->prix_unitaire_ht"></td>
                <td><label>Fournisseur</label></td>
                <td>
                <select class="select-container" name="idFournisseur">
EOT;
$fournisseurs = Fournisseur::fournisseursInfos();
foreach ($fournisseurs as $fournisseur){
    if (isset($_POST['idFournisseur']) && $fournisseur->id_fournisseur == $_POST['idFournisseur']) {
        echo "<option value=\"$fournisseur->id_fournisseur\" selected>$fournisseur->raison_sociale_fournisseur</option>";
        continue;
    }
    echo <<<EOT
    <option value="$fournisseur->id_fournisseur">$fournisseur->raison_sociale_fournisseur</option>
EOT;

}
echo <<<EOT
                </select>
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
