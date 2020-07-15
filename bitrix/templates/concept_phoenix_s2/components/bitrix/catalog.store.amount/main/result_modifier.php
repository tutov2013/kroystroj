<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();


if(!empty($arResult["STORES"]))
{
	global $PHOENIX_TEMPLATE_ARRAY;
	
	foreach($arResult["STORES"] as $pid => $arProperty)
	{
		if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SHOW_EMPTY_STORE"]["VALUE"]["ACTIVE"] != "Y" && isset($arProperty['REAL_AMOUNT']) && $arProperty['REAL_AMOUNT'] <= 0)
		{
			unset($arResult["STORES"][$pid]);
			continue;
		}

		if(isset($arParams["STORE_QUANTITY"][$arProperty["ID"]]))
		{
			$arResult["STORES"][$pid]["MEASURE"] = $arParams["STORE_QUANTITY"][$arProperty["ID"]]["MEASURE"];
			$arResult["STORES"][$pid]["QUANTITY"] = CPhoenixSku::getDescQuantity($arProperty["REAL_AMOUNT"], $arResult["STORES"][$pid]["MEASURE"]);

			
			$arResult["STORES"][$pid]["QUANTITY"]["DESCRIPTION"] = (isset($arResult["STORES"][$pid]["QUANTITY"]["QUANTITY_FORMATED"]{0})?$arResult["STORES"][$pid]["QUANTITY"]["QUANTITY_FORMATED"]:$arResult["STORES"][$pid]["QUANTITY"]["TEXT"]);

			
			$arResult["STORES"][$pid]["QUANTITY"]["DESCRIPTION_FLAT"] = (isset($arResult["STORES"][$pid]["QUANTITY"]["QUANTITY_WITH_TEXT"]{0})?$arResult["STORES"][$pid]["QUANTITY"]["QUANTITY_WITH_TEXT"]:$arResult["STORES"][$pid]["QUANTITY"]["TEXT"]);


			if(isset($arProperty["EMAIL"]{0}))
				$arResult["STORES"][$pid]["EMAIL_FORMATED"] = "<a href='mailto:".$arProperty["EMAIL"]."'>".$arProperty["EMAIL"]."</a>";
		}

	}
}

