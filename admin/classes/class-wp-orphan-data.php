<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Db_Cleaner
 * @subpackage Db_Cleaner/admin/orphan
 */

/**
 * The orphan data related functionality.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Db_Cleaner
 * @subpackage Db_Cleaner/admin
 * @author     Utkarsh <iamutkarsh@live.com>
 */
if ( ! class_exists( 'Wp_Orphan_Data' ) ) {
	class Wp_Orphan_Data {

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 */
		public function __construct() {

		}

		public static function get_array() {
			return array(
				'wp_posts_orphan_rows'              => 'WP Posts',
				'wp_postmeta_orhpan_rows'           => 'WP Postmeta',
				'wp_term_taxonomy_orphan_rows'      => 'WP Term Taxonomy',
				'wp_term_relationships_orphan_rows' => 'WP Term Taxonomy Relationships',
				'wp_usermeta_orphan_rows'           => 'WP Usermeta',
				'wp_posts_author_orphan_rows'       => 'WP Posts Author',
			);
		}

		/**
		 * Get all orphan data from wp_posts table
		 *
		 * @param bool|false $count
		 *
		 * @return mixed
		 */
		public function get_wp_posts_orphan_rows( $count = false, $offset = 0, $limit = 0 ) {

			global $wpdb;

			$select = ( $count ) ? 'COUNT(*)' : 'posts.post_title, posts.post_status, posts.post_type, posts.comment_count, posts.post_author, posts.post_date';

			$query = ( "SELECT $select FROM {$wpdb->posts} posts LEFT JOIN {$wpdb->posts} child ON (posts.post_parent = child.ID) WHERE (posts.post_parent <> 0) AND (child.ID IS NULL)" );

			$query = __dbc_pagination( $query, $count, $offset, $limit );

			if ( $count ) {
				return $wpdb->get_var( $query );
			} else {
				return $wpdb->get_results( $query );
			}

		}

		/**
		 * Delete wp_posts table orphan rows
		 *
		 * @return false|int
		 */
		public function delete_wp_posts_orphan_rows() {

			global $wpdb;
			$query = "DELETE posts.* FROM {$wpdb->posts} posts LEFT JOIN {$wpdb->posts} child ON (posts.post_parent = child.ID) WHERE (posts.post_parent <> 0) AND (child.ID IS NULL)";
			return $wpdb->query( $query );

		}

		/**
		 * Get all orphan data fro wp_postmeta table
		 *
		 * @param bool|false $count
		 *
		 * @return mixed
		 */
		public function get_wp_postmeta_orhpan_rows( $count = false ) {

			global $wpdb;

			$select = ( $count ) ? 'COUNT(*)' : '*';

			$query = ( "SELECT $select FROM {$wpdb->postmeta} meta LEFT JOIN {$wpdb->posts} posts ON (meta.post_id = posts.ID) WHERE (posts.ID IS NULL)" );

			if ( $count ) {
				return $wpdb->get_var( $query );
			} else {
				return $wpdb->get_results( $query );
			}

		}

		/**
		 * Delete wp_postmeta table orphan rows
		 *
		 * @return false|int
		 */
		public function delete_wp_postmeta_orhpan_rows() {

			global $wpdb;
			$query = "DELETE postmeta.* FROM {$wpdb->postmeta} postmeta LEFT JOIN {$wpdb->posts} posts ON (postmeta.post_id = posts.ID) WHERE (posts.ID IS NULL)";
			return $wpdb->query( $query );

		}

		/**
		 * Get all orphan data from wp_term_taxonomy table
		 *
		 * @param bool|false $count
		 *
		 * @return mixed
		 */
		public function get_wp_term_taxonomy_orphan_rows( $count = false ) {

			global $wpdb;

			$select = ( $count ) ? 'COUNT(*)' : '*';

			$query = ( "SELECT $select FROM {$wpdb->term_taxonomy} taxonomy LEFT JOIN {$wpdb->terms} terms ON (taxonomy.term_id = terms.term_id) WHERE (terms.term_id IS NULL)" );

			if ( $count ) {
				return $wpdb->get_var( $query );
			} else {
				return $wpdb->get_results( $query );
			}

		}

		/**
		 * Delete wp_term_taxonomy table orphan rows
		 *
		 * @return false|int
		 */
		public function delete_wp_term_taxonomy_orphan_rows() {

			global $wpdb;
			$query = "DELETE term_taxonomy.* FROM {$wpdb->term_taxonomy} term_taxonomy LEFT JOIN {$wpdb->terms} terms ON (term_taxonomy.term_id = terms.term_id) WHERE (terms.term_id IS NULL)";
			return $wpdb->query( $query );

		}

		/**
		 * Get all orphan data from wp_term_relationships table
		 *
		 * @param bool|false $count
		 *
		 * @return mixed
		 */
		public function get_wp_term_relationships_orphan_rows( $count = false ) {

			global $wpdb;

			$select = ( $count ) ? 'COUNT(*)' : '*';

			$query = ( "SELECT $select FROM {$wpdb->term_relationships} relationships LEFT JOIN {$wpdb->term_taxonomy} taxonomy ON (relationships.term_taxonomy_id = taxonomy.term_taxonomy_id) WHERE (taxonomy.term_taxonomy_id IS NULL)" );

			if ( $count ) {
				return $wpdb->get_var( $query );
			} else {
				return $wpdb->get_results( $query );
			}

		}

		/**
		 * Delete wp_term_relationships table orphan rows
		 *
		 * @return false|int
		 */
		public function delete_wp_term_relationships_orphan_rows() {

			global $wpdb;
			$query = "DELETE term_relationships.* FROM {$wpdb->term_relationships} term_relationships LEFT JOIN {$wpdb->term_taxonomy} term_taxonomy ON (term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id) WHERE (term_taxonomy.term_taxonomy_id IS NULL)";
			return $wpdb->query( $query );

		}

		/**
		 * Get all orphan data fro wp_usermeta table
		 *
		 * @param bool|false $count
		 *
		 * @return mixed
		 */
		public function get_wp_usermeta_orphan_rows( $count = false ) {

			global $wpdb;

			$select = ( $count ) ? 'COUNT(*)' : '*';

			$query = ( "SELECT $select FROM {$wpdb->usermeta} usermeta LEFT JOIN {$wpdb->users} users ON (usermeta.user_id = users.ID) WHERE (users.ID IS NULL)" );

			if ( $count ) {
				return $wpdb->get_var( $query );
			} else {
				return $wpdb->get_results( $query );
			}

		}

		/**
		 * Delete wp_usermeta table orphan rows
		 *
		 * @return false|int
		 */
		public function delete_wp_usermeta_orphan_rows() {

			global $wpdb;
			$query = "DELETE usermeta.* FROM {$wpdb->usermeta} usermeta LEFT JOIN {$wpdb->users} users ON (usermeta.user_id = users.ID) WHERE (users.ID IS NULL)";
			return $wpdb->query( $query );

		}

		/**
		 * Get all orphan data fro wp_posts table with no author id
		 *
		 * @param bool|false $count
		 *
		 * @return mixed
		 */
		public function get_wp_posts_author_orphan_rows( $count = false ) {

			global $wpdb;

			$select = ( $count ) ? 'COUNT(*)' : 'posts.post_title, posts.post_status, posts.post_type, posts.comment_count, posts.post_author, posts.post_date';

			$query = ( "SELECT $select FROM {$wpdb->posts} posts LEFT JOIN {$wpdb->users} users ON (posts.post_author = users.ID) WHERE (users.ID IS NULL)" );

			if ( $count ) {
				return $wpdb->get_var( $query );
			} else {
				return $wpdb->get_results( $query );
			}

		}

		/**
		 * Delete wp_posts author table orphan rows
		 *
		 * @return false|int
		 */
		public function delete_wp_posts_author_orphan_rows() {

			global $wpdb;
			$query = "DELETE posts.* FROM {$wpdb->posts} posts LEFT JOIN {$wpdb->users} users ON (posts.post_author = users.ID) WHERE (users.ID IS NULL)";
			return $wpdb->query( $query );

		}

	}

}

if ( ! function_exists( 'lm_dbc_orphan_ui' ) ) {
	function lm_dbc_orphan_ui() {
		global $orphan_data;
		$myListTable = new Wp_Db_Cleaner_List( Wp_Orphan_Data::get_array(), admin_url( 'tools.php?page=db-clean' ), $orphan_data );
		$myListTable->views();
		?>
		<div class="lm-dbc-table">
			<?php
			$myListTable->prepare_items();
			$myListTable->display();
			?>
		</div>

		<?php
	}
}
