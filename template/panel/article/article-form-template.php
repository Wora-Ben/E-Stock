<?php
global $idArticle;
global $refArticle;
global $desArticle;
global $prixArticle;
global $request;
global $error;
include(dirname(__DIR__, 2) . '/frag-deb.html');

echo <<<EOT
<form action="" method="POST">
<input hidden value="$idArticle" name="idArticle">
    <div class="container">
        <div class="heading-text">
            <p>Nouvel article</p>
        </div>
EOT;
if ($error) {
    echo "<div class=\"error\"><ul>";
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
                <td><label>Référence article</label></td>
                <td><input type="text" name="refArticle" id="refAr"
                           value="$refArticle"></td>
                <td><label>Designation article</label></td>
                <td><input type="text" name="desArticle" id="desAr"
                           value="$desArticle"></td>
            </tr>
            <tr>
                <td><label>Prix article</label></td>
                <td><input type="text" name="prixArticle" id="prAr"
                           value="$prixArticle"></td>
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
