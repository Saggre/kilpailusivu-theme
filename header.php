<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <title><?php wp_title( '' ); ?><?php if ( wp_title( '', false ) ) {
			echo ' :';
		} ?><?php bloginfo( 'name' ); ?></title>

    <link href="//www.google-analytics.com" rel="dns-prefetch">
    <link href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon.ico" rel="shortcut icon">
    <link href="<?php echo get_template_directory_uri(); ?>/img/icons/touch.png" rel="apple-touch-icon-precomposed">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <meta name="description" content="<?php bloginfo( 'description' ); ?>">

	<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>

<div class="wrapper">

    <header class="header clear" role="banner">
        <div class="grid-x align-middle align-center" style="height:100%;">
            <div class="cell small-12 medium-shrink large-shrink">
                <span class="header-text">Palvelun tarjoaa</span>
            </div>
            <div class="cell small-12 medium-shrink large-auto">
                <div class="header-logo">
                    <a href="<?php echo home_url(); ?>">
                        <img src="<?php echo get_template_directory_uri(); ?>/img/logo.png" alt="Logo">
                    </a>
                </div>
            </div>
        </div>

    </header>