<?php
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

/**
 * Bitrix vars
 *
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $this
 * @global CMain $APPLICATION
 * @global CUser $USER
 */

include_once($_SERVER['DOCUMENT_ROOT'].'/libs/php-bitrix-24/crest.php');
$arResult["PARAMS_HASH"] = md5(serialize($arParams).$this->GetTemplateName());
$arParams["AJAX_MODE"]=  'Y';
$arResult['AJAX_MODE'] =  'Y';

$arParams["USE_CAPTCHA"] = (($arParams["USE_CAPTCHA"] != "N" && !$USER->IsAuthorized()) ? "Y" : "N");
$arParams["EVENT_NAME"] = trim($arParams["EVENT_NAME"]);
if($arParams["EVENT_NAME"] == '')
	$arParams["EVENT_NAME"] = "FEEDBACK_FORM";
$arParams["EMAIL_TO"] = trim($arParams["EMAIL_TO"]);
if($arParams["EMAIL_TO"] == '')
	$arParams["EMAIL_TO"] = COption::GetOptionString("main", "email_from");
$arParams["OK_TEXT"] = trim($arParams["OK_TEXT"]);
if($arParams["OK_TEXT"] == '')
	$arParams["OK_TEXT"] = GetMessage("MF_OK_MESSAGE");

if(!$_REQUEST["success"] && $_REQUEST["submit"] <> '' && (!isset($_REQUEST["PARAMS_HASH"]) || $arResult["PARAMS_HASH"] === $_REQUEST["PARAMS_HASH"]))
{
	$arResult["ERROR_MESSAGE"] = array();
	if(check_bitrix_sessid())
	{
	    
		if(empty($arParams["REQUIRED_FIELDS"]) || !in_array("NONE", $arParams["REQUIRED_FIELDS"])) {
			if((empty($arParams["REQUIRED_FIELDS"]) || in_array("user_ph", $arParams["REQUIRED_FIELDS"])) && strlen($_REQUEST["user_ph"]) < 18)
				$arResult["ERROR_MESSAGE"][] = GetMessage("MF_REQ_PHONE");
			if((empty($arParams["REQUIRED_FIELDS"]) || in_array("user_ml", $arParams["REQUIRED_FIELDS"])) && strlen($_REQUEST["user_ml"]) <= 1)
				$arResult["ERROR_MESSAGE"][] = GetMessage("MF_REQ_EMAIL");
			if((empty($arParams["REQUIRED_FIELDS"]) || in_array("MESSAGE", $arParams["REQUIRED_FIELDS"])) && strlen($_REQUEST["MESSAGE"]) <= 3)
				$arResult["ERROR_MESSAGE"][] = GetMessage("MF_REQ_MESSAGE");
		}
		
		if(isset($_REQUEST["user_ph"])){
			$clear_user_ph =preg_replace("/[^0-9]/", "", $_REQUEST["user_ph"]);
			if(strlen($clear_user_ph) < 11) $arResult["ERROR_MESSAGE"][] = GetMessage("MF_REQ_PHONE");
		}

		if(isset($_FILES["summary"])){
			$target_dir = "/home/host1353702/torex.shop/htdocs/www/upload/summaries/";
			$target_file = $target_dir.basename($_FILES["summary"]["name"]);
			$summaryFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


			if ($_FILES["summary"]["size"] > 1500000) {
				$arResult["ERROR_MESSAGE"][] = 'Неверный тип файла или превышен его размер';
			}
			else if($summaryFileType != "doc" && $summaryFileType != "docx" && $summaryFileType != "pdf" && $summaryFileType != "txt" ) {
				$arResult["ERROR_MESSAGE"][] = 'Неверный тип файла или превышен его размер';
			}
			else {
				if (move_uploaded_file($_FILES["summary"]["tmp_name"], $target_file)) {
					$summary = array(
						"fileData" => array($_FILES["summary"]["name"], base64_encode(file_get_contents($target_file)))
					);
				} else {
					$arResult["ERROR_MESSAGE"][] = 'Неверный тип файла или превышен его размер';
				}

			}
		}

		if(strlen($_REQUEST["user_ml"]) > 1 && !check_email($_REQUEST["user_ml"]))
			$arResult["ERROR_MESSAGE"][] = GetMessage("MF_EMAIL_NOT_VALID");

		/*if(strlen($_REQUEST["captcha"]) == 0 )
			$arResult["ERROR_MESSAGE"][] = GetMessage("MF_REQ_MESSAGE");
        */
		if($arParams["USE_CAPTCHA"] == "Y")
		{
			$captcha_code = $_REQUEST["captcha_sid"];
			$captcha_word = $_REQUEST["captcha_word"];
			$cpt = new CCaptcha();
			$captchaPass = COption::GetOptionString("main", "captcha_password", "");
			if (strlen($captcha_word) > 0 && strlen($captcha_code) > 0)
			{
				if (!$cpt->CheckCodeCrypt($captcha_word, $captcha_code, $captchaPass))
					$arResult["ERROR_MESSAGE"][] = GetMessage("MF_CAPTCHA_WRONG");
			}
			else
				$arResult["ERROR_MESSAGE"][] = GetMessage("MF_CAPTHCA_EMPTY");

		}
		if( $_REQUEST["identifier"] == 'ny-lottery'){
		     $arFilter = array("IBLOCK_ID" => 56, "PROPERTY_1254_VALUE" =>trim($_REQUEST["user_ph"]));
        $res = CIBlockElement::GetList(array(), $arFilter, false, [], []);
        if ($res->SelectedRowsCount() > 0){
            $arResult["ERROR_MESSAGE"][] = "Указанный номер уже участвует в розыгрыше";
        }
		}
		if(empty($arResult["ERROR_MESSAGE"]))
		{
		    
			$arFields = Array(
				"AUTHOR" => $_REQUEST["user_nm"],
				"AUTHOR_PHONE" => $_REQUEST["user_ph"],
				"AUTHOR_THEME" => $_REQUEST["theme"],
				"AUTHOR_ADDRESS" => $_REQUEST["address"],
				"AUTHOR_PERIOD" => $_REQUEST["period"],
				"AUTHOR_MODEL" => $_REQUEST["model"],
				"AUTHOR_SCORE_MONTAGE" => $_REQUEST["score_montage"],
				"AUTHOR_SCORE_DOOR" => $_REQUEST["score_door"],
				"AUTHOR_SCORE_SERVICE" => $_REQUEST["score_service"],
				"AUTHOR_EMAIL" => $_REQUEST["user_ml"],
				"EMAIL_TO" => $arParams["EMAIL_TO"],
				"TEXT" => $_REQUEST["MESSAGE"],
			);
			if(!empty($arParams["EVENT_MESSAGE_ID"]))
			{
				foreach($arParams["EVENT_MESSAGE_ID"] as $v)
					if(IntVal($v) > 0)
						CEvent::Send($arParams["EVENT_NAME"], SITE_ID, $arFields, "N", IntVal($v));
			}
			else
				CEvent::Send($arParams["EVENT_NAME"], SITE_ID, $arFields);
            $roistatVisitId = array_key_exists('roistat_visit', $_COOKIE) ? $_COOKIE['roistat_visit'] : 'nocookie';
			# Send bitrix24
				if( isset($_REQUEST["identifier"]) && $_REQUEST["identifier"] != 'subscribe'){
				    		if( $_REQUEST["manager"] == 'agents' ) $maneger_id = 1874; // Денис Дихтяренко
					        else $maneger_id = 300; // Интернет-магазин

					        if(!isset($_REQUEST["comments"])) { $_REQUEST["comments"] = ''; }
					        if(!isset($_REQUEST["branch"])) { $_REQUEST["branch"] = 0; }
					        if(!isset($_REQUEST["product_name"])) { $_REQUEST["product_name"] = ''; }
					        if(!isset($_REQUEST["product_link"])) { $_REQUEST["product_link"] = ''; }

					        if(isset($_REQUEST["order"])) { $_REQUEST["user_nm"] = $_REQUEST["user_nm"].' '.$_REQUEST["order"]; }
					       
					       	# Summary
					        if(!isset($_REQUEST["position"])) { $_REQUEST["position"] = ''; }
					        if(isset($_REQUEST["user_snm"]) && isset($_REQUEST["user_nm"])) { $_REQUEST["user_nm"] = $_REQUEST["user_snm"].' '.$_REQUEST["user_nm"]; }
					        if(isset($_REQUEST["user_mnm"])) { $_REQUEST["user_nm"] = $_REQUEST["user_nm"].' '.$_REQUEST["user_mnm"]; }

					        if(!isset($summary)) { $summary = ''; }
					        if(!isset($_REQUEST["user_lsm"])) { $_REQUEST["user_lsm"] = ''; }
					        
					        if(!isset($_REQUEST["UTM_SOURCE"])) { $_REQUEST["UTM_SOURCE"] = ''; }
					        if(!isset($_REQUEST["UTM_MEDIUM"])) { $_REQUEST["UTM_MEDIUM"] = ''; }
					        if(!isset($_REQUEST["UTM_CAMPAIGN"])) { $_REQUEST["UTM_CAMPAIGN"] = ''; }
					        if(!isset($_REQUEST["UTM_CONTENT"])) { $_REQUEST["UTM_CONTENT"] = ''; }
					        if(!isset($_REQUEST["UTM_TERM"])) { $_REQUEST["UTM_TERM"] = ''; }

					        if(!isset($_REQUEST["calltouch_id"])) { $callTouchID = 0; }
					        else { $callTouchID = $_REQUEST["calltouch_id"]; }



					       	if( $_REQUEST["identifier"] == 'summary' ) {
					       		$title = "Отклик на вакансию ".$_REQUEST["user_nm"];
					       		$maneger_id = 404;
					       	}
					       	else if( $_REQUEST["identifier"] == 'subscribe' ) { $title = "Новый подписчик ".$_REQUEST["user_nm"]; }
                            else if( isset($_REQUEST["form_name"])) {
                                $title = $_REQUEST["form_name"] ." ". $_REQUEST["user_nm"];
                            }
                            else if( isset($_REQUEST["form_title"])) {
                                $title = $_REQUEST["form_title"] ." ". $_REQUEST["user_nm"];
                            }
                            else { $title = "Заполнение формы на сайте ".$_REQUEST["user_nm"]; }
                            if ($_REQUEST["identifier"] == 'promo-showroom' && $_REQUEST["client_city_name"]){
                                $title .="( ".$_REQUEST["client_city_name"].")";
                            }
                            if(isset($_REQUEST["product_name"]) && $_REQUEST["product_name"] != '') {
                                $_REQUEST["comments"] .= $_REQUEST["product_name"];
                            
                                if (isset($_REQUEST["product_finishing"]) && $_REQUEST["product_finishing"] != ''){
                                    $_REQUEST["comments"] .= ', ' . $_REQUEST["product_finishing"];
                                }
                                else{
                                    $_REQUEST["comments"] .= ', Сторона открывания не выбрана';
                                }
                                if (isset($_REQUEST["product_size"]) && $_REQUEST["product_size"] != ''){
                                    $_REQUEST["comments"] .= ', ' . $_REQUEST["product_size"];
                                }
                                else{
                                    $_REQUEST["comments"] .= ', Размер не выбран';
                                }

                                if (isset($_REQUEST["product_hinge"]) && $_REQUEST["product_hinge"] != ''){
                                    $_REQUEST["comments"] .= ', ' . $_REQUEST["product_hinge"];
                                }
                                if (isset($_REQUEST["user_price"]) && $_REQUEST["user_price"] != ''){
                                    $_REQUEST["user_price"] .= ', Цена товара в другом магазине: ' . $_REQUEST["user_price"];
                                }
                                if (isset($_REQUEST["user_link"]) && $_REQUEST["user_link"] != ''){
                                    $_REQUEST["user_link"] .= ', Ссылка на найденный товар: ' . $_REQUEST["user_link"];
                                }
                                if (isset($_REQUEST["user_promo"]) && $_REQUEST["user_promo"] != '') {
                                    $_REQUEST["comments"] .=', Введен промокод: '.$_REQUEST["user_promo"];
                                }


                            if (isset($_REQUEST["product_price"]) && $_REQUEST["product_price"] != ''){
                                $price = $_REQUEST["product_price"];
                            }
                            }
                            if (isset($_REQUEST["client_city"])){
                                if ($_REQUEST["client_city"] == 745)
                                    $city = 2028;
                                else if ($_REQUEST["client_city"] == 747)
                                    $city = 4114;
                                else if ($_REQUEST["client_city"] == 746)
                                    $city = 1972;
                                else if ($_REQUEST["client_city"] == 750)
                                    $city = 1974;
                                else if ($_REQUEST["client_city"] == 749)
                                    $city = 1976;

                            }
                            if( $_REQUEST["identifier"] == 'ny-lottery'){
                                $title .= ' ('.$_REQUEST["comments"].')';
                                $bytes = random_bytes(8);
                                $el = new CIBlockElement;
                                $PROP = array();
                                $PROP[1255] = $_SERVER['REMOTE_ADDR'];
                                $PROP[1254] = $_REQUEST["user_ph"];
                                $arLoadProductArray = Array(
                                    "MODIFIED_BY"    => 1,
                                    "IBLOCK_SECTION_ID" => 0,
                                    "IBLOCK_ID"      => 56,
                                    "PREVIEW_TEXT" =>$_REQUEST["comments"],
                                    "CODE" => bin2hex($bytes),
                                    "PROPERTY_VALUES"=> $PROP,
                                    "NAME"           => $_REQUEST["user_nm"],
                                    "ACTIVE"         => "Y",
                                    );
                                    $el->Add($arLoadProductArray);
                            }
                               
                $b24_cont_result = CRest::call(
                    'crm.contact.add',
                    ['FIELDS' => [
                        'ASSIGNED_BY_ID' => $maneger_id,
                        'NAME' => $_REQUEST["user_nm"],
                        'LAST_NAME' => '',
                        'SOURCE_ID' => 37, // torex.shop
                        'EMAIL' => ['0' => ['VALUE' => $_REQUEST["user_ml"], 'VALUE_TYPE' => 'WORK', ], ],
                        'PHONE' => ['0' => ['VALUE' => $_REQUEST["user_ph"], 'VALUE_TYPE' => 'WORK', ], ],
                        'UTM_SOURCE' => $_REQUEST["UTM_SOURCE"],
                        'UTM_MEDIUM' => $_REQUEST["UTM_MEDIUM"],
                        'UTM_CAMPAIGN' => $_REQUEST["UTM_CAMPAIGN"],
                        'UTM_CONTENT' => $_REQUEST["UTM_CONTENT"],
                        'UTM_TERM' => $_REQUEST["UTM_TERM"],
                        'UF_CRM_1588766183526' => [916], //Torex
                        'UF_CRM_1632213659' => [intval($_REQUEST["branch"])], //Филиал
                        'UF_CRM_1627894666' => $roistatVisitId, //ROIstat / Form Identifier
                        
                    ],
                    ]
                );

					        $b24_lead_result = CRest::call(
					            'crm.lead.add',
					            ['FIELDS' => [
					                    'TITLE' => $title,
					                    'ASSIGNED_BY_ID' => $maneger_id,
					                    'NAME' => $_REQUEST["user_nm"],
					                    'LAST_NAME' => '',
					                    'OPPORTUNITY'=> ($price ? $price : ''),
					                    'COMMENTS' => $_REQUEST["comments"],
					                    'SOURCE_ID' => 37, // torex.shop
					                    'EMAIL' => ['0' => ['VALUE' => $_REQUEST["user_ml"], 'VALUE_TYPE' => 'WORK', ], ],
					                    'PHONE' => ['0' => ['VALUE' => $_REQUEST["user_ph"], 'VALUE_TYPE' => 'WORK', ], ],
					                    'UTM_SOURCE' => $_REQUEST["UTM_SOURCE"],
					                    'UTM_MEDIUM' => $_REQUEST["UTM_MEDIUM"],
					                    'UTM_CAMPAIGN' => $_REQUEST["UTM_CAMPAIGN"],
					                    'UTM_CONTENT' => $_REQUEST["UTM_CONTENT"],
					                    'UTM_TERM' => $_REQUEST["UTM_TERM"],
                                        'UF_CRM_1660226279279' => $_REQUEST["form_title"], // Розница
					                    'UF_CRM_1566466970429' => 334, // Розница
					                    'UF_CRM_1566468003575' => 342, // Новый
					                    'UF_CRM_1586953059582' => 756, // Клиент
					                    'UF_CRM_1588766183526' => [916], //Torex
					                    'UF_CRM_1645172100' => $_REQUEST["product_name"], //Товар
					                    'UF_CRM_1645172120' => $_REQUEST["product_link"], //Ссылка на товар
					                    'UF_CRM_1632213659' => [intval($_REQUEST["branch"])], //Филиал
					                    'UF_CRM_1627894666' => $roistatVisitId, //ROIstat / Form Identifier
					                    'UF_CRM_1645777371' => $_REQUEST["position"], //Вакансия
					                    'UF_CRM_1645777424' => $_REQUEST["user_lsm"], //Ссылка на резюме
					                    'UF_CRM_1645777495' => $summary, //Файл резюме
                                        'UF_CRM_1632213659' => $city, //город

					                ],
					            ]
					        );
					      
					        $b24_result = CRest::call(
					            "crm.lead.contact.add", 
					             ['id'=>$b24_lead_result['result'],
					             'FIELDS' => [
					                 "CONTACT_ID"=> $b24_cont_result['result'],
					                 "SORT"=>10,
					                 "IS_PRIMARY"=> "Y"			
					                 ],
					                 ]
					                 );
        
					        // CallTouch
					        $array = array(
								'subject'    => $title,
								'sessionId'    => $callTouchID,
								'requestDate'    => date('d.m.Y H:m:s',time()),
								'fio'    => $_REQUEST["user_nm"],
								'phoneNumber'    => $_REQUEST["user_ph"],
								'email'    => $_REQUEST["user_ml"],
								'comment' => $_REQUEST["comments"],
								'requestUrl' => $_REQUEST["product_link"]
							);		
							 
							$ch = curl_init('https://api.calltouch.ru/calls-service/RestAPI/requests/50290/register/');
							curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/x-www-form-urlencoded'));
							curl_setopt($ch, CURLOPT_POST, 1);
							curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($array, '', '&'));
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
							curl_setopt($ch, CURLOPT_HEADER, false);
							$html = curl_exec($ch);
							curl_close($ch);
						// ---

				}
		    else if(isset($_REQUEST["identifier"]) && $_REQUEST["identifier"] == 'subscribe'){
                $maneger_id = 300;
                $b24_result = CRest::call(
                    'crm.contact.add',
                    ['FIELDS' => [
                        'ASSIGNED_BY_ID' => $maneger_id,
                        'NAME' => '',
                        'LAST_NAME' => '',
                        'SOURCE_ID' => 37, // torex.shop
                        'EMAIL' => ['0' => ['VALUE' => $_REQUEST["user_ml"], 'VALUE_TYPE' => 'WORK', ], ],

                        'UTM_SOURCE' => $_REQUEST["UTM_SOURCE"],
                        'UTM_MEDIUM' => $_REQUEST["UTM_MEDIUM"],
                        'UTM_CAMPAIGN' => $_REQUEST["UTM_CAMPAIGN"],
                        'UTM_CONTENT' => $_REQUEST["UTM_CONTENT"],
                        'UTM_TERM' => $_REQUEST["UTM_TERM"],
                        'UF_CRM_1588766183526' => [916], //Torex
                        'UF_CRM_1632213659' => [intval($_REQUEST["branch"])], //Филиал
                        'UF_CRM_1627894666' => $roistatVisitId, //ROIstat / Form Identifier
                        'UF_CRM_1654167400' => 42122
                    ],
                    ]
                );
                
                $queryUrl = 'https://api.mindbox.ru/v3/operations/async?endpointId=torex-website&operation=Website.SubscriptionInFooter&deviceUUID=' . $_COOKIE['mindboxDeviceUUID'];

                $header = array();
                $header[] = 'Content-Type: application/xml; charset=utf-8';
                $header[] = 'Accept: application/xml';
                $header[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.154 Safari/537.36';
                $header[] = 'X-Customer-IP: ' . $_SERVER['REMOTE_ADDR'];

                $queryData = '<operation> 
                <customer><email>'.$_REQUEST["user_ml"].'</email>
                    <subscriptions>  
                        <subscription>
                            <brand>Torex</brand>
                            <pointOfContact>Email</pointOfContact>
                        </subscription>
                    </subscriptions>
                </customer>
                </operation>';
        

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_POST => 1,
                    CURLOPT_HTTPHEADER => $header,
                    CURLOPT_URL => $queryUrl,
                    CURLOPT_POSTFIELDS => $queryData,
                ));
                $result = curl_exec($curl);

                curl_close($curl);
            }

			$_SESSION["MF_NAME"] = htmlspecialcharsbx($_REQUEST["user_nm"]);
			$_SESSION["MF_EMAIL"] = htmlspecialcharsbx($_REQUEST["user_ml"]);
			LocalRedirect($APPLICATION->GetCurPageParam("success=".$arResult["PARAMS_HASH"], Array("success")));
		}
		
		
		
		$arResult["MESSAGE"] = htmlspecialcharsbx($_REQUEST["MESSAGE"]);
		$arResult["user_nm"] = htmlspecialcharsbx($_REQUEST["user_nm"]);
		$arResult["user_ph"] = htmlspecialcharsbx($_REQUEST["user_ph"]);
		$arResult["win_result"] = htmlspecialcharsbx($_REQUEST["comment"]);
		$arResult["AUTHOR_NAME"] = htmlspecialcharsbx($_REQUEST["user_nm"]);
		$arResult["AUTHOR_PHONE"] = htmlspecialcharsbx($_REQUEST["user_ph"]);
		$arResult["AUTHOR_THEME"] = htmlspecialcharsbx($_REQUEST["theme"]);
		$arResult["AUTHOR_ADDRESS"] = htmlspecialcharsbx($_REQUEST["address"]);
		$arResult["AUTHOR_PERIOD"] = htmlspecialcharsbx($_REQUEST["period"]);
		$arResult["AUTHOR_MODEL"] = htmlspecialcharsbx($_REQUEST["model"]);
		$arResult["AUTHOR_SCORE_MONTAGE"] = htmlspecialcharsbx($_REQUEST["score_montage"]);
		$arResult["AUTHOR_SCORE_DOOR"] = htmlspecialcharsbx($_REQUEST["score_door"]);
		$arResult["AUTHOR_SCORE_SERVICE"] = htmlspecialcharsbx($_REQUEST["score_service"]);
		$arResult["AUTHOR_EMAIL"] = htmlspecialcharsbx($_REQUEST["user_ml"]);
	}
	else $arResult["ERROR_MESSAGE"][] = GetMessage("MF_SESS_EXP");
}
elseif($_REQUEST["success"] == $arResult["PARAMS_HASH"])
{
	$arResult["OK_MESSAGE"] = $arParams["OK_TEXT"];
}

if ($arParams["FORM_ID"]){
    $arResult["FORM_ID"] = $arParams["FORM_ID"];
}
if ($arParams["FORM_NAME"]){
    $arResult["FORM_NAME"] = $arParams["FORM_NAME"];
}
if ($arParams["FORM_TITLE"]){
    $arResult["FORM_TITLE"] = $arParams["FORM_TITLE"];
}

if(empty($arResult["ERROR_MESSAGE"]))
{
	if($USER->IsAuthorized())
	{
		$arResult["AUTHOR_NAME"] = $USER->GetFormattedName(false);
		$arResult["AUTHOR_EMAIL"] = htmlspecialcharsbx($USER->GetEmail());
	}
	else
	{
		if(strlen($_SESSION["MF_NAME"]) > 0)
			$arResult["AUTHOR_NAME"] = htmlspecialcharsbx($_SESSION["MF_NAME"]);
		if(strlen($_SESSION["MF_EMAIL"]) > 0)
			$arResult["AUTHOR_EMAIL"] = htmlspecialcharsbx($_SESSION["MF_EMAIL"]);
	}
}

if($arParams["USE_CAPTCHA"] == "Y")
	$arResult["capCode"] =  htmlspecialcharsbx($APPLICATION->CaptchaGetCode());

$this->IncludeComponentTemplate();
