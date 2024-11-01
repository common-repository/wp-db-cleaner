<?php
/**
 * Created by PhpStorm.
 * User: spock
 * Date: 2/10/15
 * Time: 1:27 PM
 */


if ( ! function_exists( 'lm_dbc_is_active_tab' ) ) {
	function lm_dbc_is_active_tab( $value, $data, $first_selected, $echo = true ) {
		if ( ( ! isset( $_REQUEST['tab'] ) && $first_selected ) || ( ! empty( $_REQUEST['tab'] ) && $_REQUEST['tab'] == $value ) ) {
			if ( $echo ) {
				echo $data;
			}

			return $data;
		}

		return '';
	}
}

if ( ! function_exists( 'lm_dbc_get_table_content' ) ) {
	function lm_dbc_get_table_content() {
		$result = null;
		$key    = _dbc_get_selected_tab();
		$result = __get_dbc_class_method( $key , 'get_');
		if ( ! empty( $result ) ) {
			if ( isset( $_REQUEST['paged'] ) ) {
				$page = intval( $_REQUEST['paged'] ) * Wp_Db_Cleaner_List::$limit;

				return call_user_func_array( $result, array( false, $page - Wp_Db_Cleaner_List::$limit, $page ) );
			}

			return call_user_func_array( $result, array( false, 0, Wp_Db_Cleaner_List::$limit ) );
		}

		return $result;
	}
}

if ( ! function_exists( '__get_dbc_class_method' ) ) {
	function __get_dbc_class_method( $key, $prefix ) {
		$orphan_array    = Wp_Orphan_Data::get_array();
		$duplicate_array = Wp_Duplicate_Data::get_array();
		global $orphan_data, $duplicate_data;
		if ( isset( $orphan_array[ $key ] ) && method_exists( $orphan_data, $prefix.$key ) ) {
			return array( $orphan_data, $prefix.$key );
		} else if ( isset( $duplicate_array[ $key ] ) && method_exists( $duplicate_data, $prefix.$key ) ) {
			return array( $duplicate_data, $prefix.$key );
		}

		return array();
	}
}

if ( ! function_exists( '__dbc_pagination' ) ) {
	function __dbc_pagination( $query, $count, $offset, $limit ) {
		if ( ! $count && is_numeric( $offset ) && $offset > 0 ) {
			$offset = ' OFFSET ' . $offset;
		} else {
			$offset = '';
		}
		if ( ! $count && is_numeric( $limit ) && $limit > 0 ) {
			$limit = ' LIMIT ' . $limit;
		} else {
			$limit = '';
		}

		return $query . $limit . $offset;
	}
}

if ( ! function_exists( '_dbc_get_selected_tab' ) ) {
	function _dbc_get_selected_tab() {
		$orphan_array    = Wp_Orphan_Data::get_array();
		$duplicate_array = Wp_Duplicate_Data::get_array();

		if ( ! isset( $_REQUEST['tab'] ) ) {
			if ( ! isset( $_REQUEST['subpage'] ) ) {
				reset( $orphan_array );
				$key = key( $orphan_array );
			} else {
				reset( $duplicate_array );
				$key = key( $duplicate_array );
			}
		} else {
			$key = $_REQUEST['tab'];
		}
		if ( ! isset( $orphan_array[ $key ] ) && ! isset( $duplicate_array[ $key ] ) ) {
			wp_die( 'First rule of fight club is we do not talk about flight club.' );
		}

		return $key;
	}
}


if ( ! function_exists( '__dbc_delete_data' ) ) {
	function __dbc_delete_data() {
		$method_name = _dbc_get_selected_tab();
		if ( isset( $_POST['submit'], $_POST['dbc_agree'] ) && $_POST['submit'] == 'i_accept' && strtolower( $_POST['dbc_agree'] ) == 'agree' && check_admin_referer( 'delete_' . $method_name ) ) {
			$callback    = __get_dbc_class_method( $method_name , 'delete_');
			if ( ! empty( $callback ) ) {
				call_user_func_array( $callback, array() );
			}
		}
	}
}
