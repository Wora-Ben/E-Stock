<?php
global $infos;
global $valeur_stock;
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
            <form action="etats.php" method="GET">
                <div class="box-utils">
                    <div class="search-box">
                        <div class="search-logo"><img src="/E-Stock/assets/images/search.svg" alt=""></div>
                        <input name="searchValue" type="text" class="search-input" placeholder="Chercher">
                    </div>
                    <div class="buttons">
                        <input name="search" type="submit" class="btn search" value="Rechercher">
                    </div>
                </div>
            </form>
        </div>
        <div class="info-box">
           <span class="text">Valeur du stock : $valeur_stock €</span>
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
                        <th>Référence article</th>
                        <th>Désignation article</th>
                        <th>Quantite actuel</th>
                        <th>CMUP</th>
                        <th>Valeur de stock</th>
                    </tr>
                </thead>
                <tbody>
EOT;

if ($infos) {
    foreach (array_chunk($infos, 15)[$currentPage - 1] as $info) {
        echo "<tr><td>$info->reference_article</td><td>$info->designation_article</td><td>$info->quantite_actuel</td><td>$info->cmup</td><td>$info->valeur_stock</td>
            </tr>";
    }
}

echo <<<EOT
                </tbody>
            </table>
        </div>
            <navbar class="pagination">
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
            </navbar>
        </div>
    </div>
</div>
EOT;

include(dirname(__DIR__, 2) . '/frag-fin.html');
?>