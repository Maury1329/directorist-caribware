<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.2.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div <?php $listings->wrapper_class(); $listings->data_atts(); ?>>

	<?php
	$listings->mobile_view_filter_template();
	$listings->directory_type_nav_template();
	$listings->header_bar_template();
	$listings->full_search_form_template();
	$listings->archive_view_template();
	?>

</div>