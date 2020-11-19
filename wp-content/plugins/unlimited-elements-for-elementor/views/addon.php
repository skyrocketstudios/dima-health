<?php
/**
 * @package Unlimited Elements
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNLIMITED_ELEMENTS_INC') or die('Restricted access');

require_once GlobalsUC::$pathViewsObjects."addon_view.class.php";

$pathProviderAddon = GlobalsUC::$pathProvider."views/addon.php";

if(file_exists($pathProviderAddon) == true){
	require_once $pathProviderAddon;
	$objAddonView = new UniteCreatorAddonViewProvider();
}
else{
	$objAddonView = new UniteCreatorAddonView();
}

$objAddonView->runView();
