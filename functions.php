<?php

add_action( 'after_setup_theme', 'statuspage_theme_setup' );
function statuspage_theme_setup() {
    // Setup localization
    load_theme_textdomain( 'statuspage', get_template_directory() . '/languages' );
}

add_action( 'admin_init', 'statuspage_admin_init' );
function statuspage_admin_init() {

  // Create our own post categories
  wp_create_category ( 'Incident' );
  wp_create_category ( 'Maintenance' );
}

// Using the Meta Box plugin from https://wordpress.org/plugins/meta-box/ we
// define additional fields for the beginning and end of an incident or
// maintenance.

add_filter( 'rwmb_meta_boxes', 'statuspage_register_meta_boxes' );

function statuspage_register_meta_boxes( $meta_boxes ) {

    $meta_boxes[] = [
        'title'   => esc_html__( 'Report', 'statuspage' ),
        'id'      => 'period',
        'context' => 'side',
        'fields'  => [
            [
                'type' => 'datetime',
                'name' => esc_html__( 'Beginning', 'statuspage' ),
                'id'   => 'beginning',
            ],
            [
                'type' => 'datetime',
                'name' => esc_html__( 'End', 'statuspage' ),
                'id'   => 'end',
            ],
        ],
    ];

    return $meta_boxes;
}

// Remove useless fields from comment form.
add_filter( 'comment_form_default_fields', 'statuspage_remove_fields' );
function statuspage_remove_fields( $fields ) {
    if ( isset( $fields[ 'url' ] )) unset( $fields[ 'url' ] );
    if ( isset( $fields[ 'name' ] )) unset( $fields[ 'name' ] );
    if ( isset( $fields[ 'email' ] )) unset( $fields[ 'email' ] );
}

// Setup the customizer
add_action ( 'customize_register', 'statuspage_customize_register' );
function statuspage_customize_register( $wp_customize ) {
    $wp_customize->add_setting( 'statuspage_logo' );
    $wp_customize->add_setting( 'statuspage_intro' );
    $wp_customize->add_setting( 'statuspage_footer' );
    $wp_customize->add_setting( 'statuspage_recent_days' );
    $wp_customize->add_setting( 'masthead_bgcolor' );
    $wp_customize->add_setting( 'footer_bgcolor' );
    $wp_customize->add_setting( 'masthead_color' );
    $wp_customize->add_setting( 'footer_color' );
    $wp_customize->add_setting( 'bgcolor' );
    $wp_customize->add_section( 'statuspage_description', array(
      'title' => __( 'Statuspage', 'statuspage' ),
      'priority' => 30
    ));
    $wp_customize->add_section( 'statuspage_colors', array(
      'title' => __( 'Colors', 'statuspage' ),
      'priority' => 40
    ));
    $wp_customize->add_control(
      new WP_Customize_Media_Control(
        $wp_customize,
        'statuspage_logo', 
        array(
          'mime_type' => 'image',
          'section' => 'title_tagline',
          'label' => __( 'Logo', 'statuspage' ),
        )
      )
    );
    $wp_customize->add_control(
      new WP_Customize_Control(
        $wp_customize,
        'statuspage_intro', 
        array(
          'type' => 'textarea',
          'section' => 'statuspage_description',
          'label' => __( 'Introduction', 'statuspage' ),
        )
      )
    );
    $wp_customize->add_control(
      new WP_Customize_Control(
        $wp_customize,
        'statuspage_footer', 
        array(
          'type' => 'textarea',
          'section' => 'statuspage_description',
          'label' => __( 'Footer', 'statuspage' ),
        )
      )
    );
    $wp_customize->add_control(
      new WP_Customize_Control(
        $wp_customize,
        'statuspage_recent_days', 
        array(
          'type' => 'number',
          'section' => 'statuspage_description',
          'label' => __( 'Show recent days', 'statuspage' ),
        )
      )
    );
        $wp_customize->add_setting( 'statuspage_recent_days' );

    $wp_customize->add_control(
      new WP_Customize_Color_Control(
        $wp_customize,
        'bgcolor', 
        array(
          'default' => '#fff',
          'section' => 'statuspage_colors',
          'label' => __( 'Background', 'statuspage' )
        )
      )
    );
    $wp_customize->add_control(
      new WP_Customize_Color_Control(
        $wp_customize,
        'masthead_color', 
        array(
          'default' => '#fff',
          'section' => 'statuspage_colors',
          'label' => __( 'Masthead', 'statuspage' )
        )
      )
    );
    $wp_customize->add_control(
      new WP_Customize_Color_Control(
        $wp_customize,
        'masthead_bgcolor', 
        array(
          'default' => '#fff',
          'section' => 'statuspage_colors',
          'label' => __( 'Masthead background', 'statuspage' )
        )
      )
    );
    $wp_customize->add_control(
      new WP_Customize_Color_Control(
        $wp_customize,
        'footer_color', 
        array(
          'default' => '#fff',
          'section' => 'statuspage_colors',
          'label' => __( 'Footer', 'statuspage' )
        )
      )
    );
    $wp_customize->add_control(
      new WP_Customize_Color_Control(
        $wp_customize,
        'footer_bgcolor', 
        array(
          'default' => '#fff',
          'section' => 'statuspage_colors',
          'label' => __( 'Footer background', 'statuspage' )
        )
      )
    );

}

// Custom styling.

add_action( 'wp_head', 'statuspage_customize_styles' );
function statuspage_customize_styles() {
    ?>
      <style type="text/css">
        body, .linehead h1 { background-color: <? echo get_theme_mod( 'bgcolor', '#fff' ); ?> }
        #masthead, #masthead a {
          color: <? echo get_theme_mod( 'masthead_color', '#000' ); ?>;
          background-color: <? echo get_theme_mod( 'masthead_bgcolor', '#fff' ); ?>;
        }
        #colophon, #colophon a { 
          color: <? echo get_theme_mod( 'footer_color', '#000' ); ?>;
          background-color: <? echo get_theme_mod( 'footer_bgcolor', '#fff' ); ?>;
        }
      </style>
    <?php
}

/// Custom functions

// Return the title of a report.

function statuspage_get_report_title( $post ) {
    $title = get_the_title( $post );
    $status = statuspage_get_report_status( $post );
    $type = statuspage_get_report_type( $post );
    if ( $status == 'ended' ) {
        if ( $type == 'incident' ) {
            return sprintf(
                __( 'Solved: %s', 'statuspage' ),
                $title
            );
        } else {
            return sprintf(
              __( 'Completed: %s', 'statuspage' ),
              $title
            );
        }
    } elseif ( $type == 'maintenance' and  $status == 'announced' ) {
        $timespan = statuspage_get_report_timespan( $post );
        if ( $timespan['beginning'] ) {
            return sprintf(
              __( 'Announcement: %s (on %s from %s)', 'statuspage' ),
              $title,
              date_i18n( 'l j F', $timespan['beginning'] ),
              date_i18n( 'H:i', $timespan['beginning'] )
            );
        }
    } elseif ( $status == 'completed' ) {
        return sprintf(
          __( 'Completed: %s', 'statuspage' ),
          $title
        );
    }
    return $title;
}
  

// Return the report type, which defaults to 'maintenance' unless the
// Incident WordPress category has been associated with it, in which case it
// will be returned as 'incident'.

function statuspage_get_report_type( $post ) {
    foreach ( get_the_category( $post->ID ) as $category ) {
        if ( $category->name == 'Incident' )
            return 'incident';
    }
    return 'maintenance';
}


// Return the current status of a report.

function statuspage_get_report_status( $post ) {
    $timespan = statuspage_get_report_timespan( $post );
    $now = new DateTime('now', wp_timezone());
    $now = $now->getTimestamp() + $now->getOffset();
	echo '<!-- ' . $timespan['beginning'] . ' -- ' . $now . ' -->';
	if ( $timespan['beginning'] ) {
        if ( $timespan['beginning'] > $now + 30 ) {
            return 'announced';
        } elseif ( $timespan['end'] < $now ) {
            return 'ended';
        }
    }
    return 'started';
}

// Return a "timeline" for a report.

function statuspage_get_report_timeline( $post ) {
  $post_date = new DateTime ( $post->post_date );
  $post_date = $post_date->getTimestamp();
  $timespan = statuspage_get_report_timespan ( $post );
  $timeline = array();
  if ( ! $timespan['beginning'] or ( $post_date < $timespan['beginning'] )) {
      $timeline[] = array(
        'status' => 'announced',
        'timestamp' => $post_date,
        'text' => $post->post_content
      );
      if ( $timespan['beginning'] ) {
          $timeline[] = array( 'status' => 'started',   'timestamp' => $timespan['beginning'] );
      }
  } else {
      if ( $timespan['beginning'] ) {
          $timeline[] = array( 'status' => 'started',   'timestamp' => $timespan['beginning'] );
      }
      $timeline[] = array(
        'status' => 'reported',
        'timestamp' => $post_date,
        'text' => $post->post_content 
      );
  }
  $comments = get_comments( array( 'post_id' => $post->ID, 'order' => 'ASC' ));
  foreach ( $comments as $comment ) {
      $comment_time = new DateTime ( $comment->comment_date);
      $comment_time = $comment_time->getTimestamp();
      if ( $timespan['end'] and ( $comment_time > $timespan['end'] )) {
          $timeline[] = array( 'status' => 'ended', 'timestamp' => $timespan['end'] );
          $timespan['end'] = null;
      }
      $timeline[] = array(
        'status' => 'update',
        'timestamp' => $comment_time,
        'text' => $comment->comment_content
      );
  }
  if ( $timespan['end'] ) {
      $timeline[] = array( 'status' => 'ended', 'timestamp' => $timespan['end'] );
  }
  
  foreach ( $timeline as &$t ) {
      $t['timestamp_formatted'] = date_i18n( 'j F Y H:i', $t['timestamp']);
      $t['date_formatted'] = date_i18n( 'j F Y', $t['timestamp']);
      $t['time_formatted'] = date_i18n( 'H:i', $t['timestamp']);
  }
  unset( $t );
  return $timeline;
  
}

// Return an associative array with the begin and end time of a report as a
// timestamp, or false if either value is unknown.

function statuspage_get_report_timespan( $post ) {
    $r = array();
    foreach ( array( 'beginning', 'end' ) as $f ) {
        $meta = get_post_meta ( $post->ID, $f, true );
        $r[$f] = false;
        if ( $meta and strlen( $meta )) {
            $time = new DateTime ( $meta );
            $r[$f] = $time->getTimestamp();
        }
    }
    return $r;
}

// Return a list of all current reports

function statuspage_get_current_reports( $max = 10 ) {
    $current_reports = array();
    $posts = wp_get_recent_posts( 
      array( 
        'numberposts' => $max,
        'orderby' => 'post_date',
        'order' => 'DESC'
      ),
      WP_Post
    );
    foreach ( $posts as $post ) {
        if ( statuspage_get_report_status( $post ) != 'ended' )
            $current_reports[] = $post;
    }
    return $current_reports;        
}

function statuspage_get_recent_reports( $days = 14, $max = 10 ) {
    $recent_reports = array();
    $posts = wp_get_recent_posts(
      array(
        'numberposts' => $max,
        'orderby' => 'post_date',
        'order' => 'DESC'
      ),
      WP_Post
    );
    $cutoff = new DateTime('now', wp_timezone());
    $cutoff = $cutoff->getTimestamp();
    $cutoff = $cutoff - ( 60 * 60 * 24 * $days );
    foreach ( $posts as $post ) {
        $timespan = statuspage_get_report_timespan( $post );
        if (
          ( statuspage_get_report_status( $post ) == 'ended' ) and 
          ( $timespan['end'] > $cutoff )
        )
            $recent_reports[] = $post;
    }
    return $recent_reports;
}

