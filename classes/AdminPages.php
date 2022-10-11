<?php

namespace Nhrdev\CountdownTimer;

class AdminPages
{

	public static $editAction = "edit_timer";
	public static $deleteAction = "delete_timer";

	public static function countdownTimers()
	{

		$action = isset($_GET['action']) ? $_GET['action'] : '';

		if ($action === self::$editAction) {
			require_once plugin_dir_path(dirname(__FILE__)) . 'views/edit.php';
		} else {
			require_once plugin_dir_path(dirname(__FILE__)) . 'views/countdown-timers.php';
		}
	}


	// add new timer page callback
	public static function addNewTimer()
	{
		require_once plugin_dir_path(dirname(__FILE__)) . 'views/add-new.php';
	}


	// shortcode page callback
	public static function shortCode()
	{
		require_once plugin_dir_path(dirname(__FILE__)) . 'views/short-code.php';
	}




	// add all the menu pages
	public static function addAdminPages()
	{

		// adding admin menu page
		add_menu_page(
			'Countdown Timers',
			'Countdown Timers',
			'manage_options',
			'nhr_cd_timer_dashboard',
			[self::class, 'countdownTimers'],
			'',
			10
		);

		// adding submenu page for settings
		add_submenu_page(
			'nhr_cd_timer_dashboard',
			'Add New',
			'Add New',
			'manage_options',
			'nhr_cd_timer_new',
			[self::class, 'addNewTimer']
		);

		// adding submenu page for settings
		add_submenu_page(
			'nhr_cd_timer_dashboard',
			'Shortcode',
			'Shortcode',
			'manage_options',
			'nhr_cd_timer_shortcode',
			[self::class, 'shortCode']
		);
	}


	// to initialize the admin pages
	public static function init()
	{
		add_action('admin_menu', [self::class, 'addAdminPages']);
	}
}
