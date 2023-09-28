<?php
/**
 * Job Application Form Table List
 *
 * @version 1.0.0
 * @package  JobApplicationForm/ListTable
 */

namespace  JobApplicationForm\Admin;

defined( 'ABSPATH' ) || exit;

if( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Appications table list class.
 */
class ListTable extends \WP_List_Table {

	/**
	 * Initialize the Appications table list.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => 'application',
				'plural'   => 'applications',
				'ajax'     => false,
			)
		);
	}

	/**
	 * Get applications columns.
	 *
	 * @return array
	 */
	public function get_columns(){
		$columns = array(
			// 'first_name' => esc_html__( 'First Name', 'ts-job-application-form' ),
			// 'last_name'  => esc_html__( 'Last Name', 'ts-job-application-form' ),

			// Day 4
			'cb'      => '<input type="checkbox" />',
			'name'     => esc_html__( 'Name', 'ts-job-application-form' ),
			'address'    => esc_html__( 'Address', 'ts-job-application-form' ),
			'email'      => esc_html__( 'Email', 'ts-job-application-form' ),
			'phone'     => esc_html__( 'Mobile', 'ts-job-application-form' ),
			'post_name'  => esc_html__( 'Post Name', 'ts-job-application-form' ),
			'cv'         => esc_html__( 'CV', 'ts-job-application-form' ),
			'date'         => esc_html__( 'Date', 'ts-job-application-form' ),
		);

		return $columns;
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-item-selection[]" value="%s" />', $item['ID']
		);
	}

	/**
	 * Prepare table list items.
	*/
	public function prepare_items() {
		$this->_column_headers = $this->get_column_info();

		/** Process bulk action */
		$this->process_bulk_action();

		$per_page     = $this->get_items_per_page( 'ts_job_applications_per_page', 5 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();
		$search = '';

		// Handle the search query.
		if ( ! empty( $_REQUEST['s'] ) ) {
			$search = sanitize_text_field( trim( wp_unslash( $_REQUEST['s'] ) ) );
		}

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );

		$this->items = self::get_applications( $search, $per_page, $current_page );
	}

	/**
	 * Counts the total applications in database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;

		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}job_application_form";

		return $wpdb->get_var( $sql );
	}

	/**
	 * Renders the columns.
	 *
	 * @param  object $application Application object.
	 * @param  string $column_name Column Name.
	 * @return string
	 */
	public function column_default( $application, $column_name ) {
		switch( $column_name ) {
			// case 'first_name':
			// case 'last_name':

			// Day 3
			case 'name':
				$delete_nonce = wp_create_nonce( 'ts-job-application-form-delete-application' );

				$title = '<strong>' . $application['first_name'] . ' ' . $application['last_name']. '</strong>';
				$actions = [
					'delete' => sprintf( '<a href="?page=%s&action=%s&application=%s&_wpnonce=%s">'. esc_html__( "Delete", "ts-job-application-form" ) . '</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $application['ID'] ), $delete_nonce )
				];
				return $title . $this->row_actions( $actions );
				break;

			case 'address':
			case 'email':
			case 'phone':
			case 'post_name':
				return  $application[ $column_name ];
				break;
			case 'cv':
				return '<a href="' . esc_url_raw( $application[ 'cv' ] ) . '" target="_blank" >' .  basename( ( $application[ 'cv' ] ) ) . '</a>';
				break;
			case 'date':
				return $application[ 'submitted_at' ];
				break;
			default:
			return print_r( $application, true ) ; //Show the whole array for troubleshooting purposes
		}
	}


	/**
	 * Get a list of sortable columns.
	 *
	 * @return array
	 */
	protected function get_sortable_columns() {
		$sortable_columns = array(
			'date' => array( 'submitted_at', false ),
		);

		return $sortable_columns;
	}


	/**
	 * Get bulk actions.
	 *
	 * @return array
	 */

	protected function get_bulk_actions() {
		$actions = array(
			'bulk-delete'    => esc_html__('Delete', 'ts-job-application-form' )
		);
		return $actions;
	}

	/**
	 * Render the list table page, including header, notices, status filters and table.
	 */
	public function display_page() {
		$this->prepare_items();
		?>
			<div class="wrap">
				<h1 class="wp-heading-inline"><?php esc_html_e( 'Job Applications' ); ?></h1>
				<hr class="wp-header-end">
				<form id="job-application-list" method="get">
					<input type="hidden" name="page" value="job-application-form" />
					<?php
						$this->views();
						$this->search_box( esc_html__( 'Search Applications', 'ts-job-application-form' ), 'application' );
						$this->display();

						wp_nonce_field( 'save', 'ts_job_application_list_nonce' );
					?>
				</form>
			</div>

		<?php
	}

	/**
	 * Retrieve applications data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_applications( $search, $per_page = 5, $page_number = 1 ) {

		global $wpdb;

		$sql = "SELECT * FROM {$wpdb->prefix}job_application_form";

		if ( '' !== $search ) {
			$sql .= $wpdb->prepare( " WHERE ( first_name LIKE %s ) OR  ( last_name LIKE %s ) OR  ( email LIKE %s ) OR  ( post_name LIKE %s )", "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%");
		}

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}

		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( (int) $page_number - 1 ) * $per_page;

		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $result;
	}

	/**
	 * Delete a application record.
	 *
	 * @param int $id application ID
	 */
	public static function delete_application( $id ) {
		global $wpdb;

		$wpdb->delete(
			"{$wpdb->prefix}job_application_form",
			[ 'ID' => $id ],
			[ '%d' ]
		);
	}

	/**
	 * Process Bulk Action.
	 */
	public function process_bulk_action() {

		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {

			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			if ( ! wp_verify_nonce( $nonce, 'ts-job-application-form-delete-application' ) ) {
				die( esc_html__( 'Nonce error please reload', 'ts-job-application-form' ) );
			}
			else {
				self::delete_application( absint( $_GET['application'] ) );
			}

		}

		// If the delete bulk action is triggered
		$action = $this->current_action();
		if ( $action == 'bulk-delete' ) {
			$delete_ids = esc_sql( $_GET['bulk-item-selection'] );

			// loop over the array of record IDs and delete them
			foreach ( $delete_ids as $id ) {
				self::delete_application( $id );
			}
		}
	}

}
