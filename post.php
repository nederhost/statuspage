<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Statuspage
 * @since 1.0.0
 */

?>
<?php

    // Expect $post to have the current post.

    $timeline = statuspage_get_report_timeline ( $post );
    $type = statuspage_get_report_type ( $post );
    $status = statuspage_get_report_status ( $post );
  
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <header class="report-header">
        <h2><?php echo statuspage_get_report_title( $post ); ?></h2>
    </header>
    
    <div class="report">
        <div class="content">
            <?php the_content(); ?>
        </div>
    </div>
    
    <div class="report-timeline">
        <ul class="timeline">
            <?php foreach ( $timeline as $t ): ?>
                <li class="timeline-<?php echo $t['status'] ?>">
                    <div class="timestamp">
                      <span class="date"><?php echo $t['date_formatted']; ?></span>
                      <span class="time"><?php echo $t['time_formatted']; ?></span>
                    </div>
                    <div class="update"><?php
                        switch ( $t['status'] ) {
                            case 'announced':
                                echo __( 'Announced', 'statuspage' );
                                break;
	      	            case 'started':
	  	                if ( $type == 'incident' ) {
	  	                    echo __( 'Start of incident', 'statuspage' );
		                } else {
		                    echo __( 'Start of maintenance', 'statuspage' );
		                }
		                break;
		            case 'reported':
		                echo __( 'Reported', 'statuspage' );
		                break;
		            case 'ended':
		                if ( $status == 'ended' ) {
   		                    if ( $type == 'incident' ) {
		                        echo __( 'Incident resolved', 'statuspage' );
		                    } else {
		                        echo __( 'Maintenance finished', 'statuspage' );
		                    }
		                } else {
   		                    if ( $type == 'incident' ) {
		                        echo __( 'Expected time of resolution', 'statuspage' );
		                    } else {
		                        echo __( 'Maintenance finished (expected)', 'statuspage' );
		                    }		                    
		                }
		                break;
		            default:
		                if (
		                    isset($t['text']) and
		                    ! preg_match( '/^[[:blank:]]+$/', $t['text'] )
		                ) {
		                    echo $t['text'];
		                }
		        }
    	            ?></div>
	        </li>
            <?php endforeach ?>
        </ul>
    </div>
    
    <?php 
        if ( get_current_user_id() and comments_open() ) {
            comment_form(
              array(
                'comment_notes_before' => null,
		'comment_notes_after'  => null,
		'label_submit'         => __( 'Post update', 'statuspage' ),
		'logged_in_as'         => null,
	    	'title_reply'          => esc_html__( 'Add an update', 'statuspage' ),
		'title_reply_before'   => '<h3 class="update-title">',
		'title_reply_after'    => '</h3>',
              )
	    );
	}
    ?>

</article>
