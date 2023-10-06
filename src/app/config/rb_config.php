<?php
require_once(PATH_LIB."/Medoo/Medoo.php");
// Using Medoo namespace.
use Medoo\Medoo;



class M {
   protected $database;
   public function __construct()
   {
    
    $this->database = new Medoo([
        'type' => 'mysql',
        'host' => dict("database.host"),
        'database' => dict("database.database"),
        'username' => dict("database.user"),
        'password' => dict("database.password")
    ]);

   }
   
   public static $mdb;

   public static function db() {
       if(M::$mdb==null)
        $mdb = new M();
       return $mdb->database;
   }
}



