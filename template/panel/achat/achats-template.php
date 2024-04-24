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
                <img class="panel-info-icon" src="/E-Stock/assets/images/value_import_goods.svg" alt="fournisseur icon">
                <span class="heading-text">Détail des achats</span>
            </div>
                <form action="achats.php" method="GET">
                    <div class="box-utils">
                        <div class="search-box">
                            <div class="search-logo"><img src="/E-Stock/assets/images/search.svg" alt="search-btn"></div>
                            <input name="searchValue" type="text" class="search-input" placeholder="Chercher">
                        </div>
                        <div class="buttons">
                            <input name="search" type="submit" class="btn search" value="Rechercher">
                            <span class="btn addBtn">
                                <a href="?addAchat">+ nouvel achat</a>
                            </span>
                        </div>
                    </div>
                </form>
        </div>
EOT;
if (isset($_GET['newAchat'])) {
    echo <<<EOT
    <div class="notification">
    <ul>
        <li>
            Nouvel achat a été ajouté avec succès
        </li>
    </ul>
    </div>
EOT;
}
if (isset($_GET['AchatDeleted'])) {
    echo <<<EOT
    <div class="notification">
    <ul>
        <li>
            L'achat a été supprimer avec succès 
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
                        <th>Référence article</th>
                        <th>Designation article</th>
                        <th>Quantite</th>
                        <th>Prix unitaire HT</th>
                        <th>Fournisseur</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
EOT;

if ($infos) {
    foreach (array_chunk($infos, 15)[$currentPage - 1] as $info) {
        echo "<tr><td>$info->reference_article</td><td>$info->designation_article</td><td>$info->quantite</td><td>$info->prix_unitaire_ht</td><td>$info->raison_sociale_fournisseur</td><td>$info->date</td>
        <td>
        <form action='?deleteAchat' method='POST'>
            <input type='image' src='/E-Stock/assets/images/delete.svg' alt='delete-icon'>
            <input name='idAchat' value='$info->id_achat' type='hidden'>
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