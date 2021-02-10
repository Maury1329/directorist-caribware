<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Directorist_Single_Listing;

if ( ! defined( 'ABSPATH' ) ) exit;

$listing = Directorist_Single_Listing::instance();
?>

<div class="edit_btn_wrap">

	<?php if ( $listing->current_user_is_author() ): ?>

		<?php if ( $listing->display_back_link() && !$listing->has_redirect_link() ): ?>

		<a href="javascript:history.back()" class="atbd_go_back"><i class="<?php atbdp_icon_type(true); ?>-angle-left"></i> <?php esc_html_e( 'Go Back', 'directorist'); ?></a>

	<?php endif; ?>

	<div>

		<?php if ( $listing->submit_link() ): ?>
			<a href="<?php echo esc_url( $listing->submit_link() ); ?>" class="btn btn-success"><?php esc_html_e( 'Continue', 'directorist' ); ?></a>
		<?php endif; ?>

		<a href="<?php echo esc_url( $listing->edit_link() ) ?>" class="btn btn-outline-light"><span class="<?php atbdp_icon_type(); ?>-edit"></span> <?php esc_html_e( 'Edit', 'directorist' ); ?></a>

	</div>

	<?php elseif( $listing->display_back_link() ): ?>

		<a href="javascript:history.back()" class="atbd_go_back"><i class="<?php atbdp_icon_type(true); ?>-angle-left"></i> <?php esc_html_e( 'Go Back', 'directorist'); ?></a>

	<?php endif; ?>

</div>