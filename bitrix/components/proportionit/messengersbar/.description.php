<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (!CModule::IncludeModule("proportionit.messengers")) {
	
	return;
}

$arComponentDescription = array(
	"NAME" => GetMessage("PROPORTION_MESSENGERS_COMP_NAME"),
	"DESCRIPTION" => GetMessage("PROPORTION_MESSENGERS_COMP_DESCR"), 
	//"ICON" => "/images/icon.gif",
	"CACHE_PATH" => "Y",
	"COMPLEX" => "Y",
	"PATH" => array(
		"ID" => "proportionit",
		"NAME" => GetMessage("PROPORTION_MESSENGERS_DESC_COMPONENTS"),
		"SORT" => 100,
		"CHILD" => array(
			"ID" => "MessengersMenu",
			"NAME" => GetMessage("PROPORTION_MESSENGERS_DESC_COMPONENTS_MESSENGERS"),
			"SORT" => 100,
			"CHILD" => array(
				"ID" => "messengers",
			),
		),
	),
);

