<?php


/**
 * Find / remove duplicate and orphaned data
 */
class Wp_Orphan_CLI extends WP_CLI_Command {
	/**
	 * Fires when wp cli called cleaner command
	 *
	 * ## OPTIONS
	 *
	 * list                     = Lists all accepted param
	 * attachment_meta_duplicate = Attachment Meta
	 * post_meta_duplicate       = Post Meta
	 * missing_attachment_meta   = Missing Attachment Meta
	 * post_meta_locks           = Post Meta Locks
	 * wp_transients             = Transients
	 * post_revisions            = Post Revisions
	 *
	 *
	 * ## Example
	 * wp cleaner duplicate wp_transients --limit=20 --offset=10 or wp cleaner duplicate wp_transients --l=20 --o=10
	 * wp cleaner duplicate wp_transients delete
	 */
	public function duplicate( $args, $assoc_args ) {
		$this->cli_callback($args,$assoc_args);
	}

	/**
	 * Fires when wp cli called cleaner command
	 *
	 * ## OPTIONS
	 *
	 * wp_posts_orphan_rows             = WP Posts
	 * wp_postmeta_orhpan_rows         = WP Postmeta
	 * wp_term_taxonomy_orphan_rows     = WP Term Taxonomy
	 * wp_term_relationships_orphan_rows = WP Term Taxonomy Relationships
	 * wp_usermeta_orphan_rows          = WP Usermeta
	 * wp_posts_author_orphan_rows      = WP Posts Author
	 *
	 *
	 * ## Example
	 * wp cleaner orphan wp_posts_orphan_rows --limit=20 --offset=10 or wp cleaner orphan wp_posts_orphan_rows --l=20 --o=10 or
	 * wp cleaner orphan wp_posts_orphan_rows delete
	 */
	public function orphan( $args, $assoc_args ) {
		$this->cli_callback($args,$assoc_args);
	}

	private function cli_callback( $args,$assoc_args ) {
		$first_arg = '';
		if ( ! empty( $args[0] ) ) {
			$first_arg = $args[0];
		}
		$second_arg = '';
		if ( ! empty( $args[1] ) ) {
			$second_arg = $args[1];
		}

		// set limit from param for displaying record
		$limit = 20;
		if ( isset( $assoc_args['l'] ) ) {
			$limit = $assoc_args['l'];
		}
		if ( isset( $assoc_args['limit'] ) ) {
			$limit = $assoc_args['limit'];
		}
		// set offset
		$offset = 0;
		if ( isset( $assoc_args['o'] ) ) {
			$limit = $assoc_args['o'];
		}
		if ( isset( $assoc_args['offset'] ) ) {
			$limit = $assoc_args['offset'];
		}

		if ( $first_arg != 'list' && strtolower( $first_arg ) != 'delete' ) {
			$callback = __get_dbc_class_method( $first_arg, 'get_' );
			if ( ! empty( $callback ) ) {
				$data = call_user_func_array( $callback, array( false, $offset, $limit ) );
				if ( ! empty( $data ) ) {
					$keys = array();
					foreach ( $data as $row ) {
						foreach ( $row as $key => $val ) {
							$keys[] = $key;
						}
						break;
					}

					$formatter = new \WP_CLI\Formatter( $assoc_args, $keys );
					$formatter->display_items( $data );
				} else {
					error_log( 'No data found!' );
				}
			} else {
				error_log( 'Please check : wp cleaner' );
			}
		} else if ( strtolower( $first_arg ) == 'delete' && !empty($second_arg) ) {
			// delete
			$callback = __get_dbc_class_method( $second_arg, 'delete_' );
			if ( ! empty( $callback ) ) {
				$data = call_user_func_array( $callback, array() );
				error_log( "$data rows deleted successfully!" );
			} else {
				error_log( 'Please check : wp cleaner' );
			}
		}
	}
}

WP_CLI::add_command( 'cleaner', 'Wp_Orphan_CLI' );
