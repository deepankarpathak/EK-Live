<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

$site_url   = get_option( 'siteurl' );
$assets_url = untrailingslashit( YWRR_ASSETS_URL );

if ( strpos( $assets_url, $site_url ) === false ) {
    $assets_url = $site_url . $assets_url;
}

$body_css       = 'margin: 0; padding: 0; min-width: 100%!important; font-family: \'Raleway\', sans-serif;';
$content_css    = 'width: 100%; max-width: 600px;';
$overheader_css = 'padding:30px 40px; font-size: 30px; text-transform: uppercase; color: #495361; font-weight: 800; line-height: 35px; text-align: center;';
$header_css     = 'padding: 10px; height: 240px; background: #d75957 url(' . $assets_url . '/images/header-bg.jpg) no-repeat center center; line-height: 40px; font-size: 30px; text-align: center; color: #ffffff;';
$mailbody_css   = 'padding: 50px 40px; border-left: 2px solid #cfcfcf; border-right: 2px solid #cfcfcf; font-size: 14px; color: #656565; line-height: 25px;';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo get_option( 'blogname' ); ?></title>
    <style type="text/css">
        @import url(https://fonts.googleapis.com/css?family=Raleway:800,700,400);

        @media only screen and (max-width: 599px) {
            .header {
                line-height: 30px !important;
                font-size: 25px !important;
            }

            .items {
                height: auto !important;
                text-align: center !important;
            }

            .items > img {
                float: none !important;
                margin: 0 auto 20px auto !important;
            }
        }
    </style>
</head>

<body style="<?php echo $body_css; ?>" bgcolor="#ffffff">
<table width="100%" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <!--[if (gte mso 9)|(IE)]>
            <table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td><![endif]-->
            <table style="<?php echo $content_css; ?>" align="center" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td style="<?php echo $overheader_css; ?>">
                        <img src="<?php echo $assets_url; ?>/images/cart-icon-red.png" style="" alt="" />
                        <?php echo get_option( 'blogname' ); ?>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#6dcbbb" class="header" style="<?php echo $header_css; ?>">
                        <?php echo $email_heading; ?>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#ffffff" style="<?php echo $mailbody_css; ?>">