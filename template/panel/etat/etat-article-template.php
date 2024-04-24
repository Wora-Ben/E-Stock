<?php
global $info;
global $infos;
global $valeur_stock;
global $error;
include(dirname(__DIR__, 2) . '/frag-deb.html');

$pages = 0;

//page number

if ($infos) {
    $pages = ceil(count($infos) / PAGE_MAX_ROWS);
}

if (isset($_GET['currentPage'])) {
    $currentPage = $_GET['currentPage'];
} else {
    $currentPage = 1;
}

if ($currentPage > $pages) {
    $currentPage = $pages - 1;
}

echo <<<EOT
<div class="container">
    <div class="box">
        <div class="head-box">
            <div class="title-box">
                <span class="heading-text">État général du stock</span>
            </div>
            <form action="" method="GET">
                <div class="box-utils">
                    <div>
                         <select class="select-container" name="idArticle">
EOT;
$articles = Article::articlesInfos();
foreach ($articles as $article){
    if (isset($_GET['idArticle']) && $article->id_article == $_GET['idArticle']) {
        echo "<option value=\"$article->id_article\" selected>$article->designation_article</option>";
        continue;
    }
    echo <<<EOT
    <option value="$article->id_article">$article->designation_article</option>
EOT;

}

echo <<<EOT
                </select>
                </div>
                    <div class="buttons">
                        <input name="etatArticle" type="submit" class="btn search" value="Valider">
                    </div>
                </div>
            </form>
        </div>
        <div class="info-box">
           <span class="text">Référence de l'article : $info->reference_article</span>
           <span class="text">Désignation de l'article : $info->designation_article</span>
           <span class="text">Quantité de l'article : $info->quantite</span>
        </div>
EOT;
if (isset($error)) {
    echo "<div class=\"error-notification\" ><ul>";
    foreach ($error as $value) {
        echo "<li>$value</li>";
    }
    echo "</ul></div>";
}
echo <<<EOT
        <div class="box-content">
            <table>
                <thead>
                    <tr>
                        <th>Opération</th>
                        <th>Quantite</th>
                        <th>Prix</th>
                        <th>Date</th>
                        <th>Fournisseur / Client</th>
                    </tr>
                </thead>
                <tbody>
EOT;

if ($infos) {
    foreach (array_chunk($infos, 15)[$currentPage - 1] as $info) {
        echo "<tr><td>$info->operation</td><td>$info->quantite</td><td>$info->prix</td><td>$info->date_operation</td><td>$info->person</td>
            </tr>";
    }
}

echo <<<EOT
                </tbody>
            </table>
        </div>
            <div class="pagination">
                <ul class="pagination">
EOT;

for ($i = 1; $i <= $pages; $i++) {
    if ($currentPage == $i) {
        echo "<li class='currentPage'><a href='?currentPage=$i'>$i</a></li>";
        continue;
    }
    echo "<li><a href='?currentPage=$i'>$i</a></li>";
}

echo <<<EOT
            </ul>
        </div>
    </div>
</div>
EOT;

include(dirname(__DIR__, 2) . '/frag-fin.html');
?>