<?php
/**
 * Plugin Name: Hide/Remove Metadata
 * Description: Hide/Remove Metadata is a free, simple yet extremely handy WordPress plugin that helps you hide author and published date either by CSS or PHP from your website effortlessly.
 * Plugin URI: http://catchplugins.com/plugins/hide-metadata
 * Author: Catch Plugins
 * Author URI: http://catchplugins.com/
 * Version: 2.0
 * License: GPL2
 * Text Domain: hide-metadata
 * Domain Path: /languages
 */

/*
	Copyright (C) Year  Author  Email

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

// Define Version
if ( ! defined( 'HIDE_METADATA_VERSION' ) ) {
	define( 'HIDE_METADATA_VERSION', '2.0' );
}

if ( ! defined( 'HIDE_METADATA_BASENAME' ) ) {
	define('HIDE_METADATA_BASENAME', plugin_basename(__FILE__));
}

class Hide_Metadata
{
	private $plugin_name;
	private $version;
	private $options;

	function __construct()
	{
		$this->plugin_name = 'hide-metadata';
		$this->version     = HIDE_METADATA_VERSION;
		$this->options     = hide_metadata_get_options();

		add_filter('body_class', array($this, 'hide_metadata_body_class'));
		add_action('admin_init', array($this, 'register_settings'));
		add_action('admin_menu', array($this, 'add_plugin_settings_menu'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));
		add_action('wp_enqueue_scripts', array($this, 'plugin_styles'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));

		add_filter('plugin_row_meta', array($this, 'add_plugin_meta_links'), 10, 2);

		add_filter('plugin_action_links', array($this, 'action_links'), 10, 2);

		if ('php' === $this->options['hide_by']) {
			add_action('loop_start', array($this, 'remove_php'));
			add_action('init', array($this, 'remove_php'));
		}
	}


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	function enqueue_styles()
	{
		if (isset($_GET['page']) && 'hide-metadata' === $_GET['page']) {
			wp_enqueue_style($this->plugin_name . '-display-dashboard', plugin_dir_url(__FILE__) . 'css/admin-dashboard.css', array(), $this->version, 'all');
		}
	}


	function plugin_styles()
	{
		wp_enqueue_style($this->plugin_name . '-style', plugin_dir_url(__FILE__) . 'css/style.css', array(), $this->version, 'all');
	}


	/**
	 * Register the scripts for the admin area.
	 *
	 * @since    1.0.0
	 */
	function enqueue_scripts()
	{
		if (isset($_GET['page']) && 'hide-metadata' === $_GET['page']) {
			wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/hide-metadata-admin.js', array('jquery', 'matchHeight', 'jquery-ui-tooltip'), $this->version, false);

			wp_enqueue_script('matchHeight', plugin_dir_url(__FILE__) . 'js/jquery-matchHeight.min.js', array('jquery'), $this->version, false);
		}
	}

	/**
	 * Hide/Remove MetaData: action_links
	 * Hide/Remove MetaData Settings Link function callback
	 *
	 * @param arrray $links Link url.
	 *
	 * @param arrray $file File name.
	 */
	function action_links($links, $file)
	{
		if ($file === $this->plugin_name . '/' . $this->plugin_name . '.php') {
			$settings_link = '<a href="' . esc_url(admin_url('admin.php?page=hide-metadata')) . '">' . esc_html__('Settings', 'hide-metadata') . '</a>';

			array_unshift($links, $settings_link);
		}
		return $links;
	}

	/**
	 * Add settings menu
	 */
	function add_plugin_settings_menu()
	{
		// Add menu under tools
		add_submenu_page(
			'tools.php',
			esc_html__('Hide/Remove Metadata', 'hide-metadata'),
			esc_html__('Hide/Remove Metadata', 'hide-metadata'),
			'manage_options',
			'hide-metadata',
			array($this, 'settings_page')
		);
	}

	/**
	 * Hide/Remove MetaData: settings_page
	 * Hide/Remove MetaData Setting function
	 */
	function settings_page()
	{
		if (! current_user_can('manage_options')) {
			wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'hide-metadata'));
		}

		require plugin_dir_path(__FILE__) . 'partials/admin-display.php';
	}

	function register_settings()
	{
		register_setting(
			'hide-metadata-group',
			'hide_metadata_options',
			array($this, 'sanitize_callback')
		);
	}

	public function sanitize_callback($input)
	{

		if (isset($input['reset']) && $input['reset']) {
			// If reset, restore defaults
			return hide_metadata_default_options();
		}
		$message = null;
		$type    = null;

		// Verify the nonce before proceeding.
		if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			|| (! isset($_POST['hide_metadata_nonce'])
				|| ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['hide_metadata_nonce'])), HIDE_METADATA_BASENAME))
			|| (! check_admin_referer(HIDE_METADATA_BASENAME, 'hide_metadata_nonce'))
		) {

			return esc_html__('Invalid Nonce', 'hide-metadata');
		} else {
			if (null !== $input) {

				$input['hide_by']     = sanitize_key($input['hide_by']);
				$input['hide_author'] = (isset($input['hide_author']) && '1' === $input['hide_author']) ? '1' : '0';
				$input['hide_date']   = (isset($input['hide_date']) && '1' === $input['hide_date']) ? '1' : '0';
			}
			return $input;
		}
	}


	// Removal using css

	public function hide_metadata_body_class($classes)
	{

		$options = hide_metadata_get_options();

		if ('1' === $options['hide_author'] && 'css' === $options['hide_by']) {
			$classes[] = 'hide-meta-author';
		}
		if ('1' === $options['hide_date'] && 'css' === $options['hide_by']) {
			$classes[] = 'hide-meta-date';
		}
		if ('1' === $options['hide_date'] && 'php' === $options['hide_by']) {
			$classes[] = 'hide-meta-date-php';
		}
		if ('1' === $options['hide_author'] && 'php' === $options['hide_by']) {
			$classes[] = 'hide-meta-author-php';
		}

		return $classes;
	}



	function remove_php()
	{
		$options = hide_metadata_get_options();

		if ('1' === $options['hide_author']) {
			add_filter('the_author', '__return_false');
			add_filter('get_the_author', '__return_false');
			add_filter('author_link', '__return_false');
		}

		if ('1' === $options['hide_date']) {
			add_filter('the_date', '__return_false');
			add_filter('the_time', '__return_false');
			add_filter('the_modified_date', '__return_false');
			add_filter('get_the_date', '__return_false');
			add_filter('get_the_title', '__return_false');
			add_filter('get_the_time', '__return_false');
			add_filter('get_the_modified_date', '__return_false');
		}
	}


	function add_plugin_meta_links($meta_fields, $file)
	{

		if (plugin_basename(__FILE__) === $file) {

			$meta_fields[] = "<a href='https://catchplugins.com/support-forum/forum/hide-metadata/' target='_blank'>Support Forum</a>";
			$meta_fields[] = "<a href='https://wordpress.org/support/plugin/hide-metadata/reviews#new-post' target='_blank' title='Rate'>
			        <i class='ct-rate-stars'>"
				. "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
				. "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
				. "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
				. "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
				. "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
				. '</i></a>';

			$stars_color = '#ffb900';

			echo '<style>'
				. '.ct-rate-stars{display:inline-block;color:' . esc_html($stars_color) . ';position:relative;top:3px;}'
				. '.ct-rate-stars svg{fill:' . esc_html($stars_color) . ';}'
				. '.ct-rate-stars svg:hover{fill:' . esc_html($stars_color) . '}'
				. '.ct-rate-stars svg:hover ~ svg{fill:none;}'
				. '</style>';
		}

		return $meta_fields;
	}
}

new Hide_Metadata();


function hide_metadata_default_options($option = null)
{
	$default_options = array(
		'hide_by'     => 'css',
		'hide_author' => '0',
		'hide_date'   => '0',
	);

	if (null === $option) {
		return apply_filters('hide_metadata_options', $default_options);
	} else {
		return $default_options[$option];
	}
}

function hide_metadata_get_options()
{
	$defaults = hide_metadata_default_options();
	$options  = get_option('hide_metadata_options', $defaults);

	return wp_parse_args($options, $defaults);
}
