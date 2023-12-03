<?php

global $sysDB, $sysData, $sysVar;

$sysDB = ['static' => false];

if (in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
	$sysDB['host'] = 'localhost';
	$sysDB['db'] = 'hostinger_gieba';
	$sysDB['user'] = 'root';
	$sysDB['pass'] = '';
	$sysDB['domain'] = 'http://localhost/Gi&Ba';
	$sysDB['admin_path'] = 'http://localhost/Gi&Ba/admin';
} else {
	$sysDB['host'] = 'localhost';
	$sysDB['db'] = 'u944138369_gieba';
	$sysDB['user'] = 'u944138369_gieba';
	$sysDB['pass'] = 'O3a=H3lvJ';
	$sysDB['domain'] = 'https://giebapromotion.com';
	$sysDB['admin_path'] = 'https://giebapromotion.com/admin';
}

$sysData = [
	'name' => 'GI&BA',
	'name_full' => 'GI&BA di Shipping Dog SNC',
	'desc_it' => 'Abbigliamento e accessori personalizzati',
	'desc_shr_it' => 'Abbigliamento e accessori personalizzati',
	'desc_en' => '',
	'desc_shr_en' => '',
	'desc_pt' => '',
	'desc_shr_pt' => '',

	'lang' => 'it',
	'multi_lang' => false,

	'logo' => $sysDB['domain'] . '/app/resources/logo/logo.jpg',
	'VAT' => '02397230224',

	'cont_fb' => 'https://www.facebook.com/GieBaPromotion',
	'cont_fb_link' => 'https://www.facebook.com/GieBaPromotion',
	'cont_inst' => '',
	'cont_inst_link' => '',
	'cont_yt' => '',
	'cont_sp' => '',
	'cont_twit' => '',
	'cont_twit_link' => '',
	'cont_mail' => 'giebapromotion@gmail.com',
	'cont_mail_link' => 'mailto:giebapromotion@gmail.com',
	'cont_tel' => '0462 230218',
	'cont_tel_link' => 'tel:0462230218',
	'cont_whats' => '3517419263',
	'cont_whats_link' => 'https://wa.me/3517419263',
	'cont_tele' => '',
	'cont_pin' => '',
	'cont_pin_link' => '',
	'cont_tiktok' => '',
	'cont_tiktok_link' => '',
	'cont_web_link' => '',

	'google_analytics' => false,

	'timezone' => 'Europe/Rome',
];

$sysVar = [
	'c1' => '#EEAD47',
	'c1_hover' => '#E8930D',
	'c2' => '#271D28',
	'c3' => '#EBEAE8',

	'main_font_name' => 'Poppins',
	'main_font_url' => 'Poppins:ital,wght@0,300;0,400;0,600;0,700;0,900;1,600',
	'side_font_name' => 'Playfair Display',
	'side_font_url' => 'Playfair+Display',
	'text_font_name' => 'Open Sans',
	'text_font_url' => 'Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400',

	'logged_redirect' => '/',
];
