<div style="font-family: Verdana, sans-serif"><p>Hello <?php echo $user->username;?>,</p>

<p>Your new <?php echo $company_name; ?> password is:<br>
<pre><?php echo $password; ?></pre></p>

<p>You can login at <?php echo html::anchor(Route::get('admin/base_url')->uri(array('controller' => 'main', 'action' => 'login'))); ?></p>

<p>Greetings,<br>
<?php echo $company_name; ?></p>
</div>