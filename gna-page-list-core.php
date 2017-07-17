<?php
	
if (!class_exists('GNA_PageList')) {
	add_action( 'plugins_loaded', array( 'GNA_PageList', 'init' ));
	class GNA_PageList {
		//protected $tmp = get_option("date_format", true);
		
		private $page_list_default_settings = null;

		public function init() {
			$class = __CLASS__;
			new $class;
		}
		
		public function __construct() {
			$this->define_constants();
			$this->define_variables();
			$this->setup_shortcodes();

			add_action('init', array(&$this, 'plugin_init'), 0);
			add_filter('plugin_row_meta', array(&$this, 'filter_plugin_meta'), 10, 2);
		}
		
		public function define_constants() {
			define('GNA_PAGE_LIST_VERSION', '1.0.1');
			
			define('GNA_PAGE_LIST_BASENAME', plugin_basename(__FILE__));
		}
		
		public function define_variables() {
				$this->page_list_default_settings = array(
				'authors'		=>	'',
				'child_of'		=>	'0',
				'date_format'	=>	get_option('date_format'),
				'depth'			=>	'0',
				'exclude'		=>	'0',
				'exclude_tree'	=>	'',
				'include'		=>	'0',
				'link_after'	=>	'',
				'link_before'	=>	'',
				'post_type'		=>	'page',
				'post_status'	=>	'publish',
				'show_date'		=>	'',
				'sort_column'	=>	'menu_order, post_title',
				'sort_order'	=>	'ASC',
				'title_li'		=>	'',
				'number'		=>	'',
				'offset'		=>	'',
				'meta_key'		=>	'',
				'meta_value'	=>	'',
				'class'			=>	''
			);
		}
		
		public function filter_plugin_meta($links, $file) {
			if( strpos( GNA_PAGE_LIST_BASENAME, str_replace('.php', '', $file) ) !== false ) { /* After other links */
				$links[] = '<a target="_blank" href="https://profiles.wordpress.org/chris_dev/" rel="external">' . __('Developer\'s Profile', 'gna-page-list') . '</a>';
			}
			
			return $links;
		}
		
		public function install() {
		}
		
		public function uninstall() {
		}
		
		public function activate_handler() {
		}
		
		public function deactivate_handler() {
		}
		
		public function setup_shortcodes() {
			add_filter('widget_text', 'do_shortcode');

			add_shortcode( 'gna_pagelist', array($this, 'pagelist_shortcode') );
			add_shortcode( 'pagelist', array($this, 'pagelist_shortcode') );
		}

		public function plugin_init() {
			load_plugin_textdomain('gna-page-list', false, dirname(plugin_basename(__FILE__ )) . '/languages/');
		}
		
		public function pagelist_shortcode($atts) {
			global $post;
			
			$return = '';
			extract(shortcode_atts($this->page_list_default_settings, $atts));

			$page_list_args = array(
				'authors'		=>	$authors,
				'child_of'		=>	$this->change_str2pageid($child_of),
				'date_format'	=>	$date_format,
				'depth'			=>	$depth,
				'exclude'		=>	$this->change_str2pageid($exclude),
				'exclude_tree'	=>	$exclude_tree,
				'include'		=>	$include,
				'link_after'	=>	$link_after,
				'link_before'	=>	$link_before,
				'post_type'		=>	$post_type,
				'post_status'	=>	$post_status,
				'show_date'		=>	$show_date,
				'sort_column'	=>	$sort_column,
				'sort_order'	=>	$sort_order,
				'title_li'		=>	$title_li,
				'number'		=>	$number,
				'offset'		=>	$offset,
				'meta_key'		=>	$meta_key,
				'meta_value'	=>	$meta_value,
				'class'			=>	$class,
				'echo'			=>	0
			);
			
			$list_pages = wp_list_pages( $page_list_args );

			if ($list_pages) {
				$return .= '<ul class="page_list '.$class.'">'."\n".$list_pages."\n".'</ul>';
			} else {
				$return .= '<!-- no pages to show -->';
			}
			
			return $return;
		}

		public function change_str2pageid( $str ) {
			global $post;
			
			$new_str_id = $str;
			$new_str_id = str_replace('this', $post->ID, $new_str_id);				// exclude this page
			$new_str_id = str_replace('current', $post->ID, $new_str_id);			// exclude current page
			$new_str_id = str_replace('parent', $post->post_parent, $new_str_id);	// exclude parent page
			
			return $new_str_id;
		}
	}
}
