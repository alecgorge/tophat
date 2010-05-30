<?php

/**
 * This class provides all the methods to create new actions in the sidebar.
 */
class AdminSidebar {
	/**
	 * @var array An array of all of the sidebar blocks registered.
	 */
	private static $registered;

	/**
	 * This adds a callback to when the admin sidebar for each page is generated.
	 *
	 * @param callback $callback The function/method to be called. Should return a string to be added to the sidebar blocks.
	 * @param integer $importance The level of importance. Lower numbers are executed first.
	 */
	public static function register ($callback, $importance = 0) {
		plugin('admin_sidebar_registered', array($callback, $importance));
		if(!is_array(self::$registered[$importance])) {
			self::$registered[$importance] = array();
		}

		self::$registered[$importance][] = $callback;
	}

	/**
	 * Similar to AdminSidebar::register, but only registers for a certain page. Useful for plugins.
	 *
	 * @param string $page Only run callback if $_GET['page'] matches this.
	 * @param callback $callback See AdminSidebar::register
	 * @param integer $importance See AdminSidebar::register
	 */
	public static function registerForPage ($page, $callback, $importance = 0) {
		if(Admin::isPage($page)) {
			self::register($callback, $importance);
		}
	}

	/**
	 * Get the HTML of the admin's sidebar.
	 *
	 * @return string The HTML of the admin's sidebar.
	 */
	public static function get () {
		ksort(self::$registered);
       	foreach(self::$registered as $arr2) {
			foreach($arr2 as $callback) {
				$r .= "\n\t\t<li class='admin-sidebar-block'>".filter('admin_sidebar_block_output', call_user_func($callback))."</li>";
			}
		}
		return filter('admin_sidebar_output', "<ul id='admin-sidebar'>".$r."\n</ul>");
	}

	/**
	 * Like AdminSidebar::get except it echos instead of returning.
	 */
	public static function display () {
		$r = self::get();
		echo $r;
	}
}

?>
