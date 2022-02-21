<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Statuspage
 * @since 1.0.0
 */

get_header();

// Comments are actually updates, so we would like to display them
// everywhere.
global $withcomments;
$withcomments = true;

// Get at most 10 current reports.
$reports = statuspage_get_current_reports( 10 );

// Get all reports that have been current in the last x days.
$recent_days = get_theme_mod( 'statuspage_recent_days', 14 );
$recent = statuspage_get_recent_reports( $recent_days );

?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		
		<?php echo get_theme_mod( 'statuspage_intro', '' ); ?>

		<?php
		if ( $reports ) {
		
		    // Show the reports.
		    foreach ( $reports as $post ) {
		        get_template_part( 'post' );
		    }
		} else {		
		    get_template_part( 'no-current-reports' );
		}
		
		if ( $recent ) {

		    // Show the recent reports.
		    echo '<div class="linehead"><h1>' . 
		      sprintf ( __( 'Reports of the last %d days', 'statuspage' ), $recent_days ) . 
		      '</h1></div>';
		    foreach ( $recent as $post ) {
		        get_template_part( 'post' );
		    }
		}
		?>

		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php
get_footer();
