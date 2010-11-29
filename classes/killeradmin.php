<?php

/**
 * Killeradmin helper
 *
 * @package Killer-admin
 * @category Helper
 */
class Killeradmin
{
	/**
	 * create links to sort a column. Set $reverse to true to set asc as desc and vice versa.
	 *
	 * @access public
	 * @static
	 * @param string  $col
	 * @param bool    $reverse. (default: false)
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

	/**
	 * create a form field for filtering
	 *
	 * @access public
	 * @static
	 * @param string  $column
	 * @param array  $filtervals
	 * @return string
	 */
	public static function filterField($column, $filtervals)
	{
		return Form::input("filter[$column]", Arr::get($filtervals, $column), array('style' => 'width:100%'));
	}

	/**
	 * create a button for filtering
	 *
	 * @access public
	 * @static
	 * @param string  $title. (default: null)
	 * @return string
	 */
	public static function filterButton($title = null)
	{
		$title = ($title) ? $title : __('clear filter');
		$button = Form::button('submitfilter', html::image('admin/media/images/icons/funnel.png') . "&nbsp;" . __('filter') , array('type' => 'submit'));
		$link = (Arr::get($_GET, 'filter')) ? html::anchor(Request::instance()->uri , __('clear filter')) : "";

		return $button . $link;
	}

	/**
	 * create a 'new x button'
	 *
	 * @access public
	 * @static
	 * @param string  $name
	 * @param string  $title. (default: null)
	 * @param string  $url.   (default: null)
	 * @return void
	 */
	public static function newButton($name, $title = null, $url = null)
	{
		$url = ($url) ? $url : Request::instance()->uri . "/add";
		$title = ($title) ? $title : html::image('admin/media/images/icons/add.png') . __('add :object', array(':object' => __($name)));

		return Html::anchor($url, $title, array('class' => 'button positive'));

	}
}

?>
