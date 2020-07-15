<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

use Bitrix\Main\Loader;

//use proportionit\geocity\CCityGeo;

if (!Loader::includeModule("proportionit.messengers")) {
    ShowError(GetMessage("PROPORTION_MESSENGERS_ERROR"));
      return;
} 

class CProportionitMessengers extends CBitrixComponent {
        public $city;
        public function onPrepareComponentParams($arParams) {
		global $USER;
                $result = array(
			"CACHE_TIME" => isset($arParams["CACHE_TIME"]) ? $arParams["CACHE_TIME"] : 36000,
		);
		return $result;
	}

	public function executeComponent() {
		global $APPLICATION, $USER;

		return parent::executeComponent();
	}
 
	public function getResultItems() {

                $arResult = array();

                $arResult = ProportionIt\Messengers\CMessengers::GetAllOptions(SITE_ID);
                       
   
                return $arResult;
	}

}

