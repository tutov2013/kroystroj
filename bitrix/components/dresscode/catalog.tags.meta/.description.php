<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$arComponentDescription = array(
   "NAME" => GetMessage("NAME"),
   "DESCRIPTION" => GetMessage("DESCRIPTION"),
   "ICON" => "/images/banner.gif",
   "PATH" => array(
      "ID" => "DRESSCODE",
      "CHILD" => array(
         "ID" => "catalogTagsMeta",
         "NAME" => GetMessage("DESCRIPTION")
      )
   )
);
?>