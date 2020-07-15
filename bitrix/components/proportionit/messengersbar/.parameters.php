<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
if (!CModule::IncludeModule("proportionit.messengers"))
	return;
$arComponentParameters = array(
    "GROUPS" => array(
    ),
    "PARAMETERS" => array(

            "CACHE_TIME" => Array("DEFAULT" => 360000),
    )
);
?>