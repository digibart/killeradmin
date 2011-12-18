<div class="four columns">
		<h2><?php echo ucfirst(__('systeminfo')); ?></h2>
		<strong>Kohana versie</strong>: <?php echo Kohana::CODENAME; ?> (<?php echo Kohana::VERSION;?>)<br />
		<strong>PHP versie</strong>: <?php echo phpversion(); ?><br />
		<strong>Killeradmin versie</strong>: <?php echo Killeradmin::CODENAME; ?> (<?php echo Killeradmin::VERSION;?>)<br />
		<strong>Aantal gebruikers</strong>: <?php echo ORM::factory('user')->count_all(); ?><br>
		<strong>Environment</strong>: <?php echo __('kohana_env_' . KOHANA::$environment) ?>
</div>