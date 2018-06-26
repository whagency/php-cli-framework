<?php

echo "<?php\n";
?>

return [
	'conn' => [
		'db' => [
			'hostname' => '<?= $db_hostname ?>',
			'database' => '<?= $db_database ?>',
			'username' => '<?= $db_username ?>',
			'password' => '<?= $db_password ?>',
			'charset' => 'utf8',
		]
	]
];