<?php
error_reporting(E_ALL);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
Cmodule::IncludeModule('catalog');
Cmodule::IncludeModule('sale');
CModule::IncludeModule('highloadblock');

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;


if (is_file('../../../php-query-master/phpQuery/phpQuery.php')) {
    require_once('../../../php-query-master/phpQuery/phpQuery.php');
} else {
    die('Required phpQueryLib is missing');
}


$log = [];




$config = json_decode(file_get_contents('catalog.json'));
$config->iteration++;



$domain = 'https://torex.ru';


# Processing
# 1 stage
switch ($config->stage) {
    case 1:
        
    $page = '/catalog/';
    
    $opts = array('https' => array('header' => 'Accept-Charset: UTF-8, *;q=0'));
    $context = stream_context_create($opts);
    $source = file_get_contents($domain . $page, false, $context);
    
    
    $html = phpQuery::newDocument($source);
    # ...

    # Processing
    $categories = [];
    $pq_categories = pq($html)->find('#filter_series a');
    $cnt_category = 0;
 
    foreach ($pq_categories as $key => $pq_category) {

        $categories[] = array(
            'title' => pq($pq_category)->text(),
            'href' => pq($pq_category)->attr('href'),
            'products' => [],
        );
           
        $cnt_category++;
    }

    $config->categories = $categories;
    $config->date_modify_from = date("Y-m-d H:i:s");
    $config->marker->one = array('all' => $cnt_category, 'parts' => 0, 'current' => 0, 'part' => 0, 'childs' => 0);

  

    $config->stage++;
    file_put_contents('catalog.json', json_encode($config));



   exit;


        break;

    case 2:
    

    # Load page
    $opts = array('http' => array('header' => 'Accept-Charset: UTF-8, *;q=0'));
    $context = stream_context_create($opts);

    if ($config->marker->one->part == 0) {
      
       $source = file_get_contents($domain . $config->categories[$config->marker->one->current]->href, false, $context);
    } else {
      
       $source = file_get_contents($domain . $config->categories[$config->marker->one->current]->href . '?page=' . $config->marker->one->part, false, $context);
    }
    $html = phpQuery::newDocument($source);
    # ...

    # Processing
    # Update parts
    if ($config->marker->one->part == 0) {
        $pq_counts = pq($html)->find('.cp-navigation-1 a');

        $config->marker->one->parts = count($pq_counts);
        $config->marker->one->part = 1;
    }

    # Update products
    $pq_products = pq($html)->find('.cp-catalog-1 .cp-doors-1');
    $cnt_products = 0;

    foreach ($pq_products as $key => $pq_product) {
        $products = array('title' => pq($pq_product)->find('img')->attr('alt'), 'href' => pq($pq_product)->find('a')->attr('href'));
        array_push($config->categories[$config->marker->one->current]->products, $products);
        $cnt_products++;
    }


    # Update childs
    $config->marker->one->childs = $config->marker->one->childs + count($pq_products);


    # End
    if ($config->marker->one->part + 1 > $config->marker->one->parts) {
        if ($config->marker->one->current + 1 >= $config->marker->one->all) {
            
            $model_obj = CIBlockElement::GetList([], ["IBLOCK_ID" => 9], false, [], []);
            if ($model_obj->SelectedRowsCount() > 0) {
                while ($model = $model_obj->GetNextElement()) {
                    $modelFields = $model->GetFields();
                    
                    CIBlockElement::SetPropertyValuesEx($modelFields['ID'], false, ['filled'=>'']);
                }
            }
            
            $config->stage++;
            $config->marker->two = array('all' => $config->marker->one->all, 'parts' => 0, 'current' => 0, 'part' => 0);
        } else {
            $config->marker->one->current++;

            $config->marker->one->parts = 0;
            $config->marker->one->part = 0;
        }
    } else {
        $config->marker->one->part++;
    }
    echo "<pre>";
   print_r($config);
   echo "</pre>";
    file_put_contents('catalog.json', json_encode($config));


   exit;


        break;

    case 3:

    $idIBlock = 2;

$to  = "<umidamukumova@yandex.ru>" ;
$headers =  'MIME-Version: 1.0' . "\r\n"; 
$headers  .= "Content-type: text/html; charset=utf-8 \r\n"; 
$headers .= "From: Администрирование сайта <from@tech.torex.shop>\r\n"; 

    # Processing
    $opts = array('http' => array(
        "header" => "Accept-Charset: UTF-8, *;q=0\r\n" .
            "Cookie: BX_USER_ID=a56558cac586d0dd260c0eab4d4699c3; _gcl_au=1.1.1300718587.1614579606; _ym_uid=1614579606217820058; _ym_d=1614579606; _ga=GA1.2.1457822494.1614579606; _fbp=fb.1.1614579605960.522145161; privacy-policy=1; marquiz__url_params={}; _gid=GA1.2.664976448.1615274354; PHPSESSID=g922ft7ls5q506bjptq2j4f4d5; _dveromat_first_site_open=1615354032044; _ym_isad=1; PHPSESSID=c4acbe0dbb9ba2ea2f4b8252ab66621a; _ym_visorc=w; _cmg_csstWnDLN=1615361870; _comagic_idWnDLN=4119242956.6351560764.1615361870; CITYCODE=moskva; CITY=%D0%9C%D0%BE%D1%81%D0%BA%D0%B2%D0%B0; BITRIX_SM_PK=page_region_moskva\r\n"
    ));
    $context = stream_context_create($opts);
    if (get_http_response_code($domain . $config->categories[$config->marker->two->current]->products[$config->marker->two->part]->href) != "200"){
          if ($config->marker->two->part + 1 >= $config->marker->two->parts) {
                if ($config->marker->two->current + 1 >= $config->marker->two->all) {
                    $config->stage++;
                } else {
                    $config->marker->two->current++;
                    $config->marker->two->parts = 0;
                    $config->marker->two->part = 0;
                }
                } else {
                    $config->marker->two->part++;
                }
        return;
    }
        $source = file_get_contents($domain . $config->categories[$config->marker->two->current]->products[$config->marker->two->part]->href, false, $context);
        $html = phpQuery::newDocument($source);

        # Update parts
        if ($config->marker->two->part == 0) {
            $config->marker->two->parts = count($config->categories[$config->marker->two->current]->products);
        }
        $product_id = pq($html)->find('meta[itemprop="productID]')->attr('content');
        if (!$product_id){
            $subject = "Некорректные данные"; 
            $message = ' <p>У элемента нет product_id</p>';
            mail($to, $subject, $message, $headers); 
            if ($config->marker->two->part + 1 >= $config->marker->two->parts) {
                if ($config->marker->two->current + 1 >= $config->marker->two->all) {
                    $config->stage++;
                } else {
                    $config->marker->two->current++;
                    $config->marker->two->parts = 0;
                    $config->marker->two->part = 0;
                }
                } else {
                    $config->marker->two->part++;
                }
            return;
        }
        $product_title = pq($html)->find('.represent meta[itemprop="name"]')->attr('content');
        $product_preview = pq($html)->find('.represent meta[itemprop="description"]')->attr('content');
        $product_specifications = $product_lock_specifications = '';
        $product_specifications = pq($html)->find('.card-detail__specification .tab-block_inside[data-tab="doortab"] .specification-line__lists')->html();
        $product_lock_specifications = pq($html)->find('.card-detail__specification .tab-block_inside[data-tab="locktab"] .specification-line__lists')->html();
       
        $product_specifications_arr = [];
        foreach (pq($product_specifications)->find('.specification-line') as $key => $pq_product) {
            $products_title = trim(pq($pq_product)->find('.specification-line_left')->text());
            $products_value = trim(pq($pq_product)->find('.specification-line_right')->text());
            $prop_key = -1;
            $product_specifications_arr[$products_title] = $products_value;
        }
        $product_specifications_lock_arr = [];
        foreach (pq($product_lock_specifications)->find('.specification-line') as $key => $pq_product) {
            $products_title = trim(pq($pq_product)->find('.specification-line_left')->text());
            $products_value = trim(pq($pq_product)->find('.specification-line_right')->text());
            $prop_key = -1;
            $product_specifications_lock_arr[$products_title] = $products_value;
        }
        foreach (pq($model_photos)->find('.foto-element') as $model_photo) {
            $model_photo_path[] = trim(pq($model_photo)->find('img')->attr('src'));
        }

        $seria_id = $model_id = 0;
        $model_name = "";
        #get model
        echo "<pre>";
        print_r($product_specifications_arr);
        echo "</pre>";
        
        
         
        $model_feature = getModel(9,["IBLOCK_ID" => 9, 'PROPERTY_120_VALUE' => strtolower(trim(preg_replace('/\s+/', ' ', $product_specifications_arr['Модель'])))],$product_specifications_arr,$product_specifications_lock_arr,pq($html)->find('.document-wrap a'));
        
       
        
        $color_outside = $product_specifications_arr['Цвет внешней отделки'];
        if ($color_outside) {
            list($color_outside_code, $color_outside_id, $color_outside_name) = getHLprop(2, "UF_SYNONYMS", $color_outside);
        }

        $design_outside = $product_specifications_arr['Рисунок внешней отделки'];
        if ($design_outside) {
            list($design_outside_code, $design_outside_id, $design_outside_name) = getHLprop(10, "UF_SYNONYMS", $design_outside);

        }

        $color_inside = $product_specifications_arr['Цвет внутренней отделки'];
        if ($color_inside) {
            list($color_inside_code, $color_inside_id, $color_inside_name) = getHLprop(2, "UF_SYNONYMS", $color_inside);
        }

        $design_inside = $product_specifications_arr['Рисунок внутренней отделки'];
        if ($design_inside) {
            list($design_inside_code, $design_inside_id, $design_inside_name) = getHLprop(10, "UF_SYNONYMS", $design_inside);
        }

        $fitting = $product_specifications_arr['Цвет фурнитуры'];
        if (!$fitting)
            $fitting = 'Хром';
        
        list($fitting_code, $fitting_id, $fitting_name) = getHLprop(11, "UF_SYNONYMS", $fitting);
        

        $trim_color = $product_specifications_arr['Цвет наличника'];
        if (!$trim_color)
            $trim_color = '-';
           
        list($trim_color_code, $trim_color_id, $trim_color_name) = getHLprop(2, "UF_SYNONYMS", $trim_color);
        
        $outside_image_url = pq($html)->find('.card-product__represent .card-product__outside .card-product__picture a img')->attr('src');
        $outside_image = downloadFile($outside_image_url);
       
        $inside_image_url = pq($html)->find('.card-product__represent .card-product__inside .card-product__picture a img')->attr('src');
        
        $inside_image = downloadFile($inside_image_url);
        echo "<br>";
        echo $outside_image_url;
        echo "<br>";
        echo $inside_image_url;
        echo "<br>";
        drawImage($outside_image['location'], $inside_image['location'], $product_id. '_r_image_res.jpg');
          
        
        $el = new CIBlockElement;
            $findprods = searchProduct(["IBLOCK_ID"=>2,"PROPERTY_197_VALUE" => $product_id]);
           
            if ($findprods){
                $prodFields = $findprods['prodFields'];
                $prodProps = $findprods['prodProps'];
                
                $setting =0;
                if ($color_outside_code && $prodProps['COLOROUTSIDE']['VALUE'] == $color_outside_code){
                    
                    ++ $setting;
                } 
                if ($design_outside_code && $prodProps['DESIGNOUTSIDE']['VALUE'] == $design_outside_code){
                    ++ $setting;
                }
                if ($color_inside_code && $prodProps['COLORINSIDE']['VALUE'] == $color_inside_code){
                    ++ $setting;
                }
                if ($design_inside_code && $prodProps['DESIGNINSIDE']['VALUE'] == $design_inside_code){
                    ++ $setting;
                }
                if ($fitting_code && $prodProps['FITTING']['VALUE'] == $fitting_code){
                    ++ $setting;
                }
                if ($trim_color_code && $prodProps['TRIMCOLOR']['VALUE'] == $trim_color_code){
                    ++ $setting;
                }
                
                if ($setting == 6){
                    $arLoadProductArray = [];
                    $arLoadProductArray = array(
                        "MODIFIED_BY" => 1
                    );
                    $ID = $prodFields["ID"];
                    $res = $el->Update($ID, $arLoadProductArray);
                    $old_pos = 1;
                } else {
                    $n_props = [];
                    $n_props["DONORCODE"] = $product_id . "_old";
                    $subject = "Обновили элемент каталога"; 
                    $message = ' <p>Элемент каталога '.$product_id.' был изменен на сайте производителя.</p></br>Предыдущая позиция <a href="https://torex.shop/'.$prodFields["CODE"].'">'.$prodFields["CODE"]."</a> (".$prodFields["ID"].")";
                    mail($to, $subject, $message, $headers); 
                    CIBlockElement::SetPropertyValuesEx($prodFields["ID"], false, $n_props);
                }
            }
        
        if (!$ID) {
           $findprods =  searchProduct(["IBLOCK_ID"=>2,"PROPERTY_85_VALUE" => $model_feature['model_id'],"PROPERTY_150_VALUE" => $color_outside_code, "PROPERTY_203_VALUE" => $design_outside_code, "PROPERTY_151_VALUE" => $color_inside_code,"PROPERTY_204_VALUE" => $design_inside_code,"PROPERTY_205_VALUE" => $fitting_code,"PROPERTY_206_VALUE" => $trim_color_code, "PROPERTY_192_VALUE" => false]);
           if ($findprods == 0){
               $ID = addNewDoor($idIBlock,$model_feature,['image'=>$product_id. '_r_image_res.jpg','color_outside_id'=>$color_outside_code,'design_outside_id'=>$design_outside_code,'color_inside_id'=>$color_inside_code,'design_inside_id'=>$design_inside_code,'fitting_id'=>$fitting_code,'jamb_color'=>$trim_color_code,'product_id'=>$product_id]);
                $newpos = 1;
           }
           else{
               $prodFields = $findprods['prodFields'];
               $prodProps = $findprods['prodProps'];
              
                    $arLoadProductArray = [];
                    $arLoadProductArray = array(
                        "MODIFIED_BY" => 1
                    );
                    $ID = $prodFields["ID"];
                    $res = $el->Update($ID, $arLoadProductArray);
                    $old_pos = 1;
                
           }
        }
         
        $product_price = floatval(pq($html)->find('[itemprop="offers"] meta[itemprop="price"]')->attr('content'));

        $arFields_new = array("ACTIVE" => "Y", "QUANTITY" => 1);
        CCatalogProduct::Update($ID, array('QUANTITY' => 1, "ACTIVE" => "Y"));

        setOfferPrice(["IBLOCK_ID" => 3, "PROPERTY_19_VALUE" => $ID, "PROPERTY_22" => 7], 1, $product_price);
        
        $opts_kaluga = array('http' => array(
            "header" => "Accept-Charset: UTF-8, *;q=0\r\n" .
                "Cookie: BX_USER_ID=f0572d9bd63f640fca583267d5a8fa43; _ga=GA1.2.783765956.1625486705; _ym_uid=162548670592192337; privacy-policy=1; _gcl_au=1.1.1866649110.1641383027; _ym_d=1641383028; PHPSESSID=37s9s2fk2t4n5q08d5n773j5h2; promosys_opened_params=a%3A0%3A%7B%7D; _dveromat_first_site_open=1643356699396; _gid=GA1.2.461249296.1643356705; _ym_visorc=w; _ym_isad=1; marquiz__url_params={}; CITYCODE=kaluga; CITY=%D0%9A%D0%B0%D0%BB%D1%83%D0%B3%D0%B0; BITRIX_SM_PK=page_region_kaluga; _dc_gtm_UA-26925054-1=1; _gat_UA-26925054-6=1; _gat_UA-26925054-1=1\r\n"
        ));
        
        $context_kaluga = stream_context_create($opts_kaluga);
        $source_kaluga = file_get_contents($domain . $config->categories[$config->marker->two->current]->products[$config->marker->two->part]->href, false, $context_kaluga);
        $html_kaluga = phpQuery::newDocument($source_kaluga);
        $kaluga_price = floatval(pq($html_kaluga)->find('[itemprop="offers"] meta[itemprop="price"]')->attr('content'));
        
        setOfferPrice(["IBLOCK_ID" => 3, "PROPERTY_19_VALUE" => $ID, "PROPERTY_22" => 7], 2, $kaluga_price);

        $opts_spb = array('http' => array(
            "header" => "Accept-Charset: UTF-8, *;q=0\r\n" .
                "Cookie: BX_USER_ID=f0572d9bd63f640fca583267d5a8fa43; _ga=GA1.2.783765956.1625486705; _ym_uid=162548670592192337; privacy-policy=1; _gcl_au=1.1.1866649110.1641383027; _ym_d=1641383028; PHPSESSID=37s9s2fk2t4n5q08d5n773j5h2; promosys_opened_params=a%3A0%3A%7B%7D; _dveromat_first_site_open=1643356699396; _gid=GA1.2.461249296.1643356705; _ym_visorc=w; _ym_isad=1; marquiz__url_params={}; CITYCODE=sankt-peterburg; CITY=%D0%A1%D0%B0%D0%BD%D0%BA%D1%82-%D0%9F%D0%B5%D1%82%D0%B5%D1%80%D0%B1%D1%83%D1%80%D0%B3; BITRIX_SM_PK=page_region_sankt-peterburg; _dc_gtm_UA-26925054-1=1; _gat_UA-26925054-6=1; _gat_UA-26925054-1=1\r\n"
        ));
        $context_spb = stream_context_create($opts_spb);
        $source_spb = file_get_contents($domain . $config->categories[$config->marker->two->current]->products[$config->marker->two->part]->href, false, $context_spb);
        $html_spb = phpQuery::newDocument($source_spb);
        $spb_price = floatval(pq($html_spb)->find('[itemprop="offers"] meta[itemprop="price"]')->attr('content'));
        
        setOfferPrice(["IBLOCK_ID" => 3, "PROPERTY_19_VALUE" => $ID, "PROPERTY_22" => 7], 3, $spb_price);

        $opts_arh = array('http' => array(
            "header" => "Accept-Charset: UTF-8, *;q=0\r\n" .
                "Cookie: BX_USER_ID=f0572d9bd63f640fca583267d5a8fa43; _ga=GA1.2.783765956.1625486705; _ym_d=1625486705; _ym_uid=162548670592192337; _fbp=fb.1.1625486705555.1328147552; privacy-policy=1; _gcl_au=1.1.2014156727.1633598043; _gid=GA1.2.994941324.1633598043; _ym_isad=1; promosys_opened_params=a%3A0%3A%7B%7D; PHPSESSID=qn519fsv1kcujbdfku77cfjt73; _dveromat_first_site_open=1633618057732; _ym_visorc=w; marquiz__url_params={}; _dc_gtm_UA-26925054-1=1; _gat_UA-26925054-6=1; _gat_UA-26925054-1=1; CITYCODE=arkhangelsk; CITY=%D0%90%D1%80%D1%85%D0%B0%D0%BD%D0%B3%D0%B5%D0%BB%D1%8C%D1%81%D0%BA; BITRIX_SM_PK=page_region_arhangelsk\r\n"
        ));
        $context_arh = stream_context_create($opts_arh);
        $source_arh = file_get_contents($domain . $config->categories[$config->marker->two->current]->products[$config->marker->two->part]->href, false, $context_arh);
        $html_arh = phpQuery::newDocument($source_arh);
        $arh_price = floatval(pq($html_arh)->find('[itemprop="offers"] meta[itemprop="price"]')->attr('content'));
        
        setOfferPrice(["IBLOCK_ID" => 3, "PROPERTY_19_VALUE" => $ID, "PROPERTY_22" => 7], 4, $arh_price);

        $opts_kgd = array('http' => array(
            "header" => "Accept-Charset: UTF-8, *;q=0\r\n" .
                "Cookie: BX_USER_ID=f0572d9bd63f640fca583267d5a8fa43; _ga=GA1.2.783765956.1625486705; _ym_uid=162548670592192337; privacy-policy=1; _gcl_au=1.1.1866649110.1641383027; _ym_d=1641383028; PHPSESSID=37s9s2fk2t4n5q08d5n773j5h2; promosys_opened_params=a%3A0%3A%7B%7D; _dveromat_first_site_open=1643356699396; _gid=GA1.2.461249296.1643356705; _ym_visorc=w; _ym_isad=1; CITYCODE=kaliningrad; CITY=%D0%9A%D0%B0%D0%BB%D0%B8%D0%BD%D0%B8%D0%BD%D0%B3%D1%80%D0%B0%D0%B4; BITRIX_SM_PK=page_region_kaliningrad; _gat_UA-26925054-1=1; _dc_gtm_UA-26925054-1=1; _gat_UA-26925054-6=1; marquiz__url_params={}\r\n"
        ));
        $context_kgd = stream_context_create($opts_kgd);
        $source_kgd = file_get_contents($domain . $config->categories[$config->marker->two->current]->products[$config->marker->two->part]->href, false, $context_kgd);
        $html_kgd = phpQuery::newDocument($source_kgd);
        $kgd_price = floatval(pq($html_kgd)->find('[itemprop="offers"] meta[itemprop="price"]')->attr('content'));
        
        setOfferPrice(["IBLOCK_ID" => 3, "PROPERTY_19_VALUE" => $ID, "PROPERTY_22" => 7], 5, $kgd_price);
        
        $opts_tver = array('http' => array(
            "header" => "Accept-Charset: UTF-8, *;q=0\r\n" .
                "Cookie: BX_USER_ID=f0572d9bd63f640fca583267d5a8fa43; _ga=GA1.2.783765956.1625486705; _ym_uid=162548670592192337; privacy-policy=1; _gcl_au=1.1.1866649110.1641383027; _ym_d=1641383028; PHPSESSID=37s9s2fk2t4n5q08d5n773j5h2; promosys_opened_params=a%3A0%3A%7B%7D; _dveromat_first_site_open=1643356699396; _gid=GA1.2.461249296.1643356705; _ym_visorc=w; _ym_isad=1; CITYCODE=tver; CITY=%D0%A2%D0%B2%D0%B5%D1%80%D1%8C; BITRIX_SM_PK=page_region_tver; _gat_UA-26925054-1=1; _dc_gtm_UA-26925054-1=1; _gat_UA-26925054-6=1; marquiz__url_params={}\r\n"
        ));
        $context_tver = stream_context_create($opts_tver);
        $source_tver = file_get_contents($domain . $config->categories[$config->marker->two->current]->products[$config->marker->two->part]->href, false, $context_tver);
        $html_tver = phpQuery::newDocument($source_tver);
        $tver_price = floatval(pq($html_tver)->find('[itemprop="offers"] meta[itemprop="price"]')->attr('content'));

        setOfferPrice(["IBLOCK_ID" => 3, "PROPERTY_19_VALUE" => $ID, "PROPERTY_22" => 7], 6, $tver_price);

    # End
   
    if ($config->marker->two->part + 1 >= $config->marker->two->parts) {
        if ($config->marker->two->current + 1 >= $config->marker->two->all) {
            $config->stage++;
        } else {
            $config->marker->two->current++;

            $config->marker->two->parts = 0;
            $config->marker->two->part = 0;
        }
    } else {
        $config->marker->two->part++;
    }

    # ...

    # Result
    file_put_contents('catalog.json', json_encode($config));


   exit;


        break;

    case 4:
/*
    $prodFilter = array("IBLOCK_ID" => 2, "!PROPERTY_integration_VALUE" => "Да", "!PROPERTY_492_VALUE" => false, "PROPERTY_vendor_VALUE" => "Torex", "QUANTITY" => 1);
    $new_props = [];
    $prod_res = CIBlockElement::GetList(array(), $prodFilter, false, [], []);

    while ($prod_obj = $prod_res->GetNextElement()) {
        $prodFields = $prod_obj->GetFields();
        $prodProps = $prod_obj->GetProperties();

        $opts = array('http' => array(
            "header" => "Accept-Charset: UTF-8, *;q=0\r\n" .
                "Cookie: BX_USER_ID=a56558cac586d0dd260c0eab4d4699c3; _gcl_au=1.1.1300718587.1614579606; _ym_uid=1614579606217820058; _ym_d=1614579606; _ga=GA1.2.1457822494.1614579606; _fbp=fb.1.1614579605960.522145161; privacy-policy=1; marquiz__url_params={}; _gid=GA1.2.664976448.1615274354; PHPSESSID=g922ft7ls5q506bjptq2j4f4d5; _dveromat_first_site_open=1615354032044; _ym_isad=1; PHPSESSID=c4acbe0dbb9ba2ea2f4b8252ab66621a; _ym_visorc=w; _cmg_csstWnDLN=1615361870; _comagic_idWnDLN=4119242956.6351560764.1615361870; CITYCODE=moskva; CITY=%D0%9C%D0%BE%D1%81%D0%BA%D0%B2%D0%B0; BITRIX_SM_PK=page_region_moskva\r\n"
        ));
        $context = stream_context_create($opts);
        echo $prodFields["ID"];
        echo "<br/>";
        echo 'donor_code' . $prodProps["DONORCODE"]['VALUE'];
        echo "<br/>";
        if ($prodProps["DONORCODE"]['VALUE'] && get_http_response_code($domain . '/catalog/product/' . $prodProps["DONORCODE"]['VALUE'] . '/') == "404") {
            delDoorOffer($prodFields["ID"]);
            //удалить все предложения "под заказ"

        }

    }
    */
    
    $config->stage = 1;
    
    file_put_contents('catalog.json', json_encode($config));


    exit;
}
function translit($st)
{
    $st = mb_strtolower($st, "utf-8");
    $st = str_replace([
        ' ', '?', '!', '.', ',', ':', ';', '*', '(', ')', '{', '}', '[', ']', '%', '#', '№', '@', '$', '^', '+', '/', '\\', '=', '|', '"', '\'',
        'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'з', 'и', 'й', 'к',
        'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х',
        'ъ', 'ы', 'э', ' ', 'ж', 'ц', 'ч', 'ш', 'щ', 'ь', 'ю', 'я'
    ], [
        '-', '-', '-', '.', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-',
        'a', 'b', 'v', 'g', 'd', 'e', 'e', 'z', 'i', 'y', 'k',
        'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'x',
        'j', 'i', 'e', '-', 'zh', 'ts', 'ch', 'sh', 'shch',
        '', 'yu', 'ya'
    ], $st);
    // $st = preg_replace("/[^a-z0-9_.]/", "", $st);


    $prev_st = '';
    do {
        $prev_st = $st;
        $st = preg_replace("/_[a-z0-9]_/", "-", $st);
    } while ($st != $prev_st);

    $st = preg_replace("/_{2,}/", "-", $st);
    return $st;
}

function addNewDoor($IBLOCK_ID,$modelParams = [],$productParams = [])
{
    //$params['modelID']
    
    $properties = CIBlockProperty::GetList(array("sort" => "asc", "name" => "asc"), array("ACTIVE" => "Y", "IBLOCK_ID" => $IBLOCK_ID));
    while ($prop_fields = $properties->GetNext()) {
        $door_params[] = strtoupper($prop_fields["CODE"]);
        $door_params_type[strtoupper($prop_fields["CODE"])] = ['TYPE' => $prop_fields["PROPERTY_TYPE"], 'MULTIPLE' => $prop_fields["MULTIPLE"]];
    }
    if (!$productParams['image']){
        $image_params = array(
            'model' => '[' . strtoupper($modelParams['model_name']) . ']',
            'series' => '[' . strtoupper($modelParams['seria_torex']) . ']',
            'outside_face' => '[' . trim($productParams['color_outside']) . ']',
            'outside_image' => '[' . trim($productParams['design_outside']) . ']',
            'inside_face' => '[' . trim($productParams['inside_face']) . ']',
            'inside_image' => '[' . trim($productParams['inside_image']) . ']',
            'fittings' => '[' . trim($productParams['fittings']) . ']',
            'mounting' => '[]',
            'jamb' => '[]',
            'jamb_color' => '[' . trim($productParams['jamb_color']) . ']',
            'additional_options' => '[]',

        );


       // $productParams['image'] = 'https://reserve.torex.ru/helper/generateImage?params=' . str_replace(' ', '%20', str_replace(']"', '%22]', str_replace('"[', '[%22', json_encode($image_params)))).'&scale=1';
        $image  = downloadFile('https://reserve.torex.ru/helper/generateImage?params=' . str_replace(' ', '%20', str_replace(']"', '%22]', str_replace('"[', '[%22', json_encode($image_params)))).'&scale=1');
        
        $productParams['image']  = $image['location'];
    }
   
        
        $main_file_array = CFile::MakeFileArray($productParams['image']);
   
    
        $text_preview = $modelParams['PREVIEW_TEXT'];
        $text_detail = $modelParams['DETAIL_TEXT'];
        $model_id = $modelParams['ID'];
        $seria_id = $modelParams['seria_id'];
        $section_id = $modelParams['section_id'];
        $purpose_id = $modelParams['purpose_id'];
        
        $new_props['PURPOSE'] = $purpose_id;
        if ($productParams['product_id'])
            $new_props["DONORCODE"]=$productParams['product_id'];
       
        
        $el = new CIBlockElement;

        $arLoadProductArray = array(
            "MODIFIED_BY" => 1,
            "IBLOCK_SECTION_ID" => $seria_id,
            "IBLOCK_SECTION" => [$section_id, $seria_id],
            "IBLOCK_ID" => $IBLOCK_ID,
            "CODE" => translit($modelParams['model_name']) . '-' . generateRandomString(5),
            "NAME" => $modelParams['model_name'],
            "ACTIVE" => "Y",
            'CATALOG_AVAILABLE' => 'Y',
            "PREVIEW_TEXT" => $text_preview,
            "PREVIEW_TEXT_TYPE" => "html",
            "DETAIL_TEXT" => $text_detail,
            "DETAIL_TEXT_TYPE" => "html"
        );

        if ($ID = $el->Add($arLoadProductArray))
            echo 'Success insert b_iblock_element ' . $product_title;
        else
            echo 'Error insert b_iblock_element '.$el->LAST_ERROR ;


        foreach ($modelParams['modelProps'] as $modelProp) {
            
           

            if ($modelProp["VALUE"] && $modelProp["VALUE"] != '' && in_array(strtoupper($modelProp["CODE"]), $door_params) && $modelProp["CODE"] != 'purpose') {
                if ($door_params_type[strtoupper($modelProp["CODE"])]["TYPE"] == 'L') {
                    $property_enums = CIBlockPropertyEnum::GetList([], array("IBLOCK_ID" => $IBLOCK_ID, "CODE" => strtoupper($modelProp["CODE"])));
                    while ($enum_fields = $property_enums->GetNext()) {
                        if ($door_params_type[strtoupper($modelProp["CODE"])]["MULTIPLE"] == "Y") {
                            foreach ($modelProp["VALUE"] as $vals) {
                                if ($enum_fields["VALUE"] == $vals) {
                                    $new_props[strtoupper($modelProp["CODE"])] = $enum_fields["ID"];
                                }
                            }

                        } else {
                            if ($enum_fields["VALUE"] == $modelProp["VALUE"]) {
                                $new_props[strtoupper($modelProp["CODE"])] = $enum_fields["ID"];
                            }
                        }

                    }
                } else
                    $new_props[strtoupper($modelProp["CODE"])] = $modelProp["VALUE"];
            }
        }
        $new_props['MODEL'] = $modelParams['model_id'];
        
        $new_props['COLOROUTSIDE'] = $productParams['color_outside_id'];
        $new_props["DESIGNOUTSIDE"] = $productParams['design_outside_id'];
        $new_props['COLORINSIDE'] = $productParams['color_inside_id'];
        $new_props["DESIGNINSIDE"] = $productParams['design_inside_id'];
        $new_props['FITTING'] = $productParams['fitting_id'];
        $new_props['TRIMCOLOR'] = $productParams['jamb_color'];
        
        

        $new_props['MAIN_PHOTO_R'] = $main_file_array;
        CIBlockElement::SetPropertyValuesEx($ID, false, $new_props);
        
        $arFields_prod = array(
            "ID" => $ID,
            "QUANTITY" => $params['quantity']
        );
        
        CCatalogProduct::Add($arFields_prod);
        
        addDoorOffer($ID, $modelParams['model_name']);
        return $ID;


    

}

function getHLprop($hlbl, $filter_prop, $prop_text)
{

    if ($prop_text) {
        $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array($filter_prop => mb_strtolower($prop_text))
        ));
        while ($arData = $rsData->Fetch()) {
            if ($arData["ID"]){
                echo "<pre>";
                print_r([$arData["UF_XML_ID"], $arData["ID"], $arData["UF_NAME"]]);
                echo "</pre>";
                return [$arData["UF_XML_ID"], $arData["ID"], $arData["UF_NAME"]];
            }
            
        }
        $xml_id = generateRandomString(7);
        if ($filter_prop != 'UF_NAME'){
        $result = $entity_data_class::add(
            array(
                'UF_NAME' => $prop_text,
                'UF_XML_ID' => $xml_id,
                $filter_prop => [mb_strtolower($prop_text)],
            )
        );
        }
        else{
          $result = $entity_data_class::add(
            array(
                'UF_NAME' => $prop_text,
                'UF_XML_ID' => $xml_id,
            )
        );  
        }
        if ($result->isSuccess()) {
            return [$xml_id, $result->getId(), $prop_text];
        }

    }

}

function addStoreAmount($prod_id, $store_id, $amount)
{
    $arFieldsStoreAmount = array(
        "PRODUCT_ID" => $prod_id,
        "STORE_ID" => $store_id,
        "AMOUNT" => $amount
    );
    if (!CCatalogStoreProduct::Add($arFieldsStoreAmount)) {
        $rsStore = CCatalogStoreProduct::GetList([], ["PRODUCT_ID" => $prod_id, "STORE_ID" => $store_id], false, false, []);
        $arStore = $rsStore->Fetch();
        $res = CCatalogStoreProduct::Update(
            $arStore['ID'],
            [
                "PRODUCT_ID" => $prod_id,
                "STORE_ID" => $store_id,
                "AMOUNT" => $amount,
            ]
        );
    }
}

function getSectionId($sectionId)
{
    $obSection = CIBlockSection::GetList([], ["ID" => $sectionId]);
    while ($arSection = $obSection->Fetch()) {
        $sectionName = $arSection["NAME"];
    }
    $obSection_n = CIBlockSection::GetList([], ["IBLOCK_ID" => 2, "NAME" => $sectionName]);
    while ($arSection_n = $obSection_n->Fetch()) {
        return $arSection_n["ID"];
    }
}
function getModel($modelBlockID,$modelFilter,$product_specifications_arr,$product_specifications_lock_arr,$files){
    if (!is_array($modelFilter))
        return ['error'=>'Некорректный массив поиска'];
    $result=[];
    $model_obj = CIBlockElement::GetList(array(), $modelFilter, false, [], []);
    if ($model_obj->SelectedRowsCount() > 0) {
        while ($model = $model_obj->GetNextElement()) {
            $modelFields = $model->GetFields();
            $result['text_preview'] = $modelFields['PREVIEW_TEXT'];
            $result['text_detail'] = $modelFields['DETAIL_TEXT'];
            $result['model_name'] = $modelFields["NAME"];
            $result['model_id'] = $modelFields['ID'];
            $modelProps = $model->GetProperties();
            $result['seria_id'] = $modelProps['parent']["VALUE"];
            $result['seria_name'] = $modelProps['seria']["VALUE"];
            $result['seria_torex'] = $modelProps['seria_torex']["VALUE"];
            $result['modelProps'] =$modelProps;
            list($result['purpose_id'], $result['section_id']) = explode("_", $modelProps['purpose']["VALUE_XML_ID"]);


                if (!$modelProps["filled"]["VALUE"]) {
                    
                    $new_props = [];
                    foreach ($modelProps as $modelProp) {
                        if ($product_specifications_arr[$modelProp["NAME"]]) {
                            if ($modelProp["PROPERTY_TYPE"] == 'L') {
                                $property_enums = CIBlockPropertyEnum::GetList([], array("IBLOCK_ID" => $modelBlockID, "CODE" => $modelProp["CODE"]));
                                while ($enum_fields = $property_enums->GetNext()) {
                                    if ($enum_fields["VALUE"] == $product_specifications_arr[$modelProp["NAME"]]) {
                                        $new_props[$modelProp["CODE"]] = $enum_fields["ID"];
                                }
                            }
                            } else {
                                $new_props[$modelProp["CODE"]] = $product_specifications_arr[$modelProp["NAME"]];
                            }
                        } elseif ($product_specifications_lock_arr[$modelProp["NAME"]]) {
                            if ($modelProp["PROPERTY_TYPE"] == 'L') {
                                $property_enums = CIBlockPropertyEnum::GetList([], array("IBLOCK_ID" => $modelBlockID, "CODE" => $modelProp["CODE"]));
                                while ($enum_fields = $property_enums->GetNext()) {
                                    if ($enum_fields["VALUE"] == $product_specifications_lock_arr[$modelProp["NAME"]]) {
                                        $new_props[$modelProp["CODE"]] = $enum_fields["ID"];
                                }
                            }
                            } else {
                            $new_props[$modelProp["CODE"]] = $product_specifications_lock_arr[$modelProp["NAME"]];
                            }
                        }
                    }
                    $new_props["vendor"] = "torex";
                    $new_props["vendor_parent"] = "Казанский завод Стальных Дверей";
                    
                    foreach ($files as $element) {
                            $image = downloadFile(pq($element)->attr('href'));
                            $main_file_array = CFile::MakeFileArray($image['location']);
                            $main_file_array["description"] = trim(pq($element)->text());
                            $new_props["serts"][] = $main_file_array;
                        }
                    
                    $new_props["filled"] = 83;
                    CIBlockElement::SetPropertyValuesEx($modelFields["ID"], false, $new_props);
                     $subject = "Редактирование модели"; 
                     $message = ' <p>Отредактировали модель</p></br>'.$product_specifications_arr['Модель']." (".$modelFields["ID"].")";
                     mail($to, $subject, $message, $headers); 
                }
            }
            return $result;
        } else {
            $subject = "Создали новую модель"; 
            $message = ' <p>Создали модель</p></br>'.$product_specifications_arr['Модель'];
            echo mail($to, $subject, $message, $headers); 
            new_model($modelBlockID,$product_specifications_arr['Модель']);
            getModel($modelBlockID,$modelFilter,$product_specifications_arr,$product_specifications_lock_arr,$files);
        }
        
    
    
}
function new_model($modelBlockID,$model_name)
{
    $model_seo = str_replace(" ", "-", $model_name);
    $el = new CIBlockElement;
    $arLoadModelArray = array(
        "MODIFIED_BY" => 1,
        "IBLOCK_ID" => $modelBlockID,
        "CODE" => $model_seo,
        "NAME" => $model_name,
        "ACTIVE" => "Y",
        "PREVIEW_TEXT_TYPE" => "html",
        "DETAIL_TEXT_TYPE" => "html",
        "XML_ID" => $model_seo,
    );

    if ($model_ID = $el->Add($arLoadModelArray))
        echo 'Success insert model ' . $model_name;

    $model_props["model_synonyms"] = strtoupper($model_name);
    CIBlockElement::SetPropertyValuesEx($model_ID, false, $model_props);
}

function addDoorOffer($parent_id, $model_name)
{
    $link = 'https://reserve.torex.ru/helper/getIndividualPositions';


    $data['model'] = strtoupper($model_name);


    $curl = curl_init();


    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));


    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, $link);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
    ));
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);


    $response = curl_exec($curl);
    curl_close($curl);
    $results = json_decode($response, true);

    if (count($results['model']) == 1 && $results['series'][0] != 'Технические') {
        foreach ($results['width'] as $width) {
            foreach ($results['height'] as $height) {
                $offer_params = [];
                $offer_params['parent_id'] = $parent_id;
                list($size_code, $size__id, $size__name) = getHLprop(12, "UF_NAME", $width.'x'.$height);
                $offer_params['size'] = $size_code;
                
                $offer_params['name'] = $model_name;
                $offer_params['status'] = 7;
                $offer_params["city"] = ['moscow','saint-peterburg','kaluga','kaliningrad','archangelsk','tver'];

                $ar_ofFilter = array("IBLOCK_ID" => 3, "PROPERTY_19_VALUE" => $parent_id, "PROPERTY_255_VALUE" => $size_code, "PROPERTY_21" => 143);
                $res_xml_offer = CIBlockElement::GetList(array(), $ar_ofFilter, false, [], []);
                if ($res_xml_offer->SelectedRowsCount() == 0) {
                    $offer_params['CML2_SIDE'] = 143;
                    $n_offer_id = addNewOffer($offer_params);
                    CCatalogProduct::Add(array('ID' => $n_offer_id, 'QUANTITY' => 1));
                }
                $ar_ofFilter = array("IBLOCK_ID" => 3, "PROPERTY_19_VALUE" => $parent_id, "PROPERTY_255_VALUE" => $size_code, "PROPERTY_21" => 144);
                $res_xml_offer = CIBlockElement::GetList(array(), $ar_ofFilter, false, [], []);

                if ($res_xml_offer->SelectedRowsCount() == 0) {

                    $offer_params['CML2_SIDE'] = 144;
                    $n_offer_id = addNewOffer($offer_params);
                    CCatalogProduct::Add(array('ID' => $n_offer_id, 'QUANTITY' => 1));
                }
            }
        }
    }
    else{
        
                $offer_params = [];
                $offer_params['parent_id'] = $parent_id;
                $offer_params['name'] = $model_name;
                $offer_params['status'] = 7;

                $ar_ofFilter = array("IBLOCK_ID" => 3, "PROPERTY_19_VALUE" => $parent_id,"PROPERTY_24_VALUE" => false,"PROPERTY_68_VALUE" => false, "PROPERTY_21" => 143);
                $res_xml_offer = CIBlockElement::GetList(array(), $ar_ofFilter, false, [], []);
                if ($res_xml_offer->SelectedRowsCount() == 0) {
                    $offer_params['CML2_SIDE'] = 143;
                    $n_offer_id = addNewOffer($offer_params);
                    CCatalogProduct::Add(array('ID' => $n_offer_id, 'QUANTITY' => 1));
                }
                $ar_ofFilter = array("IBLOCK_ID" => 3, "PROPERTY_19_VALUE" => $parent_id, "PROPERTY_24_VALUE" => false,"PROPERTY_68_VALUE" => false, "PROPERTY_21" => 144);
                $res_xml_offer = CIBlockElement::GetList(array(), $ar_ofFilter, false, [], []);

                if ($res_xml_offer->SelectedRowsCount() == 0) {

                    $offer_params['CML2_SIDE'] = 144;
                    $n_offer_id = addNewOffer($offer_params);
                    CCatalogProduct::Add(array('ID' => $n_offer_id, 'QUANTITY' => 1));
                }
    }
   
    
}

function delDoorOffer($parent_id)
{

    $ar_ofFilter = array("IBLOCK_ID" => 3, "PROPERTY_19_VALUE" => $parent_id, "PROPERTY_22" => 7);
    $res_xml_offer = CIBlockElement::GetList(array(), $ar_ofFilter, false, [], []);
    if ($res_xml_offer->SelectedRowsCount() > 0) {
        while ($offerobj = $res_xml_offer->GetNextElement()) {
            $ofFields = $offerobj->GetFields();
            if (!CIBlockElement::Delete($ofFields['ID'])) {
                echo 'Ошибка удаления!';
                echo "<br/>";
            }
        }
    }
    
    $ar_ofFilter = array("IBLOCK_ID" => 3, "PROPERTY_19_VALUE" => $parent_id);
    $res_xml_offer = CIBlockElement::GetList(array(), $ar_ofFilter, false, [], []);
    
    if ($res_xml_offer->SelectedRowsCount() == 0) {
        $arFields = array('QUANTITY' => -1);
        CCatalogProduct::Update($parent_id, $arFields);
    }

}

function addNewOffer($params = [])
{
    $intSKUIBlock = 3;
    $arProp = [];
    if ($params['status']) {
        $arProp['STATUS'] = $params['status'];
    }

    $arProp['CML2_LINK'] = $params['parent_id'];
    $arProp['CML2_SIZE'] = $params['size'];
    $arProp['CML2_SIDE'] = $params['CML2_SIDE'];
    $arProp["CITY"] = $params['city'];

    $el = new CIBlockElement;
    $arNFields = array(
        'NAME' => $params['name'],
        'IBLOCK_ID' => $intSKUIBlock,
        "CODE" => generateRandomString(5),
        'ACTIVE' => 'Y',
        "XML_ID" => $params['OFFER_XML_ID'],
        'CATALOG_AVAILABLE' => 'Y',
        'PROPERTY_VALUES' => $arProp
    );
    $offer_id = $el->Add($arNFields);
    addStoreAmount($offer_id, 1, 1);
    return $offer_id;
}

function setOfferPrice($searchfilter, $price_id, $price)
{
    
   
    $res_xml_offer = CIBlockElement::GetList(array(), $searchfilter, false, [], []);
    echo $res_xml_offer->SelectedRowsCount();
    echo "<br/>";
    if ($res_xml_offer->SelectedRowsCount() > 0) {
        while ($offerobj = $res_xml_offer->GetNextElement()) {
            $ofFields = $offerobj->GetFields();
            setprice($ofFields['ID'], $price_id, $price);
        }
    }

}

function setprice($prod_id, $price_id, $price)
{
   
    $arFields_price = array(
        "PRODUCT_ID" => $prod_id,
        "CATALOG_GROUP_ID" => $price_id,
        "PRICE" => $price,
        "CURRENCY" => "RUB"
    );

    $res_price = CPrice::GetList(
        array(),
        array(
            "PRODUCT_ID" => $prod_id,
            "CATALOG_GROUP_ID" => $price_id
        )
    );

    if ($arr = $res_price->Fetch()) {
        CPrice::Update($arr["ID"], $arFields_price);
    } else {

        CPrice::Add($arFields_price);
        CCatalogProduct::Add(["ID" => $prod_id]);

    }
}

# Functions
function get_http_response_code($url)
{
    $headers = get_headers($url);
    return substr($headers[0], 9, 3);
}

function generateRandomString($length)
{
    $bytes = random_bytes($length);
    return bin2hex($bytes);
}
function searchProduct($searchparams){
  
     echo "<pre>";
             print_r($searchparams);
             echo "</pre>";
    $prod_res = CIBlockElement::GetList(array(), $searchparams, false, [], []);
    $el = new CIBlockElement;
     echo "<pre>";
             print_r($prod_res->SelectedRowsCount());
             echo "</pre>";
    if ($prod_res->SelectedRowsCount() == 0)
        return false;
    while ($prod_obj = $prod_res->GetNextElement()) {
        $prodFields = $prod_obj->GetFields();
        $prodProps = $prod_obj->GetProperties();
    }
    return ['prodFields'=>$prodFields,'prodProps'=>$prodProps];
}
function updateProduct($params){
    
}
function addProduct($params){
    
}
function downloadFile($url)
{
    $info = pathinfo($url);
    $ch = curl_init($url);
    $extension = $info['extension'];
    $file_name = generateRandomString(10) . '.' . $extension;
    $file_name_original = basename($url);

    $dir = substr($file_name, 0, 3);
    $path = 'iblock/' . $dir . '/';

    if (!file_exists('../../../../upload/' . $path)) {
        mkdir('../../../../upload/' . $path, 0777, true);
    }

    $location = '../../../../upload/' . $path . $file_name;

    // Open file  
    $fp = fopen($location, 'wb');

    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
    curl_close($ch);

    fclose($fp);

    // Create response
    $info = pathinfo($location);

    if (exif_imagetype($location)) {
        $params = getimagesize($location);

        $height = $params[1];
        $width = $params[0];
    } else {
        $height = 0;
        $width = 0;
    }

    $size = filesize($location);
    $type = mime_content_type($location);

    return array(
        'location' => $location,
        'subdir' => $path,
        'name' => $file_name,
        'original' => addslashes($file_name_original),
        'height' => $height,
        'width' => $width,
        'size' => $size,
        'type' => $type
    );
}
 function drawImage($img1, $img2, $file = false){
     
        if (file_exists($file) || !$img1 || !$img2) return;
        if (!copy($img1, 'img1.png'))
        {
            echo "не удалось скопировать $img1...\n";
        }
        if (!copy($img2, 'img2.png'))
        {
            echo "не удалось скопировать $img2...\n";
        }
        $img1 = 'img1.png';
        $img2 = 'img2.png';
        if (!preg_match('~\.(jpe?g|png|gif)$~i', $img1) || !preg_match('~\.(jpe?g|png|gif)$~i', $img2)) return false;
        if (!($info[] = getimagesize($img1)) || !($info[] = getimagesize($img2))) return false;

        $info[0]['type'] = substr($info[0]['mime'], 6);
        $info[1]['type'] = substr($info[1]['mime'], 6);
        $info[0]['width'] = (int)$info[0][0];
        $info[1]['width'] = (int)$info[1][0];
        $info[0]['height'] = (int)$info[0][1];
        $info[1]['height'] = $info[0]['height'];

        $create1 = 'imagecreatefrom' . $info[0]['type'];
        $create2 = 'imagecreatefrom' . $info[1]['type'];
        if (!function_exists($create1) || !function_exists($create1)) return false;

        $width = ($info[0]['width'] > $info[1]['width']) ? $info[0]['width'] : $info[1]['width'];
        $height = $info[0]['height']; //зазор в 1 пиксель между картинками (можно убрать, конечно)
        if (empty($width) || empty($height)) return false;
        
       
        $image1 = $create1($img1); //create images
        $image2 = $create2($img2); //create images
        $dst = imagecreatetruecolor($width * 2, $height);
        
     
        
     
            imagecolortransparent($dst, imagecolorallocatealpha($dst, 0, 0, 0, 127));
            imagecolorallocate($dst, 255, 255, 255);
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $type = 'png';
        
        
        
       
       
        $white = imagecolorallocate($image1, 255, 255, 255);
        imagefill($image1, 0, 0, $white);
        $white = imagecolorallocate($image2, 255, 255, 255);
        imagefill($image2, 0, 0, $white);
        
        imagecopyresampled($dst, $image2, $width, 0, 0, 0, $width, $info[0]['height'], $info[0]['width'], $info[0]['height']);
        imagecopyresampled($dst, $image1, 0, 0, 0, 0, $width, $info[1]['height'], $info[1]['width'], $info[1]['height']);

        $save = 'image' . $type;
        // header('Content-type: image/' . $type);
        
        

        $save($dst, $file);
       /* if (!copy('result.jpg', $_SERVER['DOCUMENT_ROOT'] . '/images/doors/' . $file))
        {
            echo "не удалось скопировать $file...\n";
        }*/
         
        return;

    }


?>