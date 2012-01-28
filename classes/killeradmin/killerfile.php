<?php

/**
 * A Helper to minify and combine js and css
 * Depends on Kohana Cache module
 *
 * @package Killer-admin
 * @category Helper
 */

require_once Kohana::find_file('vendor', 'jsmin', 'php');
require_once Kohana::find_file('vendor', 'cssmin', 'php');
require_once Kohana::find_file('vendor', 'lessc', 'php');

class Killeradmin_KillerFile
{
	static $instance;

	protected $_files = array();
	protected $_filename;
	protected $_type = null;
	protected $_lessc;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @param string  $type js|css
	 * @return void
	 */
	function __construct($type)
	{
		$this->_type = $type;
		$this->_lessc = new lessc();

		// check if cache module exists
		if (!class_exists("Cache", true))
		{
			throw new Kohana_Exception('class "Cache" not found. Be sure to enable cache module in your bootstrap!');
		}
	}

	/**
	 * creates and returns new instance
	 *
	 * @access public
	 * @static
	 * @param string  $group. (default: null) name of the group is. css_screen, css_print, etc
	 * @param string  $type.  js|css
	 * @return void
	 */
	public static function instance($type, $group = null)
	{
		if (!$group)
		{
			$group = $type;
		}

		if (isset(KillerFile::$instance[$group]))
		{
			return KillerFile::$instance[$group];
		}
		else
		{
			KillerFile::$instance[$group] = new KillerFile($type);
			return KillerFile::$instance[$group];
		}
	}


	/**
	 * add files to combine/ minify. Can be eather a string containing one file, or an array
	 *
	 * @access public
	 * @param string|array $files
	 * @return instance
	 */
	public function add_files($files)
	{
		if (is_array($files))
		{
			$this->_files = array_merge($this->_files, $files);
		}
		else
		{
			$this->_files[] = $files;
		}

		return $this;
	}

	/**
	 * returns the tag to include in your html
	 *
	 * @access public
	 * @param array   $attr. (default: array())
	 * @return string
	 */
	public function get_tag($attr = array())
	{
		// if we're in development-mode, don't minify/ combine
		// but we still need to parse the files to replace %route% keys..
		if (Kohana::$environment >= KOHANA::TESTING)
		{
			$html = "";
			foreach ($this->_files as $file)
			{
				$content = $this->_get_file_contents($file);
				$pathinfo = pathinfo($file);

				//if its a less file, compile it
				if ($pathinfo['extension'] == "less")
				{
					$content = $this->_lessc->parse($content);
					$pathinfo['extension'] = "css";
				}

				//generate a uniq id
				$id = str_replace(".", "-", $pathinfo['filename']) . '_' . substr(md5($file), 0, 6) . "." . $pathinfo['extension'];

				//save cache so the browser can link to it
				Cache::instance('KillerFile')->set($id, array('data' => $content, 'time' => time()), 5);

				// generate the html tags
				if ($this->_type == "js")
				{
					$html .= html::script(Route::get('admin/mini')->uri(array('dir' => 'js', 'file' => $id)), $attr);
				}
				elseif ($this->_type == "css")
				{
					$html .= html::style(Route::get('admin/mini')->uri(array('dir' => 'css', 'file' => $id)), $attr);
				}
			}
			return $html;
		}
		else
		{
			// if not in development, we can minify and combine the files
			$this->minify();

			if ($this->_type == 'js')
			{
				return html::script(Route::get('admin/mini')->uri(array('dir' => 'js', 'file' => $this->_filename)), $attr);
			}
			elseif ($this->_type == 'css')
			{
				return html::style(Route::get('admin/mini')->uri(array('dir' => 'css', 'file' => $this->_filename)), $attr);
			}
		}
	}


	/**
	 * minify and combine the js and css files
	 *
	 * @access protected
	 * @return string
	 */
	public function minify()
	{
		$this->_filename = $this->_generate_filename();

		$output = Cache::instance('KillerFile')->get($this->_filename);

		// if parsed file does not exist in cache, lets create it now!
		if (!$output)
		{
			$output = "";
			foreach ($this->_files as $file)
			{
				$pathinfo = pathinfo($file);

				$output .= "/* " . $pathinfo['basename'] . " */\n";

				if ($pathinfo['extension'] == 'js')
				{
					$output .= ltrim(JSMin::minify($this->_get_file_contents($file))) . "\n";
				}
				elseif ($pathinfo['extension'] == 'css')
				{
					$output .=  ltrim(CSSMin::minify($this->_get_file_contents($file))) . "\n";
				}
				elseif ($pathinfo['extension'] == 'less')
				{
					// it's a less-file, so we need to compile it to css first
					$compiled = $this->_compile_lessc($file);
					$output .= ltrim(CSSMin::minify($compiled)). "\n";
				}
			}
			Cache::instance('KillerFile')->set($this->_filename, array('data' => trim($output), 'time' => time()));
		}
		return $output;
	}

	/**
	 * try to get the content of a file.
	 * it looks in different locations;
	 * - absolute path
	 * - APPPATH
	 * - MODPATH
	 * - external url
	 * - local url
	 *
	 * @access protected
	 * @param string  $file
	 * @return string the file content
	 */
	protected function _get_file_contents($file)
	{
		// is the path absolute?
		if (file_exists($file))
		{

			$contents = file_get_contents($file);
		}
		// or could I find it in the module folder?
		elseif (file_exists(MODPATH . $file))
		{
			$contents = file_get_contents(MODPATH .$file);
		}
		// or application folder?
		elseif (file_exists(APPPATH . $file))
		{
			$contents = file_get_contents(APPPATH .$file);
		}
		// is it a valid url aka url to a external file?
		elseif (Valid::URL($file))
		{
			$contents = file_get_contents($file);
		}
		// or is it a url to local file?
		elseif (Valid::URL(URL::site($file, true)))
		{
			$contents = file_get_contents(URL::site($file, true));
		}
		// I'm sorry, couldn't find the file
		else
		{
			throw new Kohana_Exception(":file not found", array(':file' => $file));
		}

		// replace all %route_key% to correct uri
		$contents = str_ireplace("%admin/media%", Route::url('admin/media'), $contents);
		
		return $contents;
	}

	protected function _compile_lessc($file)
	{
		try
		{
			$compiled = $this->_lessc->parse($this->_get_file_contents($file));
		}
		catch (exception $e)
		{
			throw new Kohana_Exception("could not parse :file > :error", array(
					':file' => $file,
					':error' => $e->getMessage()
				));
		}
		return $compiled;
	}

	/**
	 * generate a nice uniq filename based on current added files
	 *
	 * @access protected
	 * @return void
	 */
	protected function _generate_filename()
	{
		return substr(md5(implode(",", $this->_files)), 0, 6) . '.'. $this->_type;
	}

}


?>
