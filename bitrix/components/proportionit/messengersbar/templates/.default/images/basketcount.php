<?
CModule::IncludeModule("sale");
 
$dbBasketItems = CSaleBasket::GetList(
    array(
        "NAME" => "ASC",
        "ID" => "ASC"
    ),
    array(
        "FUSER_ID" => CSaleBasket::GetBasketUserID(),
        "LID" => SITE_ID,
        "ORDER_ID" => "NULL",
        "CAN_BUY" => "Y",
    ),
    false,
    false,
    array("ID")
);
 
$basketCount = 0; 
while ($arItem = $dbBasketItems->Fetch())
{
    $basketCount++;
}

echo $basketCount;

?>
