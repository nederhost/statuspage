<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Statuspage
 * @since 1.0.0
 */
 
$site_name         = get_bloginfo( 'name' );
$site_description  = get_bloginfo( 'description', 'display' );

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php wp_head(); ?>
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">

        <header id="masthead" class="<?php echo $header_classes; ?>" role="banner">

            <div class="content">
                <?php if ( get_theme_mod( 'statuspage_logo' )) : ?>
                <div class="site-logo"><? echo wp_get_attachment_image( get_theme_mod( 'statuspage_logo' ), 'full'); ?></div>                
                <?php endif ?>
                <?php if ( ! empty( $site_name )) : ?>
                        <?php if ( is_front_page() && is_home() ) : ?>
                                <h1 class="<?php echo esc_attr( $header_class ); ?>"><?php echo $site_name; ?></h1>
                        <?php else : ?>
                                <p class="<?php echo esc_attr( $header_class ); ?>"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo $site_name; ?></a></p>
                        <?php endif; ?>
                <?php endif; ?>

            </div>
        </header><!-- #masthead -->

	<div id="content" class="site-content">
