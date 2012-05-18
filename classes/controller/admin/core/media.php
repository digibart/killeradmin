<?php

/**
 * Controller for the javascript,css and images parser/minify
 *
 * @extends Controller
 * @package Killer-admin
 * @category Controller
 */
class Controller_Admin_Core_Media extends Controller {


	/**
	 * direct route to media files (js, css and images)
	 *
	 * @access public
	 * @return void
	 */
	public function action_media()
	{
		// Get the file path from the request
		$file = $this->request->param('file');

		// Find the file extension
		$ext = pathinfo($file, PATHINFO_EXTENSION);

		// Remove the extension from the filename
		$file = substr($file, 0, -(strlen($ext) + 1));

		if ($file = Kohana::find_file('media', $file, $ext))
		{
			// Send the file content as the response
			$this->response->body(file_get_contents($file));

			// Set the content type for this extension
			$this->response->headers("Cache-Control","max-age");
			$this->response->headers("Expires", gmdate('D, d M Y H:i:s \G\M\T', time() + (86400 * 7)));
			$this->response->headers("Last-Modified", gmdate('D, d M Y H:i:s \G\M\T', filemtime($file)));
			$this->response->headers("Content-type", File::mime_by_ext($ext));
			
			
		}
		else
		{
			// Return a 404 status
			$this->request->status = 404;
		}
	}

	/**
	 * route to minified css and stylesheets
	 * 
	 * @throws Exception
	 * @return void
	 */
	public function action_minify()
	{

		$file = $this->request->param('file');
		$dir = $this->request->param('dir');
		$ext = pathinfo($file, PATHINFO_EXTENSION);

		$content = Cache::instance('KillerFile')->get($file);

		if ($content === null)
		{
			try {
				$path = KillerAdmin::getModulePath() . "/media/" . $dir .  "/" . $file;
				$content['data'] = file_get_contents($path);
				$content['time'] = time() - 60;
			}
			catch (Exception $e)
			{
				Kohana::$log->add(Log::ERROR, 'Could find file :file', array(':file' => $path, ));
			}
		}

		$this->response->headers("Cache-Control","max-age");
		$this->response->headers("Expires",gmdate('D, d M Y H:i:s \G\M\T', time() + (86400 * 7)));
		$this->response->headers("Last-Modified",gmdate('D, d M Y H:i:s \G\M\T', $content['time']));
		$this->response->headers("Content-type",File::mime_by_ext($ext));

		//enable compression
		if (!ob_start("ob_gzhandler")) 
		{
			ob_start();
		}

		$this->response->body($content['data']);

	}
}

?>
