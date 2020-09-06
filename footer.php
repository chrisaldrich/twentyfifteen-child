<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "site-content" div and all content after.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?>

	</div><!-- .site-content -->

	<script type="text/javascript" id="pt-script" src="/frag.js" async></script>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<?php
				/**
				 * Fires before the Twenty Fifteen footer text for footer customization.
				 *
				 * @since Twenty Fifteen 1.0
				 */
				do_action( 'twentyfifteen_credits' );
			?>
			<?php
			if ( function_exists( 'the_privacy_policy_link' ) ) {
				the_privacy_policy_link( '', '<span role="separator" aria-hidden="true"></span>' );
			}
			?>
			<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'twentyfifteen' ) ); ?>" class="imprint">
				<?php printf( __( 'Proudly powered by %s', 'twentyfifteen' ), 'WordPress' ); ?>
			</a> with <a href="http://indieweb.org" rel="nofollow" alt="IndieWeb">principles from <img src="/wp-content/uploads/2018/01/indieweb-badge.png" alt="IndieWebCamp" width="80" height="15" style="image-rendering:pixelated;" /></a><br /> <a href="http://microformats.org/wiki/get-started"><img src="/wp-content/uploads/2018/01/microformats-badge.png" width="80" height="15" alt="Microformats.org" style="image-rendering:pixelated;"></a> <a title="This site accepts webmentions." href="https://www.w3.org/TR/webmention/"><img src="/wp-content/uploads/2018/01/webmention-badge.png" width="80" height="15" alt="Webmention" style="image-rendering:pixelated;"></a> <a href="https://creativecommons.org/licenses/by/3.0/"><img src="/wp-content/uploads/2018/01/cc-commons-badge.png" width="80" height="15" alt="Creative Commons Attribution 3.0" style="image-rendering:pixelated;"></a><br />&copy; 1996-2020 Chris Aldrich | Except where otherwise noted, text content<br />on this site is licensed under a <a href="https://creativecommons.org/licenses/by/3.0/">Creative Commons Attribution 3.0 License</a>.
		</div><!-- .site-info -->
	</footer><!-- .site-footer -->

</div><!-- .site -->

<?php wp_footer(); ?>

</body>
</html>