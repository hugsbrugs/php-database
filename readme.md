## php-database

This library provides utilities function to ease MySql databse connections

```
composer require hugsbrugs/php-database
```

Create a config.ini file 
```
[mysql database]
MYSQL_DB_HOST = 'localhost'
MYSQL_DB_PORT = 3306
MYSQL_DB_USER = 'username'
MYSQL_DB_PASS = 'password'
MYSQL_DB_NAME = 'database_name'
MYSQL_DB_ENV = 'dev';'prod'

[sqllite database]
SQLLITE_DB_PATH = '/PATH_TO/sqllite'
SQLLITE_DB_USER = ''
SQLLITE_DB_PASS = ''
SQLLITE_DB_NAME = 'garden'
SQLLITE_DB_ENV = 'dev';'prod'
```

Create a php file
```
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Hug\Database\MySqlDB as MySqlDB;

# Use constants defined in config.ini to get default db
$db = MySqlDB::getInstance();
list_tables($db1);

# Or pass params
$db = MySqlDB::getInstance(
	$host = 'localhost', 
	$port = 3306, 
	$user = 'username', 
	$pass = 'password', 
	$name = 'database_name', 
	$env = 'dev'
);
```

Have a look at example folder for full working code.


## Author

Hugo Maugey [visit my website ;)](https://hugo.maugey.fr)