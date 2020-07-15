<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arResult = array();
global $DB;

if (!empty($arParams["MAP_ID"])){
	$ret = $DB->Query('SELECT data FROM angerro_yadelivery WHERE id='.$arParams["MAP_ID"])->GetNext();
	$arResult['MAP_DATA'] = $ret['~data'];
}

$this->IncludeComponentTemplate();
?>