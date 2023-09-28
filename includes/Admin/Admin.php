<?php
/**
 * JobApplicationForm Admin.
 *
 * @class    Admin
 * @version  1.0.0
 * @package  JobApplicationForm/Admin
 */

namespace JobApplicationForm\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Class
 */
class Admin {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {

		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_hooks() {
		add_action( 'admin_menu', array( $this, 'job_application_form_menu' ), 68 );
	}

	/**
	 * Add  menu item.
	 */
	public function job_application_form_menu() {
		$template_page = add_menu_page(
			__( 'Job Applications', 'ts-job-application-form' ),
			__( 'Job Applications', 'ts-job-application-form' ),
			'manage_options',
			'job-application-form',
			array(
				$this,
				'ts_job_application_form_list_page',
			), '', 56
		);

		add_action( 'load-' . $template_page, array( $this, 'template_page_init' ) );

	}

	/**
	 * Loads screen options into memory.
	 */
	public function template_page_init() {
		// Table display code here.

		// Day 2
		global $ts_job_application_table_list;

		$ts_job_application_table_list = new ListTable();
		$ts_job_application_table_list->process_actions();

		// Add screen option.
		add_screen_option(
			'per_page',
			array(
				'default' => 20,
				'option'  => 'ts_job_applications_per_page',
			)
		);
	}

	/**
	 *  Init the Job Application Form List page.
	 */
	public function ts_job_application_form_list_page() {
		// ob_start();
		// echo '<h1>Job Application Form Settings</h1>';
		// echo ob_get_clean();

		// Day 2
		global $ts_job_application_table_list;
		$ts_job_application_table_list->display_page();
	}
}
