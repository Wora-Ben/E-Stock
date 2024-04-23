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
                <img class="panel-info-icon" src="/E-Stock/assets/images/sales-euro.svg" alt="fournisseur icon">
                <span class="heading-text">Ventes</span>
            </div>
                <form action="ventes.php" method="GET">
                    <div class="box-utils">
                        <div class="search-box">
                            <div class="search-logo"><img src="/E-Stock/assets/images/search.svg" alt=""></div>
                            <input name="searchValue" type="text" class="search-input" placeholder="Chercher">
                        </div>
                        <div class="buttons">
                            <input name="search" type="submit" class="btn search" value="Rechercher">
                            <span class="btn addBtn">
                                <a href="?addVente">+ nouvel vente</a>
                            </span>
                        </div>
                    </div>
                </form>
        </div>
EOT;
if (isset($_GET['newVente'])) {
    echo <<<EOT
    <div class="notification">
    <ul>
        <li>
            Nouvel vente a été ajouté avec succès
        </li>
    </ul>
    </div>
EOT;
}
if (isset($_GET['venteDeleted'])) {
    echo <<<EOT
    <div class="notification">
    <ul>
        <li>
            La vente a été supprimer avec succès 
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
                        <th>Client</th>
                        <th>Référence article</th>
                        <th>Designation article</th>
                        <th>Quantite</th>
                        <th>Prix unitaire HT</th>
                        <th>Date</th>
                        <th>Mode de livraison</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
EOT;

if ($infos) {
    foreach (array_chunk($infos, 15)[$currentPage - 1] as $info) {
        echo "<tr><td>$info->raison_sociale_client</td><td>$info->reference_article</td><td>$info->designation_article</td><td>$info->quantite</td><td>$info->prix_unitaire_ht</td><td>$info->date</td><td>$info->mode_livraison</td>
        <td>
        <form action='?deleteVente' method='POST'>
            <input type='image' src='/E-Stock/assets/images/delete.svg'>
            <input name='idVente' value='$info->id_vente' type='hidden'>
        </form>        
        </td></tr>";
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