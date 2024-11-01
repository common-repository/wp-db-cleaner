<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Db_Cleaner
 * @subpackage Db_Cleaner/admin/template
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class='wrap'>
	<div class="lm-dbc-header">
		<h2 class="nav-tab-wrapper">
			<a href="<?php echo esc_url( admin_url( 'tools.php?page=db-clean' ) ); ?>"
			   class="nav-tab <?php echo ( ! isset( $_REQUEST['subpage'] ) ) ? 'nav-tab-active' : ''; ?>">Find/Remove
				Orphan
				Data</a>
			<a href="<?php echo esc_url( admin_url( 'tools.php?page=db-clean&subpage=2' ) ); ?>"
			   class="nav-tab <?php echo isset( $_REQUEST['subpage'] ) ? 'nav-tab-active' : ''; ?>">Find/Remove
				Duplicate
				Data</a>
		</h2>
	</div>
	<div class="lm-dbc-content">
		<?php
		if ( ! isset( $_REQUEST['subpage'] ) ) {
			lm_dbc_orphan_ui();
		} else {
			lm_dbc_duplicate_ui();
		}
		$method_name = _dbc_get_selected_tab();
		$is_set      = __get_dbc_class_method( $method_name, 'get_' );
		if ( ! empty( $is_set ) ) {
			$tab = str_replace( '_', ' ', $method_name );
			?>

			<div class="lm-dbc-support">
				<div class="dbc_delete_action">
					<h2> Delete all <?php echo $tab; ?> data?</h2>

					<form method="post">
						<?php wp_nonce_field( 'delete_' . $method_name ); ?>
						<input id="dbc_agree" type="text" value="" autocomplete="off" name="dbc_agree"/>

						<p class="description">Type <b>agree</b> to delete data.</p>
						<button class="button button-primary" type="submit" value="i_accept" name="submit">Delete
						</button>
					</form>
				</div>
			</div>
		<?php } ?>
	</div>
</div>
