<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 * @global CMain $APPLICATION
 */

global $APPLICATION;

//delayed function must return a string
if(empty($arResult))
	return "";

$strReturn = '';


//we can't use $APPLICATION->SetAdditionalCSS() here because we are inside the buffered function GetNavChain()
$strReturn .= '<ul class="bx-breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList" >';

$itemSize = count($arResult);
$start = 0;


   
for($index = $start; $index < $itemSize; $index++)
{
    
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);
	$arrow = ($index > $start? '<i>—</i>' : '');

	if($arResult[$index]["LINK"] <> "" && $index != $itemSize-1)
	{
		$strReturn .= '
			<li class="bx-breadcrumb-item" id="bx_breadcrumb_'.$index.'" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
				'.$arrow.'
				<a href="'.$arResult[$index]["LINK"].'" title="'.$title.'" itemprop="item">
					<span itemprop="name">'.$title.'</span>
				</a>
				<meta itemprop="position" content="'.($index + 1).'">
			</li>';
	}
	else
	{
		$strReturn .= '
			<li class="bx-breadcrumb-item">
				'.$arrow.'
				<span>'.$title.'</span>
			</li>';
	}
}

$strReturn .= '</ul>';

return $strReturn;
