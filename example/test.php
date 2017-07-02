<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Hug\Database\MySqlDB as MySqlDB;

# Use constants defined in config.ini to get default db
$db1 = MySqlDB::getInstance();
list_tables($db1);

# Or pass params
$db2 = MySqlDB::getInstance(
	$host = 'localhost', 
	$port = 3306, 
	$user = 'username', 
	$pass = 'password', 
	$name = 'database_name', 
	$env = 'dev'
);
list_tables($db2);

/**
 *
 */
function list_tables($db)
{
	# List Tables
	$query = $db->dbh->prepare('show tables');
	$query->execute();

	while($rows = $query->fetch(PDO::FETCH_ASSOC))
	{
	    var_dump($rows);
	}
}