<?

function getgeoposition()
{
 if ($_REQUEST['geo']){
     CModule::IncludeModule('highloadblock');
     
     $hlbl = 8; 
     $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($hlbl)->fetch(); 
     $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock); 
     $entity_data_class = $entity->getDataClass(); 
     $rsData = $entity_data_class::getList(array(
         "select" => array("*"),
         "order" => array("ID" => "ASC"),
         "filter" => array("UF_XML_ID"=>$_REQUEST['geo'])  
    ));  
    while($arData = $rsData->Fetch()){
        $cookie = new \Bitrix\Main\Web\Cookie("GEO", $arData["ID"], time()+86400*30);
        $cookie->setSecure(false);
        $cookie->setHttpOnly(false);
        \Bitrix\Main\Application::getInstance()->getContext()->getResponse()->addCookie($cookie);
        
         $cookie = new \Bitrix\Main\Web\Cookie("cityXML", $arData["UF_XML_ID"], time()+86400*30);
        $cookie->setSecure(false);
        $cookie->setHttpOnly(false);
        \Bitrix\Main\Application::getInstance()->getContext()->getResponse()->addCookie($cookie);
        
        $cookie = new \Bitrix\Main\Web\Cookie("cityNAME", $arData["UF_NAME"], time()+86400*30);
        $cookie->setSecure(false);
        $cookie->setHttpOnly(false);
        \Bitrix\Main\Application::getInstance()->getContext()->getResponse()->addCookie($cookie);
        
        
        
         $cookie = new \Bitrix\Main\Web\Cookie("PRICE", $arData["UF_PRICE"], time()+86400*30);
        $cookie->setSecure(false);
        $cookie->setHttpOnly(false);
        \Bitrix\Main\Application::getInstance()->getContext()->getResponse()->addCookie($cookie);
        
         $cookie = new \Bitrix\Main\Web\Cookie("BANNERS", $arData["UF_BANNERS"], time()+86400*30);
        $cookie->setSecure(false);
        $cookie->setHttpOnly(false);
        \Bitrix\Main\Application::getInstance()->getContext()->getResponse()->addCookie($cookie);
        
        return ["cityId"=>$arData["ID"],'cityNAME'=>$arData["UF_NAME"],"cityXML"=>$arData["UF_XML_ID"],"price"=>$arData["UF_PRICE"],"banners"=>$arData["UF_BANNERS"]];
    }
}
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$cityId = $request->getCookie("GEO");
if ($cityId){
    $banners = $request->getCookie("BANNERS");
    $price = $request->getCookie("PRICE");
    $cityXML = $request->getCookie("cityXML");
    $cityNAME = $request->getCookie("cityNAME");
    
}
else{
    $cityId = 1;   
    $banners = "banners_msk";
    $price = "MSK";
    $cityXML = "moscow";
    $cityNAME = 'Москва';
}
return ["cityId"=>$cityId,'cityNAME'=>$cityNAME,"cityXML"=>$cityXML,"price"=>$price,"banners"=>$banners];
}
function getNoun($number, $one, $two, $five) {
    $number = abs($number);
    $number %= 100;
    if ($number >= 5 && $number <= 20) {
        return $five;
    }
    $number %= 10;
    if ($number == 1) {
        return $one;
    }
    if ($number >= 2 && $number <= 4) {
        return $two;
    }
    return $five;
}

// Пример использования функции
$numApples = 5;

?>