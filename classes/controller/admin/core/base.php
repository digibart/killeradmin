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
	public $request;
	public $user;
	public $menu;

	protected $_controller_url;
	protected $_session;


	public function __construct($request, $response)
	{
		parent::__construct($request, $response);

		$this->request = Request::current();
		$this->_session = Session::instance();
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
		if (isset($this->menu[Kohana::$config->load('admin.base_url') . "/" . $this->request->controller()]['secure_actions']))
		{
			$this->secure_actions = $this->menu[Kohana::$config->load('admin.base_url') . "/" . $this->request->controller()]['secure_actions'];
			if (!isset($this->secure_actions['index']))
			{
				$this->secure_actions['index'] = null;
			}
		} elseif (!isset($this->secure_actions) || !is_array($this->secure_actions))
		{
			$this->secure_actions = null;
		}

		$action_name = $this->request->action();
		if (isset($this->secure_actions[$action_name]))
		{
			$required_role = $this->secure_actions[$action_name];
		}
		elseif (isset($this->secure_actions['default']))
		{
			$required_role = $this->secure_actions['default'];
		}
		else
		{
			$required_role = false;
		}

		// these page don't require a login
		$no_login = array(
			Route::get('admin/base_url')->uri(array('controller' => 'main', 'action' => 'login')),
			Route::get('admin/base_url')->uri(array('controller' => 'main', 'action' => 'forgot'))
		);

		//check if a user is authorized to view the requested page
		if (!in_array($this->request->uri(), $no_login))
		{
			if (!is_array($this->secure_actions) || (Auth::instance()->logged_in($required_role) === false))
			{
				if (Auth::instance()->logged_in())
				{
					Killerflash::instance()->error(ucfirst(__('access denied')));
					$referrer = ($this->request->referrer()) ? $this->request->referrer() : Route::get('admin/base_url')->uri();
					$this->request->redirect($referrer);
				} else
				{
					$this->request->redirect(Route::get('admin/base_url')->uri(array('controller' => 'main', 'action' => 'login')));
				}
			} else
			{
				$this->user = Auth::instance()->get_user();
			}
		} elseif (Auth::instance()->logged_in())
		{
			$this->request->redirect(Route::get('admin/base_url')->uri(array('controller' => 'main')));
		}


		//set the template values
		$this->template->set(array(
			'title' => Kohana::$config->load('admin.company_name'),
			'content' => '',
			'scripts' => array()
		));

		$this->_controller_url = Route::get('admin/base_url')->uri(array('controller'=> $this->request->controller()));


		//show the menu
		if (Auth::instance()->logged_in())
		{
			if (count($this->menu) == 0)
			{
				Killerflash::instance()->info(Kohana::message('admin', 'menu not found'));
				$this->menu = array();
			}

			$roles = array();
			foreach ($this->user->roles->find_all() as $role)
			{
				$roles[] = $role->name;
			}

			$menuitems = array();
			foreach ($this->menu as $url => $menuitem)
			{

				if (Arr::get($menuitem, 'hidden') !== true)
				{
					$required_role = (isset($menuitem['secure_actions']['index'])) ? $menuitem['secure_actions']['index'] : $menuitem['secure_actions']['default'];
					if (in_array($required_role, $roles))
					{
						$menuitems[$menuitem['name']] = $url;
					}
				}
			}

			$this->template->menu = View::factory('admin/menubar')
				->set("items", $menuitems);
		}
		else
		{
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

		$page = Arr::get($_GET, 'page', 1);
		$offset = (($page > 0 ) ? $page - 1 : 0) * 20;

		//converts $_GET['filter'] in an array
		$filter = array();
		if (Arr::get($_GET, 'filter'))
		{
			foreach (Arr::get($_GET, 'filter') as $field => $value)
			{
				if ($value)
				{
					$filter[$field] = $value;
				}
			}
		}
		$this->_session->set('requested_url', $this->request->uri() . Url::query());
		$this->_session->delete('post_data_' . $this->orm_name);

		//count total objects
		$count = (isset($this->base_object)) ? $this->base_object->reset(false) : ORM::factory($this->orm_name);
		foreach ($filter as $field => $value)
		{
			$count->and_where($field, 'like', '%' . $value . '%');
		}
		$count = $count->count_all();


		//display message if no objects found
		if ($count == 0)
		{
			$msg = strtolower(__('no :objects found',  array(':objects' => __($this->orm_name))) );

			//display link 'clear filter'
			if (isset($_GET['filter']))
			{
				$query = Url::query(array('filter' => null));
				$msg .= "&nbsp;" . html::anchor($this->_controller_url . $query, __('clear filter'));
			}
			Killerflash::instance()->info($msg);
		}


		//pagination
		$pagination = new Pagination;
		$page_config = $pagination->config_group('admin');
		$page_config['total_items'] = $count;
		$pagination->setup($page_config);

		//pagination, check if a page exists
		if (!$pagination->valid_page($page) && $pagination->__get('total_pages') > 0)
		{
			$query = Url::query(array('page' =>  $pagination->__get('total_pages')));
			$this->request->redirect($this->_controller_url . $query);
		}

		//collect the orm objects
		$objects = (isset($this->base_object)) ? $this->base_object->reset(false) : ORM::factory($this->orm_name);
		$objects->offset($offset)->limit(20);

		// and apply filters
		foreach ($filter as $field => $value)
		{
			$objects->and_where($field, 'like', '%' . $value . '%');
		}

		// and apply sorting
		if (Arr::get($_GET, 'sort') && array_key_exists($_GET['sort'], $objects->list_columns()))
		{
			$order = (Arr::get($_GET, 'order', 'asc') == 'asc') ? 'asc' : 'desc';
			$objects->order_by(Arr::get($_GET, 'sort'), $order);
		}
		$objects = $objects->find_all();


		//get the view
		if (is_object($this->template->content) && get_class($this->template->content) == "View")
		{
			$view = $this->template->content;
		}
		else
		{
			$view = View::factory('admin/'.$this->orm_name.'_list');
		}

		$view
			->set('objects', $objects)
			->set('auth_user', $this->user)
			->set('filter', $filter)
			->set('count', $count)
			->set('controller_url', $this->_controller_url)
			->set('pagination', $pagination);


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
		$object->values($this->_session->get_once('post_data_' . $this->orm_name, array()));

		//get the view
		if (is_object($this->template->content) && get_class($this->template->content) == "View")
		{
			$view = $this->template->content;
		}
		else
		{
			$view = View::factory('admin/'.$this->orm_name.'_form');
		}
		
		$view
			->set('referrer', $this->_session->get('requested_url'))
			->set('controller_url', $this->_controller_url)
			->set('auth_user', $this->user)
			->set($this->orm_name, $object);

		$this->template->content = $view;

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
		$object->where($object->object_name() . '.id', '=', $id)->find();

		//get the view
		if (is_object($this->template->content) && get_class($this->template->content) == "View")
		{
			$view = $this->template->content;
		}
		else
		{
			$view = View::factory('admin/'.$this->orm_name.'_form');
		}

		$this->template->content = $view
			->set('referrer', $this->_session->get('requested_url'))
			->set('controller_url', $this->_controller_url)
			->set('auth_user', $this->user)
			->set($this->orm_name, $object);

		if (!$object->loaded())
		{
			Killerflash::instance()->error(__(':object not found', array(':object' => __($this->orm_name))));
			$this->request->redirect($this->_session->get_once('requested_url'));
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


		$post = $this->request->post();

		$object = (isset($this->base_object)) ? $this->base_object : ORM::factory($this->orm_name);
		$object->where($object->object_name() . '.id', '=', $id)->find();
		
		if (isset($object->save_columns) && is_array($object->save_columns))
		{
			$object->values(Arr::extract($post, $object->save_columns));
		}
		else
		{
			$object->values($post);
		}

		// add current user if object does not belongs to a user
		if (array_key_exists('user_id', $object->list_columns()) && $object->user_id == null)
		{
			$object->user_id = $this->user->id;
		}

		try {
			if ($object->save())
			{
				Killerflash::instance()->succeed(__(':object saved'),  array(':object' => __($this->orm_name)));
			} else
			{
				Killerflash::instance()->error(__(':object not saved'),  array(':object' => __($this->orm_name)));
			}
			$this->request->redirect($this->_session->get_once('requested_url'));

		} catch (ORM_Validation_Exception $e)
		{
			$errorstring = "";
			$errors = $e->errors('');

			foreach ($errors as $key => $msg)
			{
				$errorstring .= $msg . "<br />";
			}
			$this->_session->set('post_data_' . $this->orm_name, $post);
			Killerflash::instance()->error($errorstring);
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
		$object->where('id', '=', $id)->find();

		if ($object->loaded())
		{
			$object->delete();
			Killerflash::instance()->succeed(__(':object removed'),  array(':object' => __($this->orm_name)));
		} else
		{
			Killerflash::instance()->error(__(':object not found', array(':object' => __($this->orm_name))));
		}

		$this->request->redirect($this->_session->get_once('requested_url'));

	}



}
?>
