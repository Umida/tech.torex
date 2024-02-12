 
<?	
global $delaydBasketItems;
$curPage = $APPLICATION->GetCurPage(true);
use Bitrix\Main\Loader;
    Loader::includeModule("sale");
    $delaydBasket = CSaleBasket::GetList(
    array(
        "NAME" => "ASC",
        "ID" => "ASC"
    ),
    array(
        "FUSER_ID" => CSaleBasket::GetBasketUserID(),
        "LID" =>  's1',
        "ORDER_ID" => "NULL",
        "DELAY" => "Y",
    ),
    false,
    false,
    array("ID","PRODUCT_ID")
);
while ($arItems = $delaydBasket->Fetch())
{
    $delaydBasketItems[$arItems['ID']] = $arItems['PRODUCT_ID'];
}?>

<div class="header-catalog__btn header-favorite__btn">
     <?if (strpos($curPage, 'personal/favorites/') === false):?>
     <a href="/personal/favorites/">
         <?endif;?>
        <i data-svg="heart-header"></i>
<?if ($delaydBasket->SelectedRowsCount()>0):?>
    <div class="basket-line-count" id="wishcount"><?=$delaydBasket->SelectedRowsCount()?></div>
    <?endif;?>
    <?if (strpos($curPage, 'personal/favorites/') === false):?>
    </a>
    <?endif;?>
    </div> 
