<?php

namespace Hug\Database;

use PDO;
use Exception;
use PDOException;

/**
 *
 */
class SqlLiteDB
{

    /**
     * @var Singleton
     * @access private
     * @static
     */
    private static $_instance = null;

    public $dbh; // handle of the db connexion

    // public $db_path;
    
    /**
     * Constructeur de la classe
     *
     * @param void
     * @return void
     */
    private function __construct($path, $user = null, $pass = null, $name = null, $env = 'prod')
    {
        // $this->db_path = $db_path;

        if(defined('SQLLITE_DB_PATH') && $path === null)
            $path = SQLLITE_DB_PATH;
        if(defined('SQLLITE_DB_USER') && $user === null)
            $user = SQLLITE_DB_USER;
        if(defined('SQLLITE_DB_PASS') && $pass === null)
            $pass = SQLLITE_DB_PASS;
        if(defined('SQLLITE_DB_NAME') && $name === null)
            $name = SQLLITE_DB_NAME;
        if(defined('SQLLITE_DB_ENV') && $env === null)
            $env = SQLLITE_DB_ENV;

        try
        {
            # Create Connection
            # SQLITE 3
            $this->dbh = new PDO('sqlite:'.$path);

            $this->dbh->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE, 
                PDO::FETCH_ASSOC
            );
            
            # Error Reporting
            if($env==='prod')
            {
                # Prod
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
            error_log("Database Connection Error : " . $e->getMessage() );
        }
    }

    /**
     * Méthode qui crée l'unique instance de la classe
     * si elle n'existe pas encore puis la retourne.
     *
     * @param void
     * @return Singleton
     */
    public static function getInstance($path = null, $user = null, $pass = null, $name = null, $env = 'prod')
    {
        if (!isset(self::$_instance))
        {
            $object = __CLASS__;
            self::$_instance = new $object($path, $user, $pass, $name, $env);
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
            // $query = $BDD->dbh->query("SELECT name FROM sqlite_master WHERE type='table' AND name='proxies' COLLATE NOCASE;");
            // $res = $query->fetch();
            // $table_exists = $res['name']==='proxies' ? true: false;

            $query = $this->dbh->prepare('SELECT name FROM sqlite_master WHERE type="table" COLLATE NOCASE;');
            $query->execute();

            while($row = $query->fetch())
            {
                $tables[] = $row['name'];
                // var_dump($row);
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
    public function create_table($table, $columns = 'ID INTEGER PRIMARY KEY AUTOINCREMENT')
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
            $truncate_tables = $this->dbh->prepare('DELETE FROM `'.$table.'`; DELETE FROM SQLITE_SEQUENCE WHERE name=`'.$table.'`;');
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
