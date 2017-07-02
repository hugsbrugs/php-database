<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Hug\Database\MySqlDB as MySqlDB;

# Use constants defined in config.ini to get default db
$db1 = MySqlDB::getInstance();
$tables = $db1->list_tables();
var_dump($tables);

$exists = $db1->table_exists('vigneron');
var_dump($exists);
$exists = $db1->table_exists('coucou');
var_dump($exists);

$db1->create_table('tatayoyo');
$db1->truncate_table('tatayoyo');
$db1->drop_table('tatayoyo');

# Or pass params
$db2 = MySqlDB::getInstance(
	$host = 'localhost', 
	$port = 3306, 
	$user = 'username', 
	$pass = 'password', 
	$name = 'database_name', 
	$env = 'dev'
);
$tables = $db2->list_tables();
var_dump($tables);
