<?php

return array(
	'not_empty'    => ':field must not be empty',
	'matches'      => ':field must be the same as :param1',
	'regex'        => ':field does not match the required format',
	'exact_length' => ':field must be exactly :param1 characters long',
	'min_length'   => ':field must be at least :param1 characters long',
	'max_length'   => ':field must be less than :param1 characters long',
	'in_array'     => ':field must be one of the available options',
	'digit'        => ':field must be a digit',
	'decimal'      => ':field must be a decimal with :param1 places',
	'range'        => ':field must be within the range of :param1 to :param2',
	'email'			=> ':field must be a valid email address',
	'strtotime'		=> ':field is not a valid date',
	'alpha_numeric' => ':field must contain only alpha or numbers',
	'url'			=> ':field must be a valid url'
);
?>