<?php

/**
 * Controller_Admin_Core_Base class.
 *
 * @extends Controller_Template
 * @package Killer-admin
 * @category Controller
 */
class Controller_Admin_Core_Base extends Controller_Template {
	public $template = "admin/template";
	private $controller_url;
	private $session;
	public $request;
	public $user;
	public $menu;

	public function __construct($request, $response)
	{
		parent::__construct($request, $response);

		$this->request = Request::current();
		$this->session= Session::instance();
		$this->menu = Kohana::$config->load('admin.menu');
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

		//gather roles required to perform actions
		if (isset($this->menu[Kohana::$config->load('admin.base_url') . "/" . $this->request->controller()]['secure_actions'])) {
			$this->secure_actions = $this->menu[Kohana::$config->load('admin.base_url') . "/" . $this->request->controller()]['secure_actions'];
			if (!isset($this->secure_actions['index'])) {
				$this->secure_actions['index'] = null;
			}
		} elseif (!isset($this->secure_actions) || !is_array($this->secure_actions)) {		
			$this->secure_actions = null;
		}
		
		$action_name = $this->request->action();
		if (isset($this->secure_actions[$action_name])) {
			$required_role = $this->secure_actions[$action_name];
		} elseif (isset($this->secure_actions['default'])) {
			$required_role = $this->secure_actions['default'];
		} else {
			$required_role = false;
		}

		// these page don't require a login
		$no_login = array(
			Route::get('admin/base_url')->uri(array('controller' => 'main', 'action' => 'login')),
			Route::get('admin/base_url')->uri(array('controller' => 'main', 'action' => 'forgot'))
			);
			
		//check if a user is authorized to view the requested page
		if (!in_array($this->request->uri(), $no_login)) {
			if (!is_array($this->secure_actions) || (Auth::instance()->logged_in($required_role) === false)) {
				if (Auth::instance()->logged_in()) {
					Message::instance()->error(ucfirst(__('access denied')));
					$referrer = ($this->request->referrer()) ? $this->request->referrer() : Route::get('admin/base_url')->uri();
					$this->request->redirect($referrer);					
				} else {
					$this->request->redirect(Route::get('admin/base_url')->uri(array('controller' => 'main', 'action' => 'login')));
				}
			} else {
				$this->user = Auth::instance()->get_user();
			}
		} elseif (Auth::instance()->logged_in()) {
			$this->request->redirect(Route::get('admin/base_url')->uri(array('controller' => 'main')));
		}
		

		//set the template values
		$this->template->title = "Admin";
		$this->template->content = "";
		$this->template->scripts = array();

		$this->controller_url = Route::get('admin/base_url')->uri(array('controller'=> $this->request->controller()));


		//show the menu
		if (Auth::instance()->logged_in()) {
			if (count($this->menu) == 0) {
				Message::instance()->info(Kohana::message('admin', 'menu not found'));
				$this->menu = array();
			}

			$roles = array();
			foreach ($this->user->roles->find_all() as $role) {
				$roles[] = $role->name;
			}

			$menuitems = array();
			foreach ($this->menu as $url => $menuitem) {
				$required_role = (isset($menuitem['secure_actions']['index'])) ? $menuitem['secure_actions']['index'] : $menuitem['secure_actions']['default'];
				if (in_array($required_role, $roles)) {
					$menuitems[$menuitem['name']] = $url;
				}
			}

			$this->template->menu = View::factory('admin/menubar')
			->set("items", $menuitems);
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
		$this->session->set('requested_url', $this->request->uri() . Url::query());
		$this->session->delete('post_data_' . $this->orm_name);

		//count total objects
		$count = (isset($this->base_object)) ? $this->base_object->reset(false) : ORM::factory($this->orm_name);
		foreach ($filter as $field => $value) {
			$count->and_where($field, 'like', '%' . $value . '%');
		}
		$count = $count->count_all();


		//display message if no objects found
		if ($count == 0) {
			$msg = strtolower(__('no :objects found',  array(':objects' => __($this->orm_name))) . ".");

			//display link 'clear filter'
			if (isset($get['filter'])) {
				$query = Url::query(array('filter' => null));
				$msg .= "&nbsp;" . html::anchor($this->controller_url . $query, __('clear filter'));
			}
			Message::instance()->info($msg);
		}

		//collect the orm objects
		$objects = (isset($this->base_object)) ? $this->base_object->reset(false) : ORM::factory($this->orm_name);

		// and apply filters
		foreach ($filter as $field => $value) {
			$objects->and_where($field, 'like', '%' . $value . '%');
		}

		// and apply sorting
		if (Arr::get($_GET, 'sort') && array_key_exists($_GET['sort'], $objects->list_columns())) {
			$order = (Arr::get($_GET, 'order', 'asc') == 'asc') ? 'asc' : 'desc';
			$objects->order_by(Arr::get($_GET, 'sort'), $order);
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
	 * @return void
	 */
	public function action_edit()
	{
		$id = $this->request->param('id');
		
		$this->template->title = ucfirst(__('edit :object', array(':object' => __($this->orm_name))));

		$object = (isset($this->base_object)) ? $this->base_object->reset(false) : ORM::factory($this->orm_name);
		$object->where('id', '=', (int) $id)->find();

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
	 * @return void
	 */
	public function action_save()
	{
		$this->auto_render = false;
		$id = $this->request->param('id');
		

		$post = $_POST;

		$object = (isset($this->base_object)) ? $this->base_object : ORM::factory($this->orm_name);
		$object->where('id', '=', (int) $id)->find();
		$object->values($post);

		// add current user if object does not belongs to a user
		if (array_key_exists('user_id', $object->list_columns()) && $object->user_id == null) {
			$object->user_id = $this->user->id;
		}

		try {
			if ($object->save()) {
				Message::instance()->succeed(__(':object saved'),  array(':object' => __($this->orm_name)));
			} else {
				Message::instance()->error(__(':object not saved'),  array(':object' => __($this->orm_name)));
			}
			$this->request->redirect($this->session->get_once('requested_url'));

		} catch (ORM_Validation_Exception $e) {
			$errorstring = "";
			$errors = $e->errors('register');

			foreach ($errors as $key => $msg) {
				$errorstring .= $msg . "<br />";
			}
			$this->session->set('post_data_' . $this->orm_name, $post);
			Message::instance()->error($errorstring);
			$this->request->redirect($this->request->referrer());
		}

	}

	/**
	 * delete a object
	 *
	 * @access public
	 * @return void
	 */
	public function action_delete()
	{
		$this->auto_render = false;
		$id = $this->request->param('id');	

		$object = (isset($this->base_object)) ? $this->base_object : ORM::factory($this->orm_name);
		$object->where('id', '=', (int) $id)->find();

		if ($object->loaded()) {
			$object->delete();
			Message::instance()->succeed(__(':object removed'),  array(':object' => __($this->orm_name)));
		} else {
			Message::instance()->error(__(':object not found', array(':object' => __($this->orm_name))));
		}

		$this->request->redirect($this->session->get_once('requested_url'));

	}



}
?>
