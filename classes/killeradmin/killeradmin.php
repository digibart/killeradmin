<?php

/**
 * Killeradmin helper
 *
 * @package Killer-admin
 * @category Helper
 */
class Killeradmin_Killeradmin
{
	// version and codename
	const VERSION = "1.2.1";
	const CODENAME = "Troljegeren";


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

		foreach ($orders as $order)
		{
			$class = "";
			$anchor_string = ($order == 'asc') ? self::SpriteImg('arrow up') : self::SpriteImg('arrow down') ;

			if ($reverse)
			{
				$order = ($order == 'asc') ? 'desc' : 'asc';
			}

			if (Arr::get($_GET, 'sort') == $col && Arr::get($_GET, 'order') == $order)
			{
				$class = "active";
			}

			$query = URL::query(array('sort' => $col, 'order' => $order));


			$string .= html::anchor(Request::current()->uri() .  $query, $anchor_string, array('class' => $class . ' sort'));
		}

		return $string;
	}

	/**
	 * create a form field for filtering
	 *
	 * @access public
	 * @static
	 * @param string  $column
	 * @param array   $filtervals
	 * @return string
	 */
	public static function filterField($column, $filtervals)
	{
		$filtervals = array_merge(array('placeholder' => __($column)), $filtervals);
		return Form::input("filter[$column]", Arr::get($filtervals, $column), $filtervals);
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
		$button = Form::button('submitfilter', self::spriteImg('funnel') . "&nbsp;" . __('filter') , array('type' => 'submit'));
		$link = (Arr::get($_GET, 'filter')) ? html::anchor(Request::current()->uri() , str_replace(' ', '&nbsp;',__('clear filter'))) : "";

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
		$request = Request::current();
		$url = ($url) ? $url : $request->route()->uri(array('controller' => $request->controller(), 'action' => 'add'));
		$title = ($title) ? $title : self::spriteImg('plus') . __('add :object', array(':object' => __($name)));

		return Html::anchor($url, $title, array('class' => 'nice primary button'));

	}

	/**
	 * unset keys from $input when not in $editable
	 * very usefull to prevent unwanted changes on a object
	 *
	 * @access public
	 * @static
	 * @param array   $input
	 * @param array   $editable
	 * @return array
	 */
	public static function editableVals($input, $editable)
	{
		foreach ($input as $key => $value)
		{
			if (!in_array($key, $editable))
			{
				unset($input[$key]);
			}
		}
		return $input;
	}

	/**
	 * create a image tag for sprite images
	 *
	 * @access public
	 * @static
	 * @param mixed   $name
	 * @param mixed   $title.       (default: null)
	 * @param mixed   $extra_class. (default: null)
	 * @return void
	 */
	public static function spriteImg($class, $title = null)
	{
		$attr = array();
		$attr['width'] = 16;
		$attr['height'] = 16;
		$attr['class'] = 'icon ' . $class;

		if ($title)
		{
			$attr['title'] = $title;
		}

		return html::image(Route::get('admin/media')->uri(array('file' => 'images/spacer.gif')), $attr);
	}


	/**
	 * sending emails
	 *
	 * @access public
	 * @static
	 * @param array|string $from
	 * @param array|string $to
	 * @param string  $subject
	 * @param strubg  $body
	 * @param string  $textbody. (default: null)
	 * @return boolean
	 */
	public static function email($from, $to, $subject, $body, $textbody = null)
	{
		require_once Kohana::find_file('vendor/swift', 'swift_required', 'php');

		if (!$textbody)
		{
			$textbody = strip_tags($body);
		}

		$message = Swift_Message::newInstance();
		$message->setSubject($subject);
		$message->setFrom($from);
		$message->setTo($to);

		$message->setBody($textbody);
		$message->addPart($body, 'text/html');

		// create transporter
		$options = Kohana::$config->load('email.options');

		if (Kohana::$config->load('email.driver') == 'native')
		{
			$transport = Swift_MailTransport::newInstance();
		}
		else
		{
			$transport = Swift_SmtpTransport::newInstance(Arr::get($options, 'hostname'), Arr::get($options, 'port'), Arr::get($options, 'security'))
			->setUsername(Arr::get($options, 'username'))
			->setPassword(Arr::get($options, 'password'));
		}

		// create mailer
		$mailer = Swift_Mailer::newInstance($transport);

		try {
			$mailer->send($message);
			Kohana::$log->add(Log::DEBUG, "Email send to " . Debug::vars($to));
			return true;
		}
		catch (Exception $e)
		{
			Kohana::$log->add(Log::ERROR, "Error sending mail to " . $to . ": " . $e->getMessage());
			return false;
		}
	}

	/**
	 * get the file path of this module
	 * 
	 * @access public
	 * @static
	 * @return string
	 */
	public static function getModulePath()
	{
		$dirs = explode("/" , dirname(__FILE__));
		array_splice($dirs, -2);


		return implode("/", $dirs);
	}
}
?>
