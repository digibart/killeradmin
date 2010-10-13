<?php

/**
 * Controller_Admin_Core_Base class.
 * 
 * @extends Controller_Template
 * @package Pbadmin
 * @category Controller
 */
class Controller_Admin_Core_Base extends Controller_Template {
	public $template = "admin/template";
	private $controller_url;
	private $session;
	public $request;

	public function __construct($id = null)
	{
		parent::__construct($id);
		
		$this->request = Request::instance();		
		$this->session= Session::instance();
	}

	/**
	 * checks if a user is allowed to open the requested page
	 * 
	 * @access public
	 * @return void
	 */
	public function before()
	{
		parent::before();

		//Check user auth and role
		$action_name = Request::instance()->action;

		if (($this->auth_required == true && Auth::instance()->logged_in() === false)
			|| (is_array($this->secure_actions) && array_key_exists($action_name, $this->secure_actions) &&
				Auth::instance()->logged_in($this->secure_actions[$action_name]) == false)) {
			if (Auth::instance()->logged_in()) {
				Message::instance()->error(ucfirst(__('access denied')));
				Request::instance()->redirect('admin/main/');
			} else {
				Message::instance()->error(ucfirst(__('access denied')));
				Request::instance()->redirect('admin/main/login');
			}
		} else {
			$this->user = Auth::instance()->get_user();
		}

		$this->template->title = "Admin";
		$this->template->content = "";
		$this->template->scripts = array();
		
		$this->controller_url = Request::instance()->directory . "/" . Request::instance()->controller;
		
		if ( Auth::instance()->logged_in()) {
			$this->template->menu = View::factory('admin/menubar')->set("items", Kohana::config('admin.menu'));
		} else {
		 	$this->template->menu = "";
		 }
	}
	
	
	/**
	 * creates the list-view of a object
	 * 
	 * @access public
	 * @return void
	 */
	public function action_index()
	{
		$get = $_GET;
		$page =  (isset($get['page'])) ? (int) $get['page']: 1;
		$offset = (($page > 0 ) ? $page - 1 : 0) * 20;
		
		//converts $_GET['filter'] in an array
		$filter = array();
		if (isset($get['filter'])) {
			foreach ($get['filter'] as $field => $value) {
				if ($value) {
					$filter[$field] = $value;
				}
			}
		}

		$this->session->set('requested_url', $this->request->uri.'?'.http_build_query($get, '&'));
		$this->session->delete('post_data_' . $this->orm_name);

		//count total objects
		$count = ORM::factory($this->orm_name);
		foreach ($filter as $field => $value) {
			$count->and_where($field, 'like', '%' . $value . '%');
		}
		$count = $count->count_all();
		
		
		//display message if no objects found
		if ($count == 0) {
			$msg = strtolower(__('no :objects found',  array(':objects' => __($this->orm_name))) . ".");
			
			//display link 'clear filter'
			if (isset($get['filter'])) {			
				$msg .= "&nbsp;" . html::anchor(Request::instance()->directory .'/' . Request::instance()->controller, __('clear filter'));
			}
			Message::instance()->info($msg);
		}
		
		
		//pagination
		$pagination = new Pagination;
		$page_config = $pagination->config_group('admin');
		$page_config['total_items'] = $count;
		$pagination->setup($page_config);

		//pagination, check if a page exists
		if (!$pagination->valid_page($page) && $pagination->__get('total_pages') > 0) {
			$get['page'] = $pagination->__get('total_pages');
			Request::instance()->redirect($this->request->uri.'?'.http_build_query($get, '&'));
		}
		
		
		//collect the orm objects
		$objects = ORM::factory($this->orm_name)
			->offset($offset)
			->limit(20);
		foreach ($filter as $field => $value) {
			$objects->and_where($field, 'like', '%' . $value . '%');
		}
		$objects = $objects->find_all();


		$view = View::factory('admin/'.$this->orm_name.'_list')
		->bind('objects', $objects)
		->bind('auth_user', $this->user)		
		->bind('filter', $filter)
		->bind('count', $count)
		->bind('controller_url', $this->controller_url)
		->bind('pagination', $pagination);


		$this->template->content = $view;

	}	
	

	
	/**
	 * create the objects-form new page
	 * 
	 * @access public
	 * @return void
	 */
	public function action_add()
	{
		$this->template->title = ucfirst(__('add :object', array(':object' => __($this->orm_name))));

		$object = ORM::factory($this->orm_name);
		$object->values($this->session->get_once('post_data_' . $this->orm_name, array()));
		
		$this->template->content = View::factory('admin/'.$this->orm_name.'_form')
		->set('referrer', $this->session->get('requested_url'))
		->set('controller_url', $this->controller_url)
		->set('auth_user', $this->user)
		->bind($this->orm_name, $object);

	}
	
	/**
	 * creates the objects-form edit page
	 * 
	 * @access public
	 * @param mixed $id. (default: null)
	 * @return void
	 */
	public function action_edit($id = null)
	{
		$this->template->title = ucfirst(__('edit :object', array(':object' => __($this->orm_name))));

		$object = ORM::factory($this->orm_name)->find((int) $id);

		$this->template->content = View::factory('admin/'.$this->orm_name.'_form')		
		->set('referrer', $this->session->get('requested_url'))
		->set('controller_url', $this->controller_url)		
		->set('auth_user', $this->user)
		->bind($this->orm_name, $object);

		if (!$object->loaded()) {
			Message::instance()->error(__(':object not found', array(':object' => __($this->orm_name))));
			$this->request->redirect($this->session->get_once('requested_url'));
		}
	}
	
	/**
	 * validate and save the input
	 * 
	 * @access public
	 * @param mixed $id. (default: null)
	 * @return void
	 */
	public function action_save($id = null)
	{

		$this->auto_render = false;

		$post = $_POST;

		$object = ORM::factory($this->orm_name)->find((int) $id);
		$object->values($post);		

		if ($object->check()) {
			if ($object->save()) {
				Message::instance()->succeed(__(':object saved'),  array(':object' => __($this->orm_name)));
			} else {
				Message::instance()->error(__(':object not saved'),  array(':object' => __($this->orm_name)));
			}
			$this->request->redirect($this->session->get_once('requested_url'));
		} else {
			$errorstring = "";
			foreach ($object->validate()->errors('register') as $key => $msg) {
				$errorstring .= $msg . "<br />";
			}
			$this->session->set('post_data_' . $this->orm_name, $post);
			Message::instance()->error($errorstring);
			$this->request->redirect(Request::$referrer);
		}
	}
	
	/**
	 * delete a object
	 * 
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	public function action_delete($id)
	{
		$this->auto_render = false;

		$object = ORM::factory($this->orm_name)->find((int) $id);

		if ($object->loaded()) {
			$object->delete();
			Message::instance()->succeed(__(':object removed'),  array(':object' => __($this->orm_name)));
		} else {
			Message::instance()->error(__(':object not found', array(':object' => __($this->orm_name))));
		}

		$this->request->redirect($this->session->get_once('requested_url'));

	}


	/**
	 * shows profilerstats if enviroment is development
	 * 
	 * @access public
	 * @return void
	 */
	public function after()
	{
		parent::after();
		
		if (Request::$is_ajax == false && Kohana::$environment == "development") {
			$this->request->response .= "<div style='display:block;padding-top:300px'>" . View::factory('profiler/stats') . "</div>";
		}
	}

}
?>
