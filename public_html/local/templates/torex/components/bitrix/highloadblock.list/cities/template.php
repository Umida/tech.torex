<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
{
	die();
}

/** @global CMain $APPLICATION */
/** @var array $arParams */
/** @var array $arResult */


if (!empty($arResult['ERROR']))
{
	echo $arResult['ERROR'];
	return false;
}

?>
<div class="cities" data-view="default">
	<div class="body">
	    <?php
	foreach ($arResult['rows'] as $row):
	   
		?>
				<a href="javascript:void(0)" class="js-city<?=($arParams['ACTIVE']==$row['UF_XML_ID']?' active':'')?>" data-code="<?=$row['UF_XML_ID'];?>" rel="nofollow"><span><?=$row['UF_NAME'];?></span></a>

			<?php
	endforeach;
	?>

    	</div>
</div>
