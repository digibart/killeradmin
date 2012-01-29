# Smacky Tables

In this tutorial we will be creating a simple online-table-reservation-system called `Smacky Tables`. Some of the features will be:

* Guests will be able to book a table online.
* Waitresses can see which table is booked by who. But they can only see the tables they serve.
* A cook can login, but only see how many tables are booked in the next seven days.
* The manager is able to add/delete tables, users, and reservations.

For the sake of shortness, I'll only discus small part of `Smacky Tables`. You can download/view the full code on github.


# 1. Installation

### 1.1 Enable the modules

Enable the modules in your `bootstrap.php`:

	Kohana::modules(array(
		'killeradmin' 	=> MODPATH.'killeradmin',
		'auth'       	=> MODPATH.'auth', 
		'cache'      	=> MODPATH.'cache',
		'captcha'    	=> MODPATH.'captcha',
		'database'  	=> MODPATH.'database',
		'orm'       	=> MODPATH.'orm',
		'pagination'  	=> MODPATH.'pagination',
	));
	
Config the auth and database module as needed.	
	
[!!] It is important to load the `killeradmin` before the `auth` module, because `killeradmin` overwrites some files of `auth`.


### 1.2 Setup the database

Next step is to create the database tables if you have not done already. You can find the mysql or postgresql in `modules/orm/auth-schema-mysql.sql` or `modules/orm/auth-schema-postgresql.sql`

For this tutorial you'll also need these tables:

	# Dump of table reservations
	# ------------------------------------------------------------

	CREATE TABLE `reservations` (
		`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		`table_id` int(11) DEFAULT NULL,
		`name` varchar(50) DEFAULT NULL,
		`phone` varchar(20) DEFAULT NULL,
		`email` varchar(100) DEFAULT NULL,
		`start` datetime DEFAULT NULL,
		`end` datetime DEFAULT NULL,
		`created` datetime DEFAULT NULL,
		`updated` datetime DEFAULT NULL,
		PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=latin1;


	# Dump of table tables
	# ------------------------------------------------------------

	CREATE TABLE `tables` (
		`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		`number` varchar(6) DEFAULT '',
		`size` int(11) DEFAULT NULL,
		`nickname` varchar(20) DEFAULT NULL,
		`user_id` int(11) DEFAULT NULL,
		PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=latin1;
	
	
	# Alter table users
	# ------------------------------------------------------------
	
	ALTER TABLE `users` ADD `name` VARCHAR(50)  NULL  DEFAULT NULL  AFTER `username`;

	

### 1.3 Setup Killer Admin

* Copy `config/admin.sample.php` to your `application/config` folder.
* Go to [http://example.com/admin/setup](http://example.com/admin/setup).
  This page checks if all the modules are enabled, and Auth and KillerAdmin modules are configured.
* If all goes well, you can create a new user at the bottom of that page. A random password is generated after you've created the user. The password is displayed at the top of the page after submission.

[!!] You will get an `acces denied` error if a user already exists, or `Kohana::$enviroment` is not `KOHANA::DEVELOPMENT`.


### 1.4 Done
That's it, if you go to [http://example.com/admin/](http://example.com/admin/) you should be redirected to the login page.

Log in and fast-forward to the next chapter [Configuration](tutorial#2-configuration) on how to config KillerAdmin.


# 2. Adding a page to manage the tables

Now lets create a page to manage the tables. And with tables I mean tables with legs and chairs and plastic flower on top, not database tables.

### 2.1 Creating a menu item
When you are loged in, there is a menu at the top of the page. These menu items can be configured in `config/admin.php`.

First we will add a menu-item for managing the tables:

	'menu' =>
		array(
			'admin/tables' => array(			// the url to controller
				'name' => ucfirst(__('tables')),// the name shown in menu
				'secure_actions' => array(		// This array controls access to specified actions
					'default' => 'login',		// default role required to access the actions in this controller
					'index' => 'login',			// required role to list tables 
					'add' => 'admin',			// required role to add new tables
					'edit' => 'admin',			// required role to edit tables
					'delete' => 'admin'			// required role to delete tables
				),
			),
		)
		
### 2.2 Creating a model

Next thing you'll need is a model. Below is an example of `classes/model/table.php`. Just extend the [orm] and do your regular `filters` and `rules` tricks.

	class Model_Table extends ORM {
	
		protected $_belongs_to = array('user' => array());
		protected $_has_one = array('reservation' => array());
	
		public function rules()
		{
			return array(
				'id' => array(
					array('digit'),
				),
				'number' => array(
					array('max_length', array(':value', 6)),
				),
				'size' => array(
					array('not_empty'),
					array('digit'),
				),
				'nickname' => array(
					array('max_length', array(':value', 20)),
				),
				'user_id' => array(
					array('digit'),
				),
	
			);
		}
	
		public function filters()
		{
			return array(
				true => array(
					array('strip_tags', array(':value')),
				),
			);
		}
	
	}

		
### 2.3 Creating the controller

Now you have a menu-button, and a model, you'll need a controller. All the magic to list, filter, sort, add, edit and delete objects is in [Controller_Admin_Base], so extend we'll that class.

Below is an example of `classes/admin/tables.php`:

	class Controller_Admin_Tables extends Controller_Admin_Base {
	
		public $orm_name = "table"; //the name of the model we'll be editing
	
		public function before()
		{
			parent::before();
	
			$this->template->title = ucfirst(__('tables'));
	
			// the base object is used for loading, editing, viewing a object
			// not neccesary when objects don't need to be filtered
			// in this example, the manager can see everything, but waitresses can only see tables where they serve
			if (Auth::instance()->logged_in('admin'))
			{
				//i'm a manager can see every table
				$this->base_object = ORM::factory('table');
			}
			else
			{
				//i'm just a waitress, i can only see my tables...
				$this->base_object = ORM::factory('table')->where('user_id', '=', $this->user->id);
			}
		}
	
		public function action_index()
		{
			if (!Auth::instance()->logged_in('admin'))
			{
				$this->template->content = View::factory('admin/table_list_waitress');
			}
			parent::action_index();
		}
	}
	
### 2.4 Creating the views

When you open the page [/admin/tables]() you will get an error saying the view `views/admin/tables_list` cannot be found. 
So we create the views:

1. `views/admin/{orm_name}_list.php`: view for listing the objects
2. `views/admin/{orm_name}_form.php`: view for adding/ editing objects.

[!!] Where `{orm_name}` is the `$orm_name` you set in the Controller.

[!!] Best practice is to copy `MODPATH/killeradmin/views/admin/user_list.php`, to your `APPPATH` and alter it.


#### 2.4.1 views/tables_list

This view lists all the tables. The following varables are set by [controller_admin_base]:

    View::factory('admin/'.$this->orm_name.'_list')
        ->bind('objects', $objects)                      // the filtered objects found
        ->bind('auth_user', $this->user)                 // current user logged in
        ->bind('filter', $filter)                        // current filter values used
        ->bind('count', $count)                          // number of found objects
        ->bind('controller_url', $this->controller_url)  // url to the current controller
        ->bind('pagination', $pagination);               // rendered pagination

Below is an example of `APPPATH/views/admin/tables_list.php`:

	<a name="list"></a>
	<div class="twenty columns">
		<form method="get" class="table">
			<div class="filter row">
				<div class="three columns"><?php echo Killeradmin::filterField('number', $filter); ?></div>
				<div class="nineteen last columns"><?php echo Killeradmin::filterButton(); ?></div>
			</div>
			<div class="header row">
				<div class="three columns"><?php echo ucfirst(__('number')); ?>&nbsp;<?php echo Killeradmin::sortAnchor('number'); ?></div>
				<div class="four columns"><?php echo ucfirst(__('size')); ?>&nbsp;<?php echo Killeradmin::sortAnchor('size'); ?></div>
				<div class="seven columns"><?php echo ucfirst(__('nickname')); ?>&nbsp;<?php echo Killeradmin::sortAnchor('nickname'); ?></div>
				<div class="ten columns last"><?php echo ucfirst(__('served by')); ?></div>
			</div>
			<?php $i = 0; foreach ($objects as $table) :?>
				<div class="row <?php echo Text::alternate('odd', 'even');?>" id="<?php echo $i++; ?>">
					<div class="three columns"><?php echo $table->number; ?></div>	
					<div class="four columns"><?php echo $table->size; ?> persons</div>	
					<div class="seven columns"><?php echo $table->nickname; ?></div>
					<div class="seven columns nowrap"><?php echo (($table->user->name) ? $table->user->name : $table->user->username); ?></div>	
					<div class="three tools columns last">
						<?php echo html::anchor($controller_url . '/edit/' . $table->id, KillerAdmin::spriteImg('pencil', __('edit') )); ?> 
						<?php echo html::anchor($controller_url . '/delete/' . $table->id, KillerAdmin::spriteImg('bin', __('delete')), array('class' => 'delete')); ?>
					</div>
				</div>
			<?php endforeach; ?>
		</form>
	</div>
	<div class="row">
	<?php echo Killeradmin::newButton('table'); ?>
	</div>
		
	<div class="row pagination">
		<?php echo $pagination;?>
	</div>

[!!] KillerAdmin uses a fluid 24 columns css grid.

#### 2.4.3 views/tables_form

The `tables_form` view is used to dispay a form for editing a object. The view has the following variables:

    View::factory('admin/'.$this->orm_name.'_form')
        ->set('referrer', $this->session->get('requested_url'))     // for linking back to list
        ->set('controller_url', $this->controller_url)              // the url of current controller
        ->set('auth_user', $this->user)                             // current user logged in
        ->set($this->orm_name, $object);                            // the object, in this case 'car'

Example of `views/admin/car_form.php`


	<?php
	$waitresses = ORM::factory('role', array('name' => 'waitress'))->users->find_all(); //finds all waitresses
	?>
	
	<div class="last ten columns prefix-8">
		<form method="post" class="validate" action="<?php echo url::site(Route::get('admin/base_url')->uri(array('controller' => 'tables', 'action' => 'save', 'id' =>  $table->id)));?>">
			<label for="number"><?php echo ucfirst(__('number'));?></label>
			<input type="text" name="number" id="number" class="required" maxlength="6" value="<?php echo $table->number;?>"><br>
		    
			<label for="size"><?php echo ucfirst(__('size'));?><span><?php echo __('how many persons'); ?></span></label>
			<input type="text" name="size" id="size" class="required number" value="<?php echo $table->size;?>"><br>
		        
			<label for="nickname"><?php echo ucfirst(__('nickname'));?></label>
			<input type="text" name="nickname" id="nickname" maxlength="20" value="<?php echo addslashes($table->nickname);?>"><br>
	
			<label for="waitress"><?php echo ucfirst(__('waitress'));?><span><?php echo __('who serves this table?'); ?></span></label>
			
			<select name="user_id" id="waitress">
				<?php foreach ($waitresses as $waitress) : ?>
					<option value="<?php echo $waitress->id; ?>" <?php echo (($table->user_id == $waitress->id) ? 'selected=selected' : '') ?>>
						<?php echo (($waitress->name) ?  $waitress->name :  $waitress->username); ?>
					</option>			
				<?php endforeach; ?>
			</select>
					
		   	<div class="button bar">
		       		<?php echo html::anchor($referrer, __('go back'), array('class' => 'nice button')); ?>
		       		<button type="submit" class="nice primary button"><?php echo KillerAdmin::spriteImg('save');?><?php echo __('save'); ?></button>	   		
		   	</div>
		</form>
	</div>

# 3. Fast-forward

That's it, a manager can now login and add new tables, edit existing ones, attach a waitress at a table, and more.


