<?php

/**
 * Killeradmin helper
 * 
 * @package Killer-admin
 * @category Helper
 */
class Killeradmin
{
	// version and codename
	const VERSION = "1.0.0";
	const CODENAME = "Mustard Seed";
	
	/**
	 * create links to sort a column. Set $reverse to true to set asc as desc and vice versa.
	 * 
	 * @access public
	 * @static
	 * @param string $col
	 * @param bool $reverse. (default: false)
	 * @return void
	 */
	public static function sortAnchor($col, $reverse = false)
	{
		$string = "";
		$orders = array('asc', 'desc');

		foreach ($orders as $order) {
			$class = "";
			$anchor_string = ($order == 'asc') ? "&and;" : "&or;";

			if ($reverse) {
				$order = ($order == 'asc') ? 'desc' : 'asc';
			}

			if (Arr::get($_GET, 'sort') == $col && Arr::get($_GET, 'order') == $order) {
				$class = "active";
			}

			$query = URL::query(array('sort' => $col, 'order' => $order));


			$string .= html::anchor(Request::instance()->uri .  $query, $anchor_string, array('class' => $class . ' sort'));
		}

		return $string;
	}
}

?>
