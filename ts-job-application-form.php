<?php
/**
 * Plugin Name: Job Application Form Day 1
 * Plugin URI: https://wordpress.org/plugins
 * Description: Job Application Form Plugin For WordPress.
 * Version: 1.0.0
 * Author: Prajjwal Poudel
 * Author URI: http://prajjwalpoudel.com.np
 * Text Domain: ts-job-application-form
 * Domain Path: /languages/
 *
 * Copyright: © 2022 Prajjwal.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package Job_Application_Form
 */

/* It prevents public user to directly access your .php files through URL.
   If your file contains some I/O operations it can eventually be triggered (by an attacker)
   and this might cause unexpected behavior.
   */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

use JobApplicationForm\ApplicationForm;

/* Define the plugin version constant.
   It will be used throughout the plugin when needed
   */
if ( ! defined( 'TS_JOB_APPLICATION_FORM_VERSION' ) ) {
	define( 'TS_JOB_APPLICATION_FORM_VERSION', '1.0.0' );
}

// Define constant that provides full path and name of the plugin's main file.
if ( ! defined( 'TS_JOB_APPLICATION_FORM_PLUGIN_FILE' ) ) {
	define( 'TS_JOB_APPLICATION_FORM_PLUGIN_FILE', __FILE__ );
}

/**
 * Initialization of ApplicationForm instance.
 **/
function job_application_form() {
	return ApplicationForm::get_instance();
}

job_application_form();
