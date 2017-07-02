<?php

namespace Hug\Database;

use PDO;

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

        error_log('host 1 : ' . $host);
        error_log('port 1 : ' . $port);
        error_log('user 1 : ' . $user);
        error_log('pass 1 : ' . $pass);
        error_log('name 1 : ' . $name);
        error_log('env 1 : ' . $env);

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

        // error_log('host : ' . DB_HOST);
        // error_log('port : ' . DB_PORT);
        // error_log('user : ' . DB_USER);
        // error_log('pass : ' . DB_PASS);
        // error_log('name : ' . DB_NAME);
        // error_log('env : ' . DB_ENV);

        error_log('host 2 : ' . $host);
        error_log('port 2 : ' . $port);
        error_log('user 2 : ' . $user);
        error_log('pass 2 : ' . $pass);
        error_log('name 2 : ' . $name);
        error_log('env 2 : ' . $env);

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
            self::$_instance = new $object($host = null, $port = null, $user = null, $pass = null, $name = null, $env = 'prod');
        }
        return self::$_instance;
    }
}
