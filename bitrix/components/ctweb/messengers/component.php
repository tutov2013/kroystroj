<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arResult = unserialize(base64_decode($arParams["RESULT"]));
$arParams = unserialize(base64_decode($arParams["PARAMS"]));

$this->IncludeComponentTemplate();

?>