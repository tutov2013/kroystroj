<?
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true){
		die();
	}

	//check product id
	if(empty($arParams["PRODUCT_ID"])){
		return false;
	}

	//load module
	if(!Bitrix\Main\Loader::includeModule("dw.deluxe")){
		return false;
	}

	//vars
	$arResult = array();
	$arOwnerless = array();

	//set default params
	$arParams["SHOW_DELIVERY_IMAGES"] = !empty($arParams["SHOW_DELIVERY_IMAGES"]) ? $arParams["SHOW_DELIVERY_IMAGES"] : "Y";
	$arParams["PRODUCT_AVAILABLE"] = !empty($arParams["PRODUCT_AVAILABLE"]) ? $arParams["PRODUCT_AVAILABLE"] : "Y";
	$arParams["CONVERT_ENCODING"] = !empty($arParams["CONVERT_ENCODING"]) ? $arParams["CONVERT_ENCODING"] : "N";
	$arParams["DEFERRED_MODE"] = !empty($arParams["DEFERRED_MODE"]) ? $arParams["DEFERRED_MODE"] : "N";
	$arParams["LOAD_SCRIPT"] = !empty($arParams["LOAD_SCRIPT"]) ? $arParams["LOAD_SCRIPT"] : "Y";
	$arParams["SITE_ID"] = !empty($arParams["SITE_ID"]) ? $arParams["SITE_ID"] : SITE_ID;

	//check encoding
	if(!empty($arParams["GROUP_BUTTONS_LABELS"]) && $arParams["CONVERT_ENCODING"] == "Y"){
		$arParams["GROUP_BUTTONS_LABELS"] = DwSettings::convertEncodingAjax($arParams["GROUP_BUTTONS_LABELS"]);
	}

	//get product measure ratio
	$arResult["MEASURE_RATIO"] = \DigitalWeb\Basket::getMeasureRatio($arParams["PRODUCT_ID"]);
	if(empty($arParams["PRODUCT_QUANTITY"])){
		$arParams["PRODUCT_QUANTITY"] = $arResult["MEASURE_RATIO"];
	}

	//get delivery items
	if($arParams["PRODUCT_AVAILABLE"] == "Y" && $arParams["DEFERRED_MODE"] != "Y"){
		$arResult["DELIVERY_ITEMS"] = \DigitalWeb\CalculateDelivery::getCalculatedItems($arParams);
		$arResult["DELIVERY_GROUPS"] = \DigitalWeb\Basket::getDeliveriesGroups();
	}

	//check existence groups / items
	if(!empty($arResult["DELIVERY_GROUPS"]) && !empty($arResult["DELIVERY_ITEMS"])){

		//search deliveries without groups
		foreach($arResult["DELIVERY_ITEMS"] as $deliveryId => $nextDelivery){

			//flag
			$findItem = false;

			//each groups
			foreach($arResult["DELIVERY_GROUPS"] as $nextGroup){

				//without group
				if(($findItem = !empty($nextGroup["ITEMS"][$deliveryId]))){
					break(1);
				}

			}

			//check flag
			if($findItem == false){
				$arOwnerless[$deliveryId] = $nextDelivery;
			}

		}

		//clear delivery groups
		foreach($arResult["DELIVERY_GROUPS"] as $nextGroupId => $nextGroup){

			//seach empty groups
			if(($kill = empty($nextGroup["ITEMS"])) !== true){
				foreach($nextGroup["ITEMS"] as $nextGroupItemId => $nextGroupItem){
					if(empty($arResult["DELIVERY_ITEMS"][$nextGroupItem["ID"]])){
						unset($arResult["DELIVERY_GROUPS"][$nextGroupId]["ITEMS"][$nextGroupItemId]);
					}
				}
			}

			//kill empty groups
			if($kill || empty($arResult["DELIVERY_GROUPS"][$nextGroupId]["ITEMS"])){
				unset($arResult["DELIVERY_GROUPS"][$nextGroupId]);
			}

		}

		//check after kill & create group for ownerless items
		if(!empty($arOwnerless) && !empty($arResult["DELIVERY_GROUPS"])){
			$arResult["DELIVERY_GROUPS"]["_"] = array(
				"ITEMS" => $arOwnerless,
				"NAME" => GetMessage("DELIVERY_GROUPS_OWNERLESS"),
				"ID" => "_"
			);
		}

	}

	//show template
	$this->IncludeComponentTemplate();
?>