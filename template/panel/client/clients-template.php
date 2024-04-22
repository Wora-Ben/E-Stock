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
                <img class="panel-info-icon" src="/E-Stock/assets/images/clients.svg" alt="client icon">
                <span class="heading-text">Clients</span>
            </div>
                <form action="clients.php" method="GET">
                    <div class="box-utils">
                        <div class="search-box">
                            <div class="search-logo"><img src="/E-Stock/assets/images/search.svg" alt=""></div>
                            <input name="searchValue" type="text" class="search-input" placeholder="Chercher">
                        </div>
                        <div class="buttons">
                            <input name="search" type="submit" class="btn search" value="Rechercher">
                            <span class="btn addBtn">
                                <a href="?addClient">+ nouveau client</a>
                            </span>
                        </div>
                    </div>
                </form>
        </div>
EOT;
if (isset($_GET['newClient'])) {
    echo <<<EOT
    <div class="notification">
    <ul>
        <li>
            Nouveau client a été ajouté avec succès
        </li>
    </ul>
    </div>
EOT;
}
if (isset($_GET['clientEdited'])) {
    echo <<<EOT
    <div class="notification">
    <ul>
        <li>
            Client a été modifier avec succès 
        </li>
    </ul>
    </div>
EOT;
}
if (isset($_GET['clientDeleted'])) {
    echo <<<EOT
    <div class="notification">
    <ul>
        <li>
            Client a été supprimer avec succès 
        </li>
    </ul>
    </div>
EOT;
}
echo <<<EOT
        <div class="box-content">
            <table>
                <thead>
                    <tr>
                        <th>Raison sociale</th>
                        <th>N° SIREN</th>
                        <th>Telephone</th>
                        <th>Email</th>
                        <th>Adresse</th>
                        <th>Mode paiement</th>
                        <th>Delai paiement</th>
                        <th>Mode livraison</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
EOT;

if ($infos) {
    foreach (array_chunk($infos, 15)[$currentPage - 1] as $info) {
        echo "<tr><td>$info->raison_sociale_client</td><td>$info->n_siren</td><td>$info->telephone_client</td><td>$info->email_client</td><td>$info->adresse_client</td><td>$info->mode_paiement</td><td>$info->delai_paiement</td><td>$info->mode_livraison</td>
        <td>
        <form action='?editClient' method='POST'>
            <input type='image' src='/E-Stock/assets/images/edit.svg'>
            <input name='idClient' value='$info->id_client' type='hidden'>
        </form>
        <form action='?deleteClient' method='POST'>
            <input type='image' src='/E-Stock/assets/images/delete.svg'>
            <input name='idClient' value='$info->id_client' type='hidden'>
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