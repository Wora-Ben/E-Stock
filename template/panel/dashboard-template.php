<?php
global $infos;
include(dirname(__DIR__, 1) . '/frag-deb.html');

echo <<<EOT
<div class="panel-informations">
    <div class="panel-title">
        <span>Tableau de bord</span>
    </div>
    <div class="panel-content">
        <div class="panel-information">
            <div class="container-logo">
                <a href="#">
                    <img class="panel-info-icon" src="/E-Stock/assets/images/buy.svg" alt="">
                    <span>
                    <p class="panel-information-title">Nombre d'achats</p>
                    <p class="panel-information-content text-center">$infos[0]</p>
                </span>
                </a>
            </div>
        </div>
        <div class="panel-information">
            <div class="container-logo">
                <a href="#">
                    <img class="panel-info-icon" src="/E-Stock/assets/images/sales.svg" alt="">
                    <span>
                    <p class="panel-information-title">Nombre de ventes</p>
                    <p class="panel-information-content text-center">$infos[2]</p>
                </span>
                </a>
            </div>
        </div>
        <div class="panel-information">
            <div class="container-logo">
                <a href="#">
                    <img class="panel-info-icon" src="/E-Stock/assets/images/value.svg" alt="">
                    <span>
                    <p class="panel-information-title">Valeur du stock</p>
                    <p class="panel-information-content text-center">$infos[4]€</p>
                </span>
                </a>
            </div>
        </div>
        <div class="panel-information">
            <div class="container-logo">
                <a href="#">
                    <img class="panel-info-icon" src="/E-Stock/assets/images/quantity.svg" alt="">
                    <span>
                    <p class="panel-information-title">Quantité du stock</p>
                    <p class="panel-information-content text-center">$infos[5]</p>
                </span>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="panel-informations">
    <div class="panel-title">
        <span></span>
    </div>
    <div class="panel-content">
        <div class="panel-information">
            <div class="container-logo">
                <a href="#">
                    <img class="panel-info-icon" src="/E-Stock/assets/images/value_import_goods.svg" alt="">
                    <span>
                    <p class="panel-information-title">Total achats</p>
                    <p class="panel-information-content text-center">$infos[1] €</p>
                </span>
                </a>
            </div>
        </div>
        <div class="panel-information">
            <div class="container-logo">
                <a href="#">
                    <img class="panel-info-icon" src="/E-Stock/assets/images/sales-euro.svg" alt="">
                    <span>
                    <p class="panel-information-title">Total ventes</p>
                    <p class="panel-information-content text-center">$infos[3] €</p>
                </span>
                </a>
            </div>
        </div>
        <div class="panel-information">
            <div class="container-logo">
                <a href="#">
                    <img class="panel-info-icon" src="/E-Stock/assets/images/clients.svg" alt="">
                    <span>
                    <p class="panel-information-title">Nombre de clients</p>
                    <p class="panel-information-content text-center">$infos[7]</p>
                </span>
                </a>
            </div>
        </div>
        <div class="panel-information">
            <div class="container-logo">
                <a href="#">
                    <img class="panel-info-icon" src="/E-Stock/assets/images/supplier.svg" alt="">
                    <span>
                    <p class="panel-information-title">Nombre de fournisseurs</p>
                    <p class="panel-information-content text-center">$infos[6]</p>
                </span>
                </a>
            </div>
        </div>
    </div>
</div>
EOT;
include(dirname(__DIR__, 2) . "/assets/html/frag-fin.html");