# Installation

## Enable module

Enable the module in your `bootstrap.php`:

	$modules = array(
		'killeradmin'=> MODPATH.'killeradmin',// Admin for end-users
		'auth'       => MODPATH.'auth',       // Basic authentication
		'database'   => MODPATH.'database',   // Database access
		'orm'        => MODPATH.'orm',        // Object Relationship Mapping
		'pagination' => MODPATH.'pagination', // Paging of results
	);
	
Config the auth and database module as needed.	
	
[!!] It is important to load the `killeradmin` before the `auth` module, because `killeradmin` overwrites some files of `auth`.

### Create the database

Next step is to create the database. You can find the mysql or postgresql in `modules/auth/mysql.sql` or `modules/auth/postgresql.sql`

### Create initial user

To create a initial user with admin rights, go to `http://localhost/admin/users/setup`. 
This will create a user with username `admin` and password `admin`

[!!] Don't forget to change the password before you go live!

### Done
That's it, if you go to `http://localhost/admin/` you should be redirected to the login page.

# Configuration

## Creating a menu button
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

		
## Creating the controller
Now you have a menu-button 'cars', you'll need a controller. All the magic to list, filter, sort, add, edit and delete objects is in `Controller_Admin_Core_Base`, so extend we'll that class.

Below is an example of `classes/admin/cars.php`:

	class Controller_Admin_Cars extends Controller_Admin_Core_Base {
	
		// The name of the orm we are managing with this controller
		protected $orm_name = 'car';
		
		public function before() {
			parent::before();
		
			// the base object is used for loading, editing, viewing a object
			// not neccesary when objects don't need to be filtered
			$this->base_object = ORM::factory('car')->where('user_id', '=', $this->user->id);
		}
	}

## Creating the model

Next thing you'll need is a model. Below is an example of `classes/model/car.php`. As you can see, it's pretty basic. 

[!!]You need to set the `public function rules()` for validating the orm while saving.

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
	
## Creating the views

The last thing you'll need are two views: 

1. `views/admin/{orm_name}_list.php`: view for listing the objects
2. `views/admin/{orm_name}_form.php`: view for adding/ editing objects.

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
					<?php echo html::anchor($controller_url . '/edit/' . $object->id, html::image('admin/media/images/icons/pencil.png', array('title' => __('edit')))); ?> 
					<?php echo html::anchor($controller_url . '/delete/' . $object->id, html::image('admin/media/images/icons/bin.png', array('title' => __('delete'))), array('class' => 'delete')); ?>
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
                         <button type="submit" class="button positive"><?php echo html::image('admin/media/images/icons/save.png');?><?php echo __('save'); ?></button>
                         <?php echo html::anchor($referrer, __('go back')); ?>
                    </p>
            </div>
        </form>

# Done

That's it, you are now able to add, edit, and delete cars.
