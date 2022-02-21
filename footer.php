<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Statuspage
 * @since 1.0.0
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer default-max-width" role="contentinfo">
	    <div class="content"><? echo get_theme_mod( 'statuspage_footer', '' ); ?></div>
	</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
