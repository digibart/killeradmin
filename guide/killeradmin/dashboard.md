Dashboard
=========

Once a user is logged in, he will be redirected to the Dashboard page. Here is how you can change the content of the Dashboard.

In this example we will continue with our tutorial and display reservations in the center.

1. Create the controller
----------------------

The dashboard contains of three actions, `action_left`, `action_center` and `action_right`. Each action represents a column. 
First create a controller `controller/admin/dashboard.php`:

	class Controller_Admin_Dashboard extends Controller_Admin_Core_Dashboard {
			
			/**
			* This is the right column of the dashboard
			* 
			* @access public
			* @return void
			*/
			$this->auto_render = false;
			
			$persons = array();
			for ($i = 0; $i < 7; $i++)
			{
				$date = date("Y-m-d", (time() + (86400 * $i)));
				$persons[$date] = 0;
			
				$tables = ORM::factory('table')->with('reservation')->where('reservation.start', 'like', $date . ' %')->find_all();
			
				foreach ($tables as $table)
				{
					$persons[$date] += $table->size;
				}
			}
			
			echo View::factory('admin/dashboard_reservations')
			->set('persons', $persons);
			
	}

	
2. Create the view
----------------

Next (and final) step is to create the view `views/admin/dashboard_reservations.php`.

	<div class="eight columns">
			<h2><?php echo ucfirst(__('reservations')); ?></h2>
			
			<div class="twentyfour table columns">
				<div class="header row"  style="min-width: 0">
					<div class="eighteen columns"><?php echo __('date'); ?></div>
					<div class="six columns last"><?php echo __('persons'); ?></div>
				</div>
			
			<?php foreach ($persons as $date => $amount) : ?>
				<div class="row" style="min-width: 0">
					<div class="three columns"><?php echo strftime("%a", strtotime($date)); ?></div>
					<div class="fiveteen columns"><?php echo strftime("%e %b", strtotime($date)); ?></div>
					<div class="six columns last"><?php echo $amount; ?></div>
				</div>
			<?php endforeach; ?>
			</div>
	</div>



Conclusion
-------------

And there you have it, the center column of the dashboard now shows the reservations for next 7 days.
