<?php
/*
Plugin Name: GNA Page List
Version: 1.0.1
Plugin URI: http://wordpress.org/plugins/gna-page-list/
Author: Chris Dev
Author URI: http://webgna.com/
Description: [pagelist] shortcode for any pages, posts or widgets
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: gna-page-list
*/

if(!defined('ABSPATH'))exit; //Exit if accessed directly

include_once('gna-page-list-core.php');

register_activation_hook(__FILE__, array('GNA_PageList', 'activate_handler'));		//activation hook
register_deactivation_hook(__FILE__, array('GNA_PageList', 'deactivate_handler'));	//deactivation hook
