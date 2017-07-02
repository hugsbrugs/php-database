<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Hug\Database\SqlLiteDB as SqlLiteDB;

# Use constants defined in config.ini to get default db
$db1 = SqlLiteDB::getInstance();

$tables = $db1->list_tables();
var_dump($tables);

$exists = $db1->table_exists('tatayoyo');
var_dump($exists);
$exists = $db1->table_exists('coucou');
var_dump($exists);

$db1->create_table('tatayoyo');
$db1->truncate_table('tatayoyo');
$db1->drop_table('tatayoyo');


# Or pass params
$db2 = SqlLiteDB::getInstance(
	$path = '/PATH_TO/sqllite.db', 
	$user = '', 
	$pass = '', 
	$name = 'test', 
	$env = 'dev'
);
$tables = $db2->list_tables();
var_dump($tables);

