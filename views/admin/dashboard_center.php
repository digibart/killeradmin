<div class="span-7 box">
		<H4><?= ucfirst(__('systeminfo')); ?></H4>
		<strong>Kohana versie</strong>: <?= Kohana::VERSION; ?><br />
		<strong>PHP versie</strong>: <?= phpversion(); ?><br />
		<strong>Aantal gebruikers</strong>: <?= ORM::factory('user')->count_all(); ?><br>
		<strong>Environment</strong>: <?= KOHANA::$environment ?>
</div>