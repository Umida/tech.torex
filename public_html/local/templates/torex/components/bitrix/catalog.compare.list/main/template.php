<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$itemCount = count($arResult);
$needReload = (isset($_REQUEST["compare_list_reload"]) && $_REQUEST["compare_list_reload"] == "Y");
$idCompareCount = 'compareList'.$this->randString();
$obCompare = 'ob'.$idCompareCount;

$curPage = $APPLICATION->GetCurPage(true);
$style = ($itemCount == 0 ? ' style="display: none;"' : '');

?>

<div class="header-catalog__btn catalog-compare-count">
     <?if (strpos($curPage, 'compare/') === false):?>
    <a href="<?=$arParams["COMPARE_URL"]; ?>">
        <?endif;?>
    <i data-svg="comp-header"></i>
    <div class="basket-line-count catalog-compare-count" <?=$style?> id="<?=$idCompareCount; ?>"><span data-block="count">
<?
unset($style, $mainClass);

if ($needReload)
{
	$APPLICATION->RestartBuffer();
}

$frame = $this->createFrame($idCompareCount)->begin('');

if ($itemCount > 0)
{
	?>
	<?=$itemCount; ?>
	<?}
	$frame->end();?>
	
		</span></div>
		<?if (strpos($curPage, 'compare/') === false):?>
</a>
 <?endif;?>
<?


if ($needReload)
{
	die();
}
$currentPath = CHTTP::urlDeleteParams(
	$APPLICATION->GetCurPageParam(),
	array(
		$arParams['PRODUCT_ID_VARIABLE'],
		$arParams['ACTION_VARIABLE'],
		'ajax_action'
	),
	array("delete_system_params" => true)
);

$jsParams = array(
	'VISUAL' => array(
		'ID' => $idCompareCount,
	),
	'AJAX' => array(
		'url' => $currentPath,
		'params' => array(
			'ajax_action' => 'Y'
		),
		'reload' => array(
			'compare_list_reload' => 'Y'
		),
		'templates' => array(
			'delete' => (mb_strpos($currentPath, '?') === false ? '?' : '&').$arParams['ACTION_VARIABLE'].'=DELETE_FROM_COMPARE_LIST&'.$arParams['PRODUCT_ID_VARIABLE'].'='
		)
	),
	'POSITION' => array(
		'fixed' => $arParams['POSITION_FIXED'] == 'Y',
		'align' => array(
			'vertical' => $arParams['POSITION'][0],
			'horizontal' => $arParams['POSITION'][1]
		)
	)
);
?>
	<script type="text/javascript">
		var <?=$obCompare; ?> = new JCCatalogCompareList(<? echo CUtil::PhpToJSObject($jsParams, false, true); ?>)
	</script>
</div>