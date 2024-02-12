
<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $templateData */
/** @var @global CMain $APPLICATION */
if($arResult["FLT_VALUES"]){
	global $APPLICATION;

	$option = '';
	if ($arResult["FLT_VALUES"]["purpose"] && $arResult["FLT_VALUES"]["purpose"] != 'Межкомнатные')
	 $option = ' '.mb_convert_case($arResult["FLT_VALUES"]["purpose"], MB_CASE_LOWER, "UTF-8");
	if ($arResult["FLT_VALUES"]["vendor"] && $arResult["FLT_VALUES"]["vendor"] != 'Torex')
	 $option .= ' '.$arResult["FLT_VALUES"]["vendor"];
	if ($arResult["FLT_VALUES"]["seria"])
	 $option .= ' '.$arResult["FLT_VALUES"]["seria"]; 

	if($arResult["FLT_VALUES"]["price"]):
		$title=GetMessage('TITLE_WITH_PRICE_AND_PURPOSE',array('#price#'=>mb_convert_case($arResult["FLT_VALUES"]["price"], MB_CASE_LOWER, "UTF-8"),'#purpose#'=>$option));
		$description=GetMessage('TITLE_WITH_PRICE_AND_PURPOSE',array('#price#'=>$arResult["FLT_VALUES"]["price"],'#purpose#'=>$option));
		
	elseif($arResult["FLT_VALUES"]["purpose"] && $arResult["FLT_VALUES"]["purpose"] != 'Межкомнатные'):
		$title=GetMessage('TITLE_WITH_PURPOSE',array('#purpose#'=>$option));
		$description=GetMessage('DESCRIPTION_WITH_PURPOSE',array('#purpose#'=>$option));
		
	elseif(($arResult["FLT_VALUES"]["purpose"] && $arResult["FLT_VALUES"]["purpose"] == 'Межкомнатные') ):
	    $title=GetMessage('TITLE_MKD',array('#purpose#'=>$option));
		$description=GetMessage('DESCRIPTION_MKD',array('#purpose#'=>$option));
	endif;
	
	if ($title){
	$APPLICATION->SetPageProperty("flt_title", $title);
	$APPLICATION->SetPageProperty("flt_description", $description);


		$APPLICATION->SetPageProperty("title", $title);
	
		echo '<script type="text/javascript">
		$(document).ready(function(){
		$("h1").text("'.$title.'"); });</script>';

}

}

CJSCore::Init(array('fx', 'popup'));
?>