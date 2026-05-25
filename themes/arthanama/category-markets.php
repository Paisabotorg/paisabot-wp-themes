<?php
/**
 * Template Name: Category Markets Redirect
 *
 * Redirects /category/markets/ → markets.paisabot.com with the correct ?from= language param.
 */
defined('ABSPATH') || exit;

$_site = get_bloginfo('url');
$_lang = 'en';
if (strpos($_site, 'hi.paisabot.com') !== false)  $_lang = 'hi';
elseif (strpos($_site, 'ml.paisabot.com') !== false)  $_lang = 'ml';
elseif (strpos($_site, 'tel.paisabot.com') !== false) $_lang = 'tel';

wp_redirect('https://markets.paisabot.com' . ($_lang !== 'en' ? '?from=' . $_lang : ''), 302);
exit;
