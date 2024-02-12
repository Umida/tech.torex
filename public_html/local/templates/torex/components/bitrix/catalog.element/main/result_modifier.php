<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();
 

if ($arResult['DISPLAY_PROPERTIES']['MODEL']){
  $reviewFilter = ["IBLOCK_ID" => 10, "%PROPERTY_254_VALUE" => $arResult['DISPLAY_PROPERTIES']['MODEL']['VALUE']];
  $reviewres = CIBlockElement::GetList([], $reviewFilter, false, false, []);
  $data = [];
  $arResult['REVIEWS_COUNT'] = $reviewres->SelectedRowsCount();
  if ( $arResult['REVIEWS_COUNT'] > 0) {
      $totalScore = 0;
  while ($review_obj = $reviewres->GetNextElement()) {
        $reviewFields = $review_obj->GetFields();
        $reviewProps = $review_obj->GetProperties();
        $totalScore += $reviewProps['score']['VALUE'];
        $arResult['REVIEWS'][] = ['ID'=>$reviewFields['ID'],'DETAIL_PAGE_URL'=>$reviewFields['DETAIL_PAGE_URL'],'TITLE'=>$reviewFields['NAME'],'TEXT'=>$reviewFields['DETAIL_TEXT'],'PROPERTIES'=>$reviewProps];
    }
   
    $arResult['REVIEWS_TOTAL_SCORE'] =round($totalScore/$arResult['REVIEWS_COUNT']);
    
  }
}



foreach ($arResult['OFFERS'] as $ind => $jsOffer)
{

foreach ($arResult['SKU_PROPS']['CML2_SIZE']['VALUES'] as $index => $sizeName)
{
    if ($sizeName['XML_ID'] == $jsOffer['PROPERTIES']['CML2_SIZE']['VALUE']){
        $sizeTitle = $sizeName['NAME'].', ';
        break;
    }
}
      $arResult['JS_OFFERS'][$ind]['TITLE'] = "Входная дверь ".mb_strtolower($arResult['PROPERTIES']['PURPOSE']['VALUE'])." ".$arResult['PROPERTIES']['VENDOR']['DISPLAY_VALUE']." ".$arResult['NAME'].' '.$sizeTitle.'открывание '.mb_strtolower($jsOffer['PROPERTIES']['CML2_SIDE']['VALUE']);
      
}
if ($arResult['PROPERTIES']['MODEL']['VALUE']){
  $modelFilter = ["IBLOCK_ID" => 9, "ID" => $arResult['PROPERTIES']['MODEL']['VALUE']];
    $modelres = CIBlockElement::GetList([], $modelFilter, false, /*$arNavStartParams ?? */ false, []);

    $data = [];
$arResult['MODEL'] = [];

    while ($model_obj = $modelres->GetNextElement()) {
        $modelFields = $model_obj->GetFields();
        $modelProps = $model_obj->GetProperties();
        $model_name = $modelFields['NAME'];
        $arResult['MODEL']['NAME'] = $modelFields['NAME'];
        $arResult['MODEL']['DESC'] = $modelFields['DETAIL_TEXT'];
        $arResult['MODEL']['PREVIEW'] = $modelFields['PREVIEW_TEXT'];
        $arResult['MODEL']['VIDEO_ID'] = $modelProps['video_id']["VALUE"];
        $arResult['MODEL']['VIDEO_TIMER'] = $modelProps['video_timer']["VALUE"];
        foreach ($modelProps['serts']['VALUE'] as $key=>$sert){
            $arResult['MODEL']['DOCS']['LINK'][$key] = CFile::GetPath($sert);    
            $arResult['MODEL']['DOCS']['DESC'][$key] = $modelProps['serts']['DESCRIPTION'][$key];
        }
        
    }
    $photosFilter = array("IBLOCK_ID" => 43,'PROPERTY_667_VALUE'=>$arResult['PROPERTIES']['model']['VALUE']);
    $photosres = CIBlockElement::GetList(array(), $photosFilter, false, [], []);
        while ($photos_obj = $photosres->GetNextElement()) {
            $photosFields = $photos_obj->GetFields();
            
            $arResult['MODEL']['model_pics'][] = CFile::GetPath($photosFields['DETAIL_PICTURE']);
            //$photosProps = $photos_obj->GetProperties();
            
        }





$other_res = CIBlockElement::GetList([], ["IBLOCK_ID"=>2,"!ID"=>$arResult['ID'],"PROPERTY_85_VALUE"=>$arResult['PROPERTIES']['MODEL']['VALUE'], "ACTIVE"=>"Y","CATALOG_AVAILABLE"=>"Y","PROPERTY_203_VALUE"=>$arResult['DISPLAY_PROPERTIES']['DESIGNOUTSIDE']['VALUE'],"PROPERTY_204_VALUE"=>$arResult['DISPLAY_PROPERTIES']['DESIGNINSIDE']['VALUE'],"PROPERTY_192"=>false], false, [], ["DETAIL_PAGE_URL"]);
while($other_ob = $other_res->GetNextElement())
{
	$other_Fields = $other_ob->GetFields();
	$other_Properties = $other_ob->GetProperties();
	$arResult['other'][]=["URL"=>$other_Fields["DETAIL_PAGE_URL"],"PICT"=>CFile::GetPath($other_Properties['MAIN_PHOTO_R']['VALUE'])];
}
unset($other_Fields,$other_Properties);
}
?>

