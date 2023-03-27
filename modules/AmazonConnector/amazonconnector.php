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

    // Ce Hook permet d'intégrer la synchronisation des produits via Amazon //
    public function hookActionProductSave($params)
    
        {
            // Récupérer les informations du produit qui ont été sauvegardées dans PrestaShop
            $product_id = (int)$params['id_product'];
            $product = new Product($product_id, false, $this->context->language->id);
        
            // Formater les données du produit pour qu'elles correspondent aux spécifications d'Amazon
            $amazon_product_data = array(
                // Insérez les données du produit formatées pour Amazon ici
            );
        
            // Utiliser l'API d'Amazon pour créer ou mettre à jour le produit sur votre boutique Amazon
            $amazon_api = new AmazonAPI(); 
            $response = $amazon_api->createOrUpdateProduct($amazon_product_data); //Appel la méthode de l'API pour créer ou mettre à jour le produit
        
            // Gérer les erreurs et les messages de retour de l'API d'Amazon
            if ($response->isError()) {
                // Erreur
            } else {
                // Sinon produit importé avec succès
            }
        }

        /**
         * Cette méthode renvoie le contenu à afficher sur la page de configuration du module.
         * Elle est appelée lorsque l'utilisateur accède à la page de configuration du module dans le back-office.
         * Elle renvoie un formulaire contenant les paramètres de configuration du module et permettant à l'utilisateur
         * de les modifier
         */

        public function getContent()
        {
        $output = null;
 
        if (Tools::isSubmit('submit'.$this->name))
        {
        $access_key = strval(Tools::getValue('amazon_access_key'));
        $secret_key = strval(Tools::getValue('amazon_secret_key'));
 
        if (!$access_key  || empty($access_key) || !Validate::isGenericName($access_key))
            $output .= $this->displayError($this->l('Invalid Amazon Access Key'));
        elseif (!$secret_key || empty($secret_key) || !Validate::isGenericName($secret_key))
            $output .= $this->displayError($this->l('Invalid Amazon Secret Key'));
        else
        {
            Configuration::updateValue('AMAZON_ACCESS_KEY', $access_key);
            Configuration::updateValue('AMAZON_SECRET_KEY', $secret_key);
            $output .= $this->displayConfirmation($this->l('Settings updated'));
        }
    }
 
        return $output.$this->displayForm();
    }
    
        public function displayForm()
        {
        // Get default language
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        
        // Init Fields form array
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Amazon Connector Settings'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Amazon Access Key'),
                    'name' => 'amazon_access_key',
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Amazon Secret Key'),
                    'name' => 'amazon_secret_key',
                    'required' => true
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );
    
        $helper = new HelperForm();
    
        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
    
        // Language
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
    
        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'submit'.$this->name;
    
        // Load current value
        $helper->fields_value['amazon_access_key'] = Configuration::get('AMAZON_ACCESS_KEY');
        $helper->fields_value['amazon_secret_key'] = Configuration::get('AMAZON_SECRET_KEY');
    
        return $helper->generateForm($fields_form);
    }


        
    
}
