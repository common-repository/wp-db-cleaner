<?php
/**
 * Created by PhpStorm.
 * User: spock
 * Date: 2/10/15
 * Time: 4:33 PM
 */
if ( ! class_exists( 'Wp_Db_Cleaner_List' ) ) {

	// WP_List_Table is not loaded automatically so we need to load it in our application
	if ( ! class_exists( 'WP_List_Table' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}

	class Wp_Db_Cleaner_List extends WP_List_Table {

		var $tab_array;
		var $url;
		var $obj_data;
		var $total_item;

		static $limit = 10;

		/**
		 * Wp_Db_Cleaner_List constructor.
		 *
		 * @param array|string $tab_array
		 * @param $url
		 * @param $obj
		 */
		public function __construct( $tab_array, $url, $obj ) {

			$this->tab_array = $tab_array;
			$this->url       = $url;
			$this->obj_data  = $obj;
			if ( isset( $_REQUEST['subpage'] ) ) {
				parent::__construct( array(
					'singular' => $_REQUEST['subpage'],
					'plural'   => $_REQUEST['subpage'],
					'ajax'     => false
				) );
			}
		}

		function get_columns() {
			$columns = array();
			if ( isset( $this->items[0] ) ) {
				foreach ( $this->items[0] as $key => $val ) {
					$columns[ $key ] = $key;
				}
			}

			return $columns;
		}

		public function views() {
			$views = $this->get_views();
			if ( empty( $views ) )
				return;
			echo "<ul class='subsubsub'>\n";
			foreach ( $views as $class => $view ) {
				$views[ $class ] = "\t<li class='$class'>$view";
			}
			echo implode( " |</li>\n", $views ) . "</li>\n";
			echo "</ul>";
		}

		protected function get_views() {
			if ( empty( $this->tab_array ) ) {
				return array();
			}
			$views          = array();
			$first_selected = false;
			if ( ! isset( $_REQUEST['tab'] ) ) {
				$first_selected = true;
			}
			foreach ( $this->tab_array as $key => $val ) {
				$url = esc_url( $this->url . '&tab=' . $key );

				$count_n     = call_user_func_array( array(
					$this->obj_data,
					'get_' . $key
				), array( true ) );
				$_is_current = lm_dbc_is_active_tab( $key, 'class="current"', $first_selected, false );
				if ( ! empty( $_is_current ) ) {
					$this->total_item = $count_n;
				}
				$count         = '<span class="count">(' . $count_n . ')</span>';
				$views[ $key ] = "<a " . $_is_current . " href='$url'>$val $count</a>";
				if ( $first_selected ) {
					$first_selected = false;
				}
			}

			return $views;
		}

		/**
		 * get CSS classes
		 * @return array
		 */
		protected function get_table_classes() {
			return array( 'widefat', 'fixed', 'striped', 'lm_dbc_table' );
		}

		/**
		 *
		 * @return array
		 */
		protected function get_sortable_columns() {
			return array();
		}


		/** Text displayed when no customer data is available */
		public function no_items() {
			_e( 'No data available.', 'sp' );
		}

		public function prepare_items() {
			$this->items           = lm_dbc_get_table_content();
			$this->_column_headers = array( $this->get_columns(), array(), array() );
			$total_pages           = ceil( $this->total_item / self::$limit );

			$this->set_pagination_args( array(
				'total_items' => $this->total_item,
				'total_pages' => $total_pages,
				'per_page'    => self::$limit
			) );
		}

		/**
		 * Generates content for a single row of the table
		 *
		 * @since 3.1.0
		 * @access public
		 *
		 * @param object $item The current item
		 */
		public function single_row( $item ) {
			echo '<tr>';
			foreach ( $item as $key => $val ) { ?>
				<td><?php echo esc_attr( $val ); ?></td>
			<?php }
			echo '</tr>';
		}
	}
}
