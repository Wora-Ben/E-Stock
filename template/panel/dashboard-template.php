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
                    <div>
                        <img class="panel-info-icon" src="/E-Stock/assets/images/buy.svg" alt="">
                        <div class="panel-information-title">Nombre d'achats</div>
                    </div>
                    <div>
                        <p class="panel-information-content text-center">$infos[0]</p>
                    </div>
                </a>
            </div>
        </div>
        <div class="panel-information">
            <div class="container-logo">
                <a href="#">
                <div>
                    <img class="panel-info-icon" src="/E-Stock/assets/images/sales.svg" alt="">
                    <div class="panel-information-title">Nombre de ventes</div>
                </div>
                <div>
                    <p class="panel-information-content text-center">$infos[2]</p>
                </div>
                </a>
            </div>
        </div>
        <div class="panel-information">
            <div class="container-logo">
                <a href="#">
                    <div>
                        <img class="panel-info-icon" src="/E-Stock/assets/images/value.svg" alt="">
                        <div class="panel-information-title">Valeur du stock</div>
                    </div>
                    <div>
                        <p class="panel-information-content text-center">$infos[4]€</p>
                    </div>
                </a>
            </div>
        </div>
        <div class="panel-information">
            <div class="container-logo">
                <a href="#">
                <div>
                    <img class="panel-info-icon" src="/E-Stock/assets/images/quantity.svg" alt="">
                    <div class="panel-information-title">Quantité du stock</div>
                </div>
                <div>
                    <p class="panel-information-content text-center">$infos[5]</p>
                </div>
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
                <div>
                    <img class="panel-info-icon" src="/E-Stock/assets/images/value_import_goods.svg" alt="">
                    <div class="panel-information-title">Total achats</div>
                
                </div>
                <div>
                    <p class="panel-information-content text-center">$infos[1] €</p>
                </div>
                </a>
            </div>
        </div>
        <div class="panel-information">
            <div class="container-logo">
                <a href="#">
                    <div>
                           <img class="panel-info-icon" src="/E-Stock/assets/images/sales-euro.svg" alt="">
                           <div class="panel-information-title">Total ventes</div>
                    </div>
                    <div>
                            <p class="panel-information-content text-center">$infos[3] €</p>
                    </div>
                </a>
            </div>
        </div>
        <div class="panel-information">
            <div class="container-logo">
                <a href="#">
                        <div>
                            <img class="panel-info-icon" src="/E-Stock/assets/images/clients.svg" alt="client-logo">
                            <div class="panel-information-title">Nombre de clients</div>
                        </div>
                        <div>
                            <p class="panel-information-content">$infos[7]</p>
                        </div>
                </a>
            </div>
        </div>
        <div class="panel-information">
            <div class="container-logo">
                <a href="#">
                    <div>
                        <img class="panel-info-icon" src="/E-Stock/assets/images/supplier.svg" alt="">
                        <div class="panel-information-title">Nombre de fournisseurs</div>
                    </div>
                    <div>
                    <p class="panel-information-content text-center">$infos[6]</p>
                    </div>    
                </a>
            </div>
        </div>
    </div>
</div>
EOT;
include(dirname(__DIR__, 2) . "/assets/html/frag-fin.html");