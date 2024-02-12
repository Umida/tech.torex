<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!empty($arResult['ERROR']))
{
	ShowError($arResult['ERROR']);
	return false;
}

global $USER_FIELD_MANAGER;

//$GLOBALS['APPLICATION']->SetTitle('Highloadblock Row');

$listUrl = str_replace('#BLOCK_ID#', intval($arParams['BLOCK_ID']),	$arParams['LIST_URL']);
foreach($arResult['fields'] as $field):
     
     if ($field['FIELD_NAME'] == 'UF_NAME'){
        $title =$field['VALUE'];    
     }
     else if ($field['FIELD_NAME'] == 'UF_XML_ID'){
        $code =$field['VALUE'];    
     }
     
endforeach; 				
?>
<div class="header-cities current" data-marker="location-current" data-code="<?=$code?>" data-action="cities-toggle" rel="nofollow">
    <i data-svg="geo"></i><span><?=$title?></span>
</div>
<?$APPLICATION->IncludeComponent("bitrix:highloadblock.list","cities",Array(
		"BLOCK_ID" => $arParams['BLOCK_ID'],
		"CHECK_PERMISSIONS" => "Y",
		"ACTIVE"=>$code,
		"SORT_FIELD"=>"SORT",
		"SORT_ORDER"=>"ASC",
		"DETAIL_URL" => "detail.php?BLOCK_ID=#BLOCK_ID#&ROW_ID=#ID#",
		"FILTER_NAME" => "myfilter",
		"PAGEN_ID" => "page",	
	)
);?>
