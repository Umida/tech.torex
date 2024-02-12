<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Loader;
Loader::includeModule("sale");
    $fUserID = CSaleBasket::GetBasketUserID(True);
    $fUserID = IntVal($fUserID);

    
 
   $success = 0;
   
$inwished=[];
switch ( $_POST['action']) {
    case "add":
            
    $arFields = array(
        "PRODUCT_ID" => $_POST['p_id'],
        "PRODUCT_PRICE_ID" => $_POST['pp_id'],
        "PRICE" => $_POST['p'],
        "CURRENCY" => "RUB",
        "WEIGHT" => 0,
        "QUANTITY" => 1,
        "LID" => 's1',
        "DELAY" => "Y",
        "CAN_BUY" => "Y",
        "NAME" => $_POST['name'],
        "MODULE" => "sale",
        "NOTES" => "",
        "DETAIL_PAGE_URL" => $_POST['dpu'],
        "FUSER_ID" => $fUserID
    );
            if (CSaleBasket::Add($arFields)) {
                
               $arBasketItems = array();
        $dbBasketItems = CSaleBasket::GetList(
            array(
                "NAME" => "ASC",
                "ID" => "ASC"
            ),
            array(
                "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                "LID" => SITE_ID,
                "ORDER_ID" => "NULL",
                "DELAY" => "Y",
            ),
            false,
            false,
            array("ID","PRODUCT_ID")
        );
        while ($arItems = $dbBasketItems->Fetch()){
            if ($arItems["PRODUCT_ID"] == $_POST['p_id']){
                $inwished['id'] = $arItems["ID"];
            }
            $arBasketItems[] = $arItems["PRODUCT_ID"];
        }
        $inwished['count'] = count($arBasketItems);
    }
        break;
    case "delete":
        
        if (CSaleBasket::Delete($_POST['p_id'])) {
        $arBasketItems = array();
        $dbBasketItems = CSaleBasket::GetList(
            array(
                "NAME" => "ASC",
                "ID" => "ASC"
            ),
            array(
                "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                "LID" => SITE_ID,
                "ORDER_ID" => "NULL",
                "DELAY" => "Y",
            ),
            false,
            false,
            array("ID","PRODUCT_ID")
        );
        while ($arItems = $dbBasketItems->Fetch()){
      
            $arBasketItems[] = $arItems["PRODUCT_ID"];
        }
        $inwished['count'] = count($arBasketItems);
}
        break;
    
}

echo json_encode($inwished);


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>