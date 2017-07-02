<?php

namespace Hug\Database;

use PDO;
use PDOException;

/**
 *
 * @package \class
 */
class MySqlDB
{

    /**
     * @var Singleton
     * @access private
     * @static
     */
    private static $_instance = null;

    public $dbh; // handle of the db connexion
    
    /**
     * Constructeur de la classe
     *
     * @param void
     * @return void
     */
    private function __construct($host = null, $port = null, $user = null, $pass = null, $name = null, $env = 'prod')
    {
        if(defined('DB_HOST') && $host === null)
            $host = DB_HOST;
        if(defined('DB_PORT') && $port === null)
            $port = DB_PORT;
        if(defined('DB_USER') && $user === null)
            $user = DB_USER;
        if(defined('DB_PASS') && $pass === null)
            $pass = DB_PASS;
        if(defined('DB_NAME') && $name === null)
            $name = DB_NAME;
        if(defined('DB_ENV') && $env === null)
            $env = DB_ENV;

        try
        {
            # Set Options
            # http://php.net/manual/en/ref.pdo-mysql.php */
            $options = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                PDO::MYSQL_ATTR_FOUND_ROWS => true,
                // to prevent errors when multi threads
                PDO::ATTR_PERSISTENT => false
            );

            # Create Connection
            $this->dbh = new PDO(
                'mysql:host='.$host.';dbname='.$name, 
                $user, 
                $pass, 
                $options
            );          
            
            # Error Reporting        
            if($env==='prod')
            {
                # Prod :
                $this->dbh->setAttribute(
                    PDO::ATTR_ERRMODE,
                    PDO::ERRMODE_EXCEPTION
                );
            }
            else
            {
                # Dev
                $this->dbh->setAttribute(
                    PDO::ATTR_ERRMODE,
                    PDO::ERRMODE_WARNING
                );
            }

        }
        catch(PDOException $e)
        {
            error_log("Database Connexion Error : " . $e->getMessage() );
        }
    }

    /**
     * Méthode qui crée l'unique instance de la classe
     * si elle n'existe pas encore puis la retourne.
     *
     * @param void
     * @return Singleton
     */
    public static function getInstance($host = null, $port = null, $user = null, $pass = null, $name = null, $env = 'prod')
    {
        if (!isset(self::$_instance))
        {
            $object = __CLASS__;
            self::$_instance = new $object($host, $port, $user, $pass, $name, $env);
        }
        return self::$_instance;
    }

    /**
     *
     */
    public function list_tables()
    {
        $tables = [];
        try
        {
            # List Tables
            $query = $this->dbh->prepare('show tables');
            $query->execute();

            while($rows = $query->fetch())
            {
                $tables[] = $rows[0];
                //var_dump($rows);
            }
        }
        catch (PDOException $e)
        {
            error_log('List Tables : ' . $e->getMessage());
        }

        return $tables;
    }

    /**
     *
     */
    public function table_exists($table)
    {
        $exists = false;
        # Try a select statement against the table
        # Run it in try/catch in case PDO is in ERRMODE_EXCEPTION.
        try
        {
            $result = $this->dbh->query('SELECT 1 FROM '.$table.' LIMIT 1');
            $exists = true;
        }
        catch (PDOException $e)
        {
            error_log('Table Exists : ' . $e->getMessage());
        }

        return $exists;
    }

    /**
     *
     */
    public function create_table($table, $columns = 'ID INT( 11 ) AUTO_INCREMENT PRIMARY KEY')
    {
        $created = false;

        try
        {
            $result = $this->dbh->exec('CREATE TABLE IF NOT EXISTS '.$table.' ('.$columns.')');
            $created = true;
        }
        catch (PDOException $e)
        {
            error_log('Create Table : ' . $e->getMessage());
        }

        return $created;
    }

    /**
     *
     */
    public function truncate_table($table)
    {
        $truncated = false;
        try
        {
            $truncate_tables = $this->dbh->prepare('TRUNCATE TABLE `'.$table.'`');
            $truncate_tables->execute();
            $truncated = true;
        }
        catch (PDOException $e)
        {
            error_log('Truncate Table : ' . $e->getMessage());
        }

        return $truncated;
    }

    /**
     *
     */
    public function drop_table($table)
    {
        $dropped = false;
        try
        {
             $this->dbh->exec('DROP TABLE IF EXISTS ' . $table);
             $dropped = true;
        }
        catch (PDOException $e)
        {
            error_log('Drop Table : ' . $e->getMessage());
        }

        return $dropped;
    }
}
