<?php

namespace model;

class ClientModel
{
    public $id_client;
    public $raison_sociale_client;
    public $adresse_client;
    public $telephone_client;
    public $email_client;
    public $n_siren;
    public $mode_paiement;
    public $delai_paiement;
    public $mode_livraison;

    function __construct(){}
}