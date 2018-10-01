<?php 

namespace HelloFramework;

class Singleton {

    private static $instances = array();

    // ------------------------------------------------------------
    // When this class it should first store itself as a singleton.

    public function __construct() {

        $called = get_called_class();
        self::$instances[$called] = $this;

    }


    // ------------------------------------------------------------
    // This will return the class singlton, makes it easier for this to be a global class.

    public static function get_instance() {

        $called = get_called_class();

        if (empty(self::$instances[$called])) {
            return new $called;
        }

        return self::$instances[$called];

    }


    // ------------------------------------------------------------
    // Prevent cloning of Singleton classes.
    
    private final function __clone(){
   
    }

}
