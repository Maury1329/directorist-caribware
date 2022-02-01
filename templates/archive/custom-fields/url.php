<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;

if ( !$listings->field_value() ) {
	return;
}
?>

<div class="directorist-listing-card-url">
	<?php $listings->print_icon(); ?>
	<?php $listings->print_label(); ?>
	<a target="_blank" href="<?php echo esc_url( $listings->field_value() ); ?>"><?php echo esc_html( $listings->field_value() ); ?></a>
</div>