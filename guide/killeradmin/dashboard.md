Dashboard
=========

Once a user is logged in, he will be redirected to the Dashboard page. Here is how you can change the content of the Dashboard.

In this example we will replace the right column with statistics about the user cars.

1. Create the controller
----------------------

The dashboard contains of three actions, `action_left`, `action_center` and `action_right`. Each action represents a column. 
Create a controller `controller/admin/dashboard.php`:

	class Controller_Admin_Dashboard extends Controller_Admin_Core_Dashboard {
			
			/**
			* This is the right column of the dashboard
			* 
			* @access public
			* @return void
			*/
			public function action_right()
			{
				$car_count = ORM::factory('car')->where('user_id', '=', $this->user->id)->count_all();
				$total_car_count = ORM::factory('car')->count_all();
				$percentage = round($total_car_count / $car_count) * 100;
		
				echo View::factory('admin/dashboard_right')
					->set('car_count', $car_count)
					->set('total_car_count', $total_car_count)
					->set('percentage', $percentage);
			}	
			
	}

	
2. Create the view
----------------

Next (and final) step is to create the view `views/admin/dashboard_right.php`. The css class `box` will create a nice box, and the `h4` makes a splendid header.


	<div class="span-7 box">
		<H4>Cars</H4>
		Cars you own: <?php echo $car_count; ?><br>
		Total cars in database: <?php echo $total_car_count; ?><br>
		Percentage you own: <?php echo $percentage; ?>%
	</div>


Conclusion
-------------

This is a realy lame example, but it should explain the basics.
