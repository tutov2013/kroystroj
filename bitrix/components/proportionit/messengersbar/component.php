<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
if (!CModule::IncludeModule("proportionit.messengers")) {
	ShowError("Error!!!");       
  	return;
}

	$arResult['ITEMS'] = $this->getResultItems();

	$this->IncludeComponentTemplate();


