<?php

/*
 * You can add your own functions here. You can also override functions that are
 * called from within the parent theme.
 */

/**
 * Adding filter below to keep Jetpack Publicize from triggering without explicitly choosing boxes first
 * Found via http://jetpack.me/2013/10/15/ever-accidentally-publicize-a-post-that-you-didnt/
 */
add_filter( 'publicize_checkbox_default', '__return_false' );


/**
 * Adding filter allows jetpack to toggle whether subscribers receive email update of the post
 * Found via https://jetpack.me/support/subscriptions/
 * Note: can't use both this filter AND the following filter at the same time due to collisions. Use one OR the other.
 * add_filter( 'jetpack_allow_per_post_subscriptions', '__return_true' );
*/

/** jetpack_subscriptions_exclude_these_categories: Exclude certain categories from ever emailing to subscribers.
 * Filter: jetpack_subscriptions_exclude_these_categories
 * Will never send subscriptions emails to whatever categories are in that array

add_filter( 'jetpack_subscriptions_exclude_these_categories', 'exclude_these' );
function exclude_these( $categories ) {
$categories = array( 'social-stream', 'checkin', 'live-stream');
return $categories;
}
*/

/**
 * Filter to remove jetpack sharing when viewed from mobile set up (via https://jetpack.com/2016/04/15/hook-month-customizing-sharing/#more-12360)
 */
// Check if we are on mobile
function jetpack_developer_is_mobile() {

    // Are Jetpack Mobile functions available?
    if ( ! function_exists( 'jetpack_is_mobile' ) ) {
        return false;
    }

    // Is Mobile theme showing?
    if ( isset( $_COOKIE['akm_mobile'] ) && $_COOKIE['akm_mobile'] == 'false' ) {
        return false;
    }

    return jetpack_is_mobile();
}

// Let's remove the sharing buttons when on mobile
function jetpack_developer_maybe_add_filter() {

    // On mobile?
    if ( jetpack_developer_is_mobile() ) {
        add_filter( 'sharing_show', '__return_false' );
    }
}
add_action( 'wp_head', 'jetpack_developer_maybe_add_filter' );

/**
 * Temporary Code to accept all webmentions as suggested by snarfed at https://github.com/indieweb/wordpress-indieweb/issues/38  */
   function unspam_webmentions($approved, $commentdata) {
     return $commentdata['comment_type'] == 'webmention' ? 1 : $approved;
   }
   add_filter('pre_comment_approved', 'unspam_webmentions', '99', 2);


// For allowing [shortcode] in widget text per http://stephanieleary.com/2010/02/using-shortcodes-everywhere/
add_filter( 'widget_text', 'shortcode_unautop');
add_filter( 'widget_text', 'do_shortcode');

add_filter( 'the_title', 'shortcode_unautop');
add_filter( 'the_title', 'do_shortcode');


/*
 * Function to add u-featured to the post thumbnail
 * per details at https://miklb.com/microformats2-wordpress-and-featured-images-classes/ and
 * https://wordpress.stackexchange.com/questions/102158/add-class-name-to-post-thumbnail/102250#102250
 */

function mf2_featured_image($attr) {
  remove_filter('wp_get_attachment_image_attributes','mf2_featured_image');
  $attr['class'] .= ' u-featured';
  return $attr;
}
add_filter('wp_get_attachment_image_attributes','mf2_featured_image');

/**
 * If the given post is a single post, then add a class to this post.
 *
 * @param    array       $classes    The array of classes being rendered on a single post element.
 * @return   array       $classes    The array of classes being rendered on a single post element.
 * @package  example
 * @since    1.0.0
 */
function iw15_add_post_class_to_single_post( $classes ) {
	if ( is_single() ) {
		array_push( $classes, 'h-entry' );
	} // end if
	return $classes;
}
add_filter( 'post_class', 'iw15_add_post_class_to_single_post' );

/**
 * Wraps the_content in e-content
 */
function iw15_the_content( $content ) {
	if ( is_feed() ) {
		return $content;
	}
	$wrap = '<div class="e-content entry-content">';
	if ( empty( $content ) ) {
		return $content;
	}
	return $wrap . $content . '</div>';
}
add_filter( 'the_content', 'iw15_the_content', 1 );

/**
 * Wraps the_excerpt in p-summary
 */
function iw15_the_excerpt( $content ) {
	if ( is_feed() ) {
		return $content;
	}
	$wrap = '<div class="p-summary entry-summary">';
	if ( ! empty( $content ) ) {
		return $wrap . $content . '</div>';
	}
	return $content;
}
add_filter( 'the_excerpt', 'iw15_the_excerpt', 1 );

/**
 * Add `p-category` to tags links
 *
 * @link https://www.webrocker.de/2016/05/13/add-class-attribute-to-wordpress-the_tags-markup/
 *
 * @param  array $links
 * @return array
 */
function sempress_term_links_tag( $links ) {
	$post  = get_post();
	$terms = get_the_terms( $post->ID, 'post_tag' );
	if ( is_wp_error( $terms ) ) {
		return $terms;
	}
	if ( empty( $terms ) ) {
		return false;
	}
	$links = array();
	foreach ( $terms as $term ) {
		$link = get_term_link( $term );
		if ( is_wp_error( $link ) ) {
			return $link;
		}
		$links[] = '<a class="p-category" href="' . esc_url( $link ) . '" rel="tag">' . $term->name . '</a>';
	}
	return $links;
}
add_filter( 'term_links-post_tag', 'sempress_term_links_tag' );

/**
 * Plugin Support - some how this doesn't work. is it looking in the right place?
 */
// require get_template_directory() . '/inc/plugin-support.php';

function twenty_fifteen_indieweb_plugin_support() {
	/*
	 * Adds support for Syndication Links
	 */
	if ( class_exists( 'Syn_Meta' ) && has_filter( 'the_content', array( 'Syn_Config', 'the_content' ) ) ) {
		remove_filter( 'the_content', array( 'Syn_Config', 'the_content' ), 30 );
	}
	/*
	 * Adds support for Simple Location
	 */
	if ( class_exists( 'Loc_View' ) && has_filter( 'the_content', array( 'Loc_View', 'location_content' ) ) ) {
		remove_filter( 'the_content', array( 'Loc_View', 'location_content' ), 12 );
	}
}
add_action( 'init', 'twenty_fifteen_indieweb_plugin_support', 11 );

/* Customizing Jetpack related posts 
 * Forcing it to add to post meta rather than to the_body
 * see details at: https://jetpack.com/support/related-posts/customize-related-posts/ 
 * Adding related portion to the template-tags.php section to add the functionality back
 */
function jetpackme_remove_rp() {
	if ( class_exists( 'Jetpack_RelatedPosts' ) ) {
		$jprp = Jetpack_RelatedPosts::init();
		$callback = array( $jprp, 'filter_add_target_to_dom' );
		remove_filter( 'the_content', $callback, 40 );
	}
}
add_action( 'wp', 'jetpackme_remove_rp', 20 );

/* Adding default image to the Jetpack Related Posts if none exists */
/* function jeherve_custom_image( $media, $post_id, $args ) {
    if ( $media ) {
        return $media;
    } else {
        $permalink = get_permalink( $post_id );
        $url = apply_filters( 'jetpack_photon_url', 'https://boffosocko.com/wp-content/uploads/2014/03/norbert-weiner-teaching.jpg' );
     
        return array( array(
            'type'  => 'image',
            'from'  => 'custom_fallback',
            'src'   => esc_url( $url ),
            'href'  => $permalink,
        ) );
    }
}
add_filter( 'jetpack_images_get_images', 'jeherve_custom_image', 10, 3 );
*/

/* Increase the default of admin UI post editor from returning 30 custom fields via https://www.maketecheasier.com/show-more-than-30-custom-fields-in-wordpress/ 
function increase_postmeta_form_limit() {
	return 120;
}
add_filter('postmeta_form_limit', 'increase_postmeta_form_limit'); */

/* via https://developer.wordpress.org/reference/hooks/postmeta_form_limit/ */
add_filter( 'postmeta_form_limit', function( $limit ) {
    return 250;
} );