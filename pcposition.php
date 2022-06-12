<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class PcPosition extends Module
{
	public function __construct()
    {
        $this->name = 'pcposition';
        $this->tab = 'front_office_features';
        $this->version = '1.0.1';
        $this->author = 'Presta Centre';
        $this->bootstrap = true;
		$this->need_instance = 0;
		
        parent::__construct();

        $this->displayName = $this->l('Change Position');
        $this->description = $this->l('Allows you to edit the position of the products within the category');
        $this->ps_versions_compliancy = ['min' => '1.6.0.0', 'max' => _PS_VERSION_];
    }

    public function install()
    {
		include(dirname(__FILE__).'/sql/install.php');
        return parent::install() &&
            $this->registerHook('actionAdminControllerSetMedia');
    }

    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');
		return parent::uninstall();
    }
    public function getContent()
    {
		$output 	= '';
		$pname 		= '';
		$idLang 	= Context::getContext()->language->id;
		$idCat 		= 1;
		$products 	= '';
		$valus 		= 0;
		$edit 		= 0;
		$error		= array();

		if (((bool)Tools::isSubmit('pcategory')) == true)
		{
			$idCat = Tools::getValue('pcategory');
			if (Validate::isInt($idCat))
			{
				$products 	= Db::getInstance()->executeS('SELECT *, cp.position AS cposition FROM `'._DB_PREFIX_.'category_product` cp 
					INNER JOIN `'._DB_PREFIX_.'product_lang` pl ON cp.id_product = pl.id_product
					INNER JOIN `'._DB_PREFIX_.'image` i ON i.id_product = pl.id_product
					WHERE cp.id_category = '.(int)$idCat.' AND pl.id_lang = '.(int)$idLang.' AND i.cover = 1 ORDER BY cp.position ASC
				');

				$this->context->cookie->__set('idCategory',$idCat);
			}
		}
		if (((bool)Tools::isSubmit('changePositon')) == true)
		{
			$changePositon 	= Tools::getValue('changePositon');
			$idCat 			= Context::getContext()->cookie->idCategory;
			$newPosition	= 1;
			foreach ($changePositon as $cp)
			{
				$idProduct 	= $cp;
				if (Validate::isInt($idProduct) AND Validate::isInt($idCat) AND $idCat > 1)
				{
					$products 	= Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'category_product` WHERE id_product = '.(int)$idProduct.' AND id_category = '.(int)$idCat.'');

					Db::getInstance()->update('category_product', array(
						'position' 	=> (int)$newPosition,
					), 'id_product = '.(int)$idProduct.' AND id_category = '.(int)$idCat.''); 
					$newPosition++;
					$valus = 1;
				}
				else
					$valus = 0;
			}
			echo $valus;
			exit();
		}

		$category	= Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'category_lang` WHERE id_lang = '.(int)$idLang.' AND id_category > 1');
		$this->context->smarty->assign(array(
			'products' 	=> $products,
			'category' 	=> $category,
			'idCat' 	=> $idCat,
			'pname' 	=> $pname,
			'editLink' 	=> Context::getContext()->link->getAdminLink('AdminModules').'&configure=pcposition&editProduct=',
			'baseLink' 	=> Context::getContext()->link->getAdminLink('AdminModules').'&configure=pcposition',
		));
		for ($i = 0; $i < COUNT($error); $i++)
		{
			$output .= $error[$i];
		}
		return $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');
    }
    public function hookActionAdminControllerSetMedia()
    {
        if (Tools::getValue('configure') == 'pcposition')
		{
			$edit = (int)Tools::getValue('editProduct');
			$this->context->controller->addCSS($this->_path . 'views/css/pcposition.css');
			if ($edit == 0)
				$this->context->controller->addJS($this->_path . 'views/js/jquery-ui.min.js');
			else
				$this->context->controller->addJS($this->_path . 'views/js/pcposition.js');
		}
    }
	public static function getpImage($id)
	{
		if (Validate::isInt($id))
		{
			$product 	= new Product($id, false, Context::getContext()->language->id);
			$images 	= Product::getCover($id);
			$image_url 	= Context::getContext()->link->getImageLink($product->link_rewrite, $images['id_image'], ImageType::getFormatedName('home'));
			return $image_url;
		}
	}
}
