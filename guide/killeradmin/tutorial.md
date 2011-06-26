# 1. Installation

### 1.1 Enable the modules

Enable the modules in your `bootstrap.php`:

	$modules = array(
		'killeradmin'=> MODPATH.'killeradmin',// Admin for end-users
		'auth'       => MODPATH.'auth',       // Basic authentication
		'database'   => MODPATH.'database',   // Database access
		'orm'        => MODPATH.'orm',        // Object Relationship Mapping
		'pagination' => MODPATH.'pagination', // Paging of results
		'cache'      => MODPATH.'cache',      // Caching with multiple backends
	);
	
Config the auth and database module as needed.	
	
[!!] It is important to load the `killeradmin` before the `auth` module, because `killeradmin` overwrites some files of `auth`.


### 1.2 Setup the database

Next step is to create the database tables if not done already. You can find the mysql or postgresql in `modules/auth/mysql.sql` or `modules/auth/postgresql.sql`

### 1.3 Setup Killer Admin

Go to [/admin/setup](http://localhost/admin/setup). You will get an `acces denied` error if already users exists, or `Kohana::$enviroment` is not `KOHANA::DEVELOPMENT`.
This page checks if all the modules are enabled, and Auth and KillerAdmin modules are configured.
If all goes well, you can create a new user at the bottom of the page. Jump to the next chapter [Configuration](tutorial#2-configuration) on how to config KillerAdmin.

### 1.4 Done
That's it, if you go to [/admin/](http://localhost/admin/) you should be redirected to the login page.

# 2 Configuration

### 2.1 Creating a menu button
All the menu buttons are defined in `config/admin.php`. Copy `config/admin.sample.php` to your `application/config` folder. 
To add menu items, add keys to the menu array, for example:

	'menu' =>
		array(
			'admin/cars' => array(				// the url to controller
				'name' => ucfirst(__('users')),	// the name shown in menu
				'secure_actions' => array(		// This array controls access to specified actions
					'default' => 'login',		// default role required to access the actions in this controller
					'index' => 'login',			// required role to list cars 
					'add' => 'admin',			// required role to add new cars
					'edit' => 'login',			// required role to edit cars
					'delete' => 'admin'			// required role to delete cars
				),
			),
		)
		

All the classes and views for managing users are included in the module. To enable user-manager, simply add the following to menu array:

	'admin/users' => array(
		'name' => ucfirst(__('users')),
		'secure_actions' => array(
			'default' => 'admin'
		),
	),

# 3 Adding a page
		
### 3.1 Creating the controller
Now you have a menu-button 'cars', you'll need a controller. All the magic to list, filter, sort, add, edit and delete objects is in [Controller_Admin_Base], so extend we'll that class.

Below is an example of `classes/admin/cars.php`:

	class Controller_Admin_Cars extends Controller_Admin_Base {
	
		// The name of the orm we are managing with this controller
		protected $orm_name = 'car';
		
		public function before() {
			parent::before();
		
			// the base object is used for loading, editing, viewing a object
			// not neccesary when objects don't need to be filtered
			// in this example, user can only edit cars they own
			$this->base_object = ORM::factory('car')->where('user_id', '=', $this->user->id);
		}
	}

### 3.2 Creating the model

Next thing you'll need is a model. Below is an example of `classes/model/car.php`. As you can see, it's pretty basic, just extend the [orm] and do as you would regulary do.

[!!]You should to set the `public function rules()` for validating the orm while saving.

	class Model_Car extends ORM {
		
		protected $_belongs_to = array('user' => array());
	
		public function rules()
		{
			return array(
				'brand' => array(
					array('not_empty'),
					array('min_length', array(':value', 3)),
					array('max_length', array(':value', 25)),
				),
				'miles' => array(
					array('digit', array(':value')),
				),
				'color' => array(
					array('min_length', array(':value', 0)),
					array('max_length', array(':value', 10)),
				),
			);
		}
	}
	
### 3.3 Creating the views

The last thing you'll need are two views: 

1. `views/admin/{orm_name}_list.php`: view for listing the objects
2. `views/admin/{orm_name}_form.php`: view for adding/ editing objects.

[!!] Where `{orm_name}` is the `$orm_name` you set in the Controller.

#### List
The `{orm_name}_list` has the following values:

    View::factory('admin/'.$this->orm_name.'_list')
        ->bind('objects', $objects)                      // the filtered objects found
        ->bind('auth_user', $this->user)                 // current user logged in
        ->bind('filter', $filter)                        // current filter values used
        ->bind('count', $count)                          // number of found objects
        ->bind('controller_url', $this->controller_url)  // url to the current controller
        ->bind('pagination', $pagination);               // rendered pagination

Example of `views/admin/car_list.php`

	<a name="list"></a>
	<form method="get">
		<table>
			<!-- filter fields	 -->
			<tr>
				<td><?php echo Killeradmin::filterField('brand', $filter); ?></td>
				<td><?php echo Killeradmin::filterField('color', $filter); ?></td>
				<td><?php echo Killeradmin::filterField('miles', $filter); ?></td>
				<td colspan="2"><?php echo Killeradmin::filterButton();?></td>
			</tr>
			<!-- headings -->
			<tr>
				<th class="span-5">Brand&nbsp;<?php echo Killeradmin::sortAnchor('brand'); ?></th>
				<th class="span-3">Color&nbsp;<?php echo Killeradmin::sortAnchor('color'); ?></th>
				<th class="span-3">Miles&nbsp;<?php echo Killeradmin::sortAnchor('miles'); ?></th>
				<th>&nbsp;</th>
			</tr>
			
			<!-- loop trough the cars -->
			<?php foreach ($objects as $object) :?>
			<tr>
				<td><?php echo $object->brand; ?></td>	
				<td><?php echo $object->color; ?></td>	
				<td><?php echo number_format($object->miles); ?></td>	
				<td nowrap="nowrap">
					<?php echo html::anchor($controller_url . '/edit/' . $object->id, KillerAdmin::spriteImg('edit'), __('edit')); ?> 
					<?php echo html::anchor($controller_url . '/delete/' . $object->id, KillerAdmin::spriteImg('delete'), __('delete') , array('class' => 'delete')); ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
	</form>
	
	<!-- new car button -->
	<?php echo Killeradmin::newButton('car');?>
	
	<!-- pagination -->
	<div class="span-26 last pagination">
		<?php echo $pagination;?>
	</div>


#### Form

The `{orm_name}_form` view is used to dispay a form for editing a object. The view has the following values:

    View::factory('admin/'.$this->orm_name.'_form')
        ->set('referrer', $this->session->get('requested_url'))     // for linking back to list
        ->set('controller_url', $this->controller_url)              // the url of current controller
        ->set('auth_user', $this->user)                             // current user logged in
        ->set($this->orm_name, $object);                            // the object, in this case 'car'

Example of `views/admin/car_form.php`


        <form method="post" action="<?php echo url::site($controller_url . '/save/' . $car->id);?>">

            <label>Brand <small>(3-25 characters)</small></label>
                    <input type="text" name="brand" value="<?php echo $car->brand;?>"><br>
            <label>Miles</label>
                    <input type="text" name="miles" value="<?php echo $car->miles; ?>"><br>
            <label>Color <small>(0-10 characters)</small></label>
                    <input type="text" name="color" value="<?php echo $car->miles; ?>"><br>
            <div class="span-24">
                    <p><br>
                         <button type="submit" class="button positive"><?php echo KillerAdmin::spriteImg('save');?><?php echo __('save'); ?></button>
                         <?php echo html::anchor($referrer, __('go back')); ?>
                    </p>
            </div>
        </form>

# Done

That's it, you are now able to add, edit, and delete cars.
