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
				'ts_job_application_form_settings_page',
			), '', 56
		);

		add_action( 'load-' . $template_page, array( $this, 'template_page_init' ) );

	}

	/**
	 * Loads screen options into memory.
	 */
	public function template_page_init() {
		// Table display code here.
	}

	/**
	 *  Init the Job Application Form Settings page.
	 */
	public function ts_job_application_form_settings_page() {
		ob_start();
		echo '<h1>Job Application Form Settings</h1>';
		echo ob_get_clean();
	}
}
