<?php
global $infos;
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
                <img class="panel-info-icon" src="/E-Stock/assets/images/quantity.svg" alt="">
                <span class="heading-text">Liste d'articles</span>
            </div>
                <form action="articles.php" method="GET">
                    <div class="box-utils">
                        <div class="search-box">
                            <div class="search-logo"><img src="/E-Stock/assets/images/search.svg" alt=""></div>
                            <input name="searchValue" type="text" class="search-input" placeholder="Chercher">
                        </div>
                        <div class="buttons">
                            <input name="search" type="submit" class="btn search" value="Rechercher">
                            <span class="btn addBtn">
                                <a href="?addArticle">+ Ajouter article</a>
                            </span>
                        </div>
                    </div>
                </form>
        </div>
EOT;
if (isset($_GET['newArticle'])) {
    echo <<<EOT
    <div class="notification">
    <ul>
        <li>
            Nouvel article a été ajouté avec succès
        </li>
    </ul>
    </div>
EOT;
}
if (isset($_GET['articleEdited'])) {
    echo <<<EOT
    <div class="notification">
    <ul>
        <li>
            L'article a été modifier avec succès 
        </li>
    </ul>
    </div>
EOT;
}
if (isset($_GET['articleDeleted'])) {
    echo <<<EOT
    <div class="notification">
    <ul>
        <li>
            L'article a été supprimer avec succès 
        </li>
    </ul>
    </div>
EOT;
}
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
                        <th>Référence</th>
                        <th>Désignation</th>
                        <th>Prix unitaire</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
EOT;

if ($infos) {
    foreach (array_chunk($infos, 15)[$currentPage - 1] as $info) {
        echo "<tr><td>$info->reference_article</td><td>$info->designation_article</td><td>$info->prix_achat_unitaire_HT</td>
        <td>
        <form action='?editArticle' method='POST'>
            <input type='image' src='/E-Stock/assets/images/edit.svg' alt='edit-icon'>
            <input name='idArticle' value='$info->id_article' type='hidden'>
        </form>
        <form action='?deleteArticle' method='POST'>
            <input type='image' src='/E-Stock/assets/images/delete.svg' alt='delete-icon'>
            <input name='idArticle' value='$info->id_article' type='hidden'>
        </form>        
        </td></tr>";
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