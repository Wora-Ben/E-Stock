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
                <img class="panel-info-icon" src="/E-Stock/assets/images/supplier.svg" alt="fournisseur-icon">
                <span class="heading-text">Liste fournisseurs</span>
            </div>
                <form action="fournisseurs.php" method="GET">
                    <div class="box-utils">
                        <div class="search-box">
                            <div class="search-logo"><img src="/E-Stock/assets/images/search.svg" alt="search-icon"></div>
                            <input name="searchValue" type="text" class="search-input" placeholder="Chercher">
                        </div>
                        <div class="buttons">
                            <input name="search" type="submit" class="btn search" value="Rechercher">
                            <span class="btn addBtn">
                                <a href="?addFournisseur">+ Ajouter un fournisseur</a>
                            </span>
                        </div>
                    </div>
                </form>
        </div>
EOT;
if (isset($_GET['newFournisseur'])) {
    echo <<<EOT
    <div class="notification">
    <ul>
        <li>
            Nouvel fournisseur a été ajouté avec succès
        </li>
    </ul>
    </div>
EOT;
}
if (isset($_GET['fournisseurEdited'])) {
    echo <<<EOT
    <div class="notification">
    <ul>
        <li>
            Fournisseur a été modifier avec succès 
        </li>
    </ul>
    </div>
EOT;
}
if (isset($_GET['FournisseurDeleted'])) {
    echo <<<EOT
    <div class="notification">
    <ul>
        <li>
            Fournisseur a été supprimer avec succès 
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
                        <th>Raison sociale</th>
                        <th>N° SIREN</th>
                        <th>Telephone</th>
                        <th>Email</th>
                        <th>Adresse</th>
                        <th>Mode paiement</th>
                        <th>Delai paiement</th>
                        <th>Nom interlocuteur</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
EOT;

if ($infos) {
    foreach (array_chunk($infos, 15)[$currentPage - 1] as $info) {
        echo "<tr><td>$info->raison_sociale_fournisseur</td><td>$info->n_siren</td><td>$info->telephone_fournisseur</td><td>$info->email_fournisseur</td><td>$info->adresse_fournisseur</td><td>$info->mode_paiement</td><td>$info->delai_paiement</td><td>$info->nom_interlocuteur</td>
        <td>
        <form action='?editFournisseur' method='POST'>
            <input type='image' src='/E-Stock/assets/images/edit.svg' alt='edit-icon'>
            <input name='idFournisseur' value='$info->id_fournisseur' type='hidden'>
        </form>
        <form action='?deleteFournisseur' method='POST'>
            <input type='image' src='/E-Stock/assets/images/delete.svg' alt='delete-icon'>
            <input name='idFournisseur' value='$info->id_fournisseur' type='hidden'>
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