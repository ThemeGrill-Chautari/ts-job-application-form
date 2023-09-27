<?php
/**
 *  Shortcodes.
 *
 * @class    Shortcodes
 * @version  1.0.0
 * @package  JobApplicationForm/Classes
 */

namespace  JobApplicationForm;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shortcodes Class
 */
class Shortcodes {

	/**
	 * Init Shortcodes.
	 */
	public function __construct() {
		add_shortcode( "job_application_form", array( $this, 'job_application_form' ) );
	}

	/**
	 * Application Form shortcode.
	 *
	 * @param mixed $atts Attributes.
	 */
	public function job_application_form( $atts ) {

		ob_start();
		self::render_application_form();
		return ob_get_clean();
	}

	/**
	 * Output for Application Form.
	 *
	 * @since 1.0.0
	 */
	public static function render_application_form() {
		// Day 2
		/**
		 * Enqueue the frontend form style.
		 */
		wp_enqueue_style( "ts-job-application-form-style", TS_JOB_APPLICATION_FORM_ASSETS_URL . '/css/ts-job-application-form.css', array(), TS_JOB_APPLICATION_FORM_VERSION );

		if ( is_user_logged_in() ) {
			include TS_JOB_APPLICATION_FORM_TEMPLATE_PATH . '/ts-job-application-form-page.php';
		}
	}

}
