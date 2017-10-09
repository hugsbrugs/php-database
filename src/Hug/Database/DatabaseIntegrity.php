<?php

namespace Hug\Database;
// require_once __DIR__ . DIRECTORY_SEPARATOR . 'DatabaseCst.php';

/**
 *
 */
class DatabaseIntegrity
{
    public $fields = [];
    public $integrity = [];

    /**
     *
     */
    function __construct($fields = null)
    {
        $this->fields = $fields;
        if($this->fields!==null)
        {
            $this->test();
        }
    }

    /**
     *
     */
    public function test()
    {
        foreach ($this->fields as $field_name => $field_value)
        {
            //error_log('DatabaseIntegrity : ' . $field_name .' : '. $field_value);
            $field_length = 0;
            /*error_log('test gettype : ' . gettype($field_value));*/
            if(gettype($field_value)==='string')
            {
                $field_length = strlen($field_value);
            }
            else
            {
                $field_length = strlen(json_encode($field_value));
            }
            
            if( defined( $field_name.'_max_sz' ) )
            {
                $field_max_length = constant($field_name.'_max_sz');
                if( $field_length > $field_max_length)
                {
                    $this->integrity[$field_name] = 'ERROR_MAX_LENGTH' . $field_max_length;
                }
            }

            if( defined( $field_name.'_min_sz' ) )
            {
                $field_min_length = constant($field_name.'_min_sz');
                if( $field_length < $field_min_length)
                {
                    $this->integrity[$field_name] = 'ERROR_MIN_LENGTH' . $field_min_length;
                }
            }
        }
    }

}
