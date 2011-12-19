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
