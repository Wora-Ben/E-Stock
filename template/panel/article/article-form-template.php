<?php
global $info;
global $error;
global $request;
include(dirname(__DIR__, 2) . '/frag-deb.html');

echo <<<EOT
<form action="" method="POST">
<input hidden value="$info->id_article" name="idArticle">
    <div class="container">
        <div class="heading-text">
            <p>Article</p>
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
                <td><label>Référence article</label></td>
                <td><input type="text" name="refArticle" id="refAr"
                           value="$info->reference_article"></td>
                <td><label>Designation article</label></td>
                <td><input type="text" name="desArticle" id="desAr"
                           value="$info->designation_article"></td>
            </tr>
            <tr>
                <td><label>Prix article</label></td>
                <td><input type="text" name="prixArticle" id="prixAr"
                           value="$info->prix_achat_unitaire_HT"></td>
               <td></td>
               <td></td>
            </tr>
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
