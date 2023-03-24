<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class AmazonConnector extends Module
{
    public function __construct()
    {
        $this->name = 'amazonconnector';
        $this->tab = 'others';
        $this->version = '1.0.0';
        $this->author = 'Votre Nom';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        parent::__construct();

        $this->displayName = $this->l('Amazon Connector');
        $this->description = $this->l('Intégration automatique des produits de votre boutique Prestashop sur votre boutique Amazon.');

        $this->confirmUninstall = $this->l('Êtes-vous sûr de vouloir désinstaller le module Amazon Connector ?');
    }

    public function install()
    {
        if (!parent::install() || !$this->registerHook('actionProductSave')) {
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        return true;
    }

    public function hookActionProductSave($params)
    {
        // TODO: Implémenter la synchronisation des produits avec Amazon
    }
}
