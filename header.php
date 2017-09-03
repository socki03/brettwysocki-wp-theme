<?php
/**
 * Welcome to the header!  We're just getting started here.
 *
 * @package WordPress
 * @subpackage Brett_Wysocki
 * @since 1.0
 * @version 1.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<div id="page" class="site">

		<header id="masthead" class="site-header" role="banner">
			<div class="site-branding">
				<a href="<?php bloginfo('url'); ?>" class="logo"><span class="heavy">brett</span><br /><span class="light">wysocki</span></a>
			</div>
		</header><!-- #masthead -->
		
		<div class="menu-toggle">
			<a href="#">
				<span class="menu-content">
					<i class="fa fa-bars"></i>
					<span class="menu">MENU</span>
				</span>
			</a>
		</div>
		
		<div id="content" class="site-content">