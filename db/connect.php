<?
    class dbconnect{
        static private $instance;
        private $connection;

        function __construct(){
            $servername = "127.0.0.1";
            $port = 3306;
            $username = "root";
            $password = "root";
            $database = "proj";

            $this->connection = new mysqli($servername, $username, $password, $database, $port);
        }
    
        public static function getConnection(){
            if(self::$instance === null){
                self::$instance = new dbconnect();
            }

            return self::$instance->connection;
        }
    }
?>