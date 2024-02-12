<?php

use Bitrix\Main\Localization\Loc;
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */
$this->setFrameMode(true);
$emptyImagePath = $this->getFolder().'/images/tile-empty.svg';
$arParams['TITLE_MESSAGE'] = $arParams['TITLE_MESSAGE'] ?? Loc::getMessage('CT_BCSL_TITLE_MESSAGE');

if ($arResult['SECTIONS_COUNT'] > 0)
{
	$mainId = $this->GetEditAreaId($arResult['SECTION']['ID'].'_'.$arResult['AREA_ID_ADDITIONAL_SALT']);
	$visual = [
		'ID' => $mainId
	];
	$obName = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);

	if ($arParams['OFFSET_MODE'] == 'D')
	{
		$templateData = [
			'JS_OBJ' => $obName,
			'REQUEST_KEY' => $arParams['OFFSET_VARIABLE']
		];
	}

	?>
	
	<div class="catalog-sections-container">
			<?php
			$sectionEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'SECTION_EDIT');
			$sectionDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'SECTION_DELETE');
			$sectionDeleteParams = [
				'CONFIRM' => Loc::getMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'),
			];

			$sectionNumber = 0;
			foreach ($arResult['SECTIONS'] as &$section)
			{
			   
				$this->addEditAction($section['ID'], $section['EDIT_LINK'], $sectionEdit);
				$this->addDeleteAction($section['ID'], $section['DELETE_LINK'], $sectionDelete, $sectionDeleteParams);

				if (!empty($section['PICTURE']))
				{
					$xResizedImage = \CFile::ResizeImageGet(
						$section['PICTURE'],
						[
							'width' => 280,
							'height' => 196,
						]
					);

					$x2ResizedImage = \CFile::ResizeImageGet(
						$section['PICTURE'],
						[
							'width' => 560,
							'height' => 382,
						]
					);

					if (!$xResizedImage || !$x2ResizedImage)
					{
						$xResizedImage = [
							'src' => $section['PICTURE']['SRC'],
						];
						$x2ResizedImage = $xResizedImage;
					}

					$xResizedImage = \Bitrix\Iblock\Component\Tools::getImageSrc([
						'SRC' => $xResizedImage['src']
					]);
					$x2ResizedImage = \Bitrix\Iblock\Component\Tools::getImageSrc([
						'SRC' => $x2ResizedImage['src']
					]);

					$style = "background-image: url('{$xResizedImage}');";
					$style .= "background-image: -webkit-image-set(url('{$xResizedImage}') 1x, url('{$x2ResizedImage}') 2x);";
					$style .= "background-image: image-set(url('{$xResizedImage}') 1x, url('{$x2ResizedImage}') 2x);";
					
				}
				else
				{
					$section['PICTURE'] = [
						'SRC' => $emptyImagePath,
						'ALT' => ($section['IPROPERTY_VALUES']['SECTION_PICTURE_FILE_ALT'] ?? '') !== ''
							? $section['IPROPERTY_VALUES']['SECTION_PICTURE_FILE_ALT']
							: $section['NAME'],
						'TITLE' => ($section['IPROPERTY_VALUES']['SECTION_PICTURE_FILE_TITLE'] ?? '') !== ''
							? $section['IPROPERTY_VALUES']['SECTION_PICTURE_FILE_TITLE']
							: $section['NAME'],
					];

					//$style = "background-image: url('{$section['PICTURE']['SRC']}');";
					$pict = $section['PICTURE']['SRC'];
				}
				 
				if ($arParams['COUNT_ELEMENTS'] && $section['ELEMENT_CNT'] >0 ){
				   
				?>
				
				<div class="catalog-section-list-item" data-item-number="<?=$sectionNumber; ?>">
					<div class="main-section-img-container">
						<a
							href="<?= $section['SECTION_PAGE_URL'] ?>"
							class="catalog-section-item-img"
							style="<?= $style ?>"
							title="<?= $section['PICTURE']['TITLE'] ?>"
						>
						</a>
					</div>
					
					<div class="main-section-desc">
					    <div class="main-section-desc-container">
					        	<span class="main-section-desc-purpose"><? if ($section['IBLOCK_SECTION_ID'] =='16'):?>Для квартиры<? elseif ($section['IBLOCK_SECTION_ID'] =='17'):?>Для дома<? endif;?></span>
								<span class="main-section-desc-counter"><?=$section['ELEMENT_CNT']?> дверей</span>
							
					    </div>
					    <div>
					        <h3 class="catalog-section-item-title"><?=$section['NAME']?></h3>
					    </div>
					    <div class="catalog-section-item-desc">
								<span><?=$section['DESCRIPTION']?></span>
					    </div>
					    <div class="catalog-section-item-price">
					        <span>от <?=$section['PRICE']?></span>
					    </div>
					   <div class="catalog-section-item-btn">
					       <a class="brand-white-btn">Подробнее</a>
					   </div>
					</div>
				</div>
				<?php
				}
				$sectionNumber++;
			}
			unset($section);
			?>
	
	</div>
	<?php
	$jsParams = [
		'offsetMode' => $arParams['OFFSET_MODE'],
		'settings' => [
			'maxCount' => $arResult['SECTIONS_COUNT']
		],
		'visual' => array_change_key_case($visual, CASE_LOWER)
	];
	if ($arParams['OFFSET_MODE'] == 'F')
	{
		$jsParams['settings']['offset'] = $arParams['OFFSET_VALUE'];
	}
	?><script>
	var <?=$obName?> = new JCCatalogSectionListStoreComponent(<?=CUtil::PhpToJSObject($jsParams, false, true, true)?>);
</script><?php
}
