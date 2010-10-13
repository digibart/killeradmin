<?php
$grid = new Grid;
$grid->link()->action('entries/add')->text(__('entry.new'));
$grid->column()->field('id')->title('id');
$grid->column()->field('name')->title('Naam');
$grid->column()->field('address')->title('Adres');
$grid->column('action')->title('')->url(Request::instance()->directory . '/'. Request::instance()->controller . '/edit')->text(__('entry.edit'));
$grid->column('action')->title('')->url(Request::instance()->directory . '/'. Request::instance()->controller . '/edit')->text(__('entry.delete'));
$grid->data($objects);
echo $grid;
?>
