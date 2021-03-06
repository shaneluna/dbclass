<?php
/**
 * php Database class using PDO
 *
 * The following post by Philip Brown was used as reference when creating this class:
 * http://culttt.com/2012/10/01/roll-your-own-pdo-php-class/
 * @see http://culttt.com/2012/10/01/roll-your-own-pdo-php-class/ http://culttt.com/2012/10/01/roll-your-own-pdo-php-class/
 * @author Shane T. Luna <sluna3@semprautilities.com>
 */

/**
 * php Database class using PDO
 */
class Database {
    
    /** @var string $dsn    Data source name (i.e. mysql, sqlsrv, etc.). */
    private $dsn;
    /** @var string $host   Host name to connect to. */
    private $host;
    /** @var string $dbname Database name used to connect to. */
    private $dbname;
    /** @var string $user   Username for database credentials. */
    private $user;
    /** @var string $pass   Password for database credentials. */
    private $pass;

    /** @var \PDO $dbconn   PDO object that represents a connection between PHP and a database server. */
    private $dbconn;
    /** @var \PDOStatment $stmt   Represents a prepared statment and, after the statment is executed, an associated result set. */
    private $stmt;
    /** @var mixed $error   Message of exception caught. */
    private $error;
    
    /**
     * Sets instance variables and attempts to connect to database--calls connect().
     * @param string $dsn    Data source name (i.e. mysql, sqlsrv, etc.).
     * @param string $host   Host name to connect to.
     * @param string $dbname Name of database to connect to.
     * @param string $user   Username for database credentials.
     * @param string $pass   Password for database credentials.
     */
    public function __construct($dsn, $host, $dbname, $user = null, $pass = null) {
        $this->dsn = $dsn;
        $this->host = $host;
        $this->dbname = $dbname;
        $this->user = $user;
        $this->pass = $pass;

        $this->connect();
    }

    
    /**
     * Connects to thhe database using PDO. Uses credentials from constructor else windows auth if none provided.
     */
    private function connect() {
        //If adding/removing/changing connection conditional, make sure to change for both if user/pass inputted and not inputted (1 AND 2)
        try {  

            //SET OPTIONS TO BE USED IN ALL CONNECTIONS
            $options = array (
                PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
            );

            //1. IF USER IS BLANK AND PASS IS BLANK
            //Both NULL and "" return 0 when using strlen
            //Allows for custom app pool identity and windows auth to be used in Azure
            if(strlen($this->user) == 0 && strlen($this->pass) == 0) {

                //MS SQL Server(sqlsrv)
                if($this->dsn == "sqlsrv") {
                    $this->dbconn = new PDO("$this->dsn:Server=$this->host;Database=$this->dbname", NULL, NULL, $options);
                }
                //MySQL(mysql), PostgreSQL(pgsql)
                else {
                    $this->dbconn = new PDO("$this->dsn:dbname=$this->dbname;host=$this->host", NULL, NULL, $options);
                }

            }//end if

            //2. ELSE USER AND PASS ARE INPUTTED
            else {

                //MS SQL Server(sqlsrv)
                if($this->dsn == "sqlsrv") {
                    $this->dbconn = new PDO("$this->dsn:Server=$this->host;Database=$this->dbname", $this->user, $this->pass, $options); 
                }
                //MySQL(mysql), PostgreSQL(pgsql)
                else {
                    $this->dbconn = new PDO("$this->dsn:dbname=$this->dbname;host=$this->host", $this->user, $this->pass, $options);
                }

            }//end else

        } 
        catch(Exception $e) { 
            die( $this->error = $e->getMessage() ); 
        }
    }//end connect

    /**
     * Disconnect function not completely necessary as "PHP will automatically close the connection when your script ends."
     */
    public function disconnect() {
        //unset($this->dbconn);
        $this->dbconn = null;
    }

    /////////////////////////////////////////////////////////////////////
    //ALL OTHER METHODS IN ALPHABETICAL ORDER////////////////////////////
    /////////////////////////////////////////////////////////////////////

    /**
     * Determines and returns the TYPE of the value to be used for the actual binding.
     * If type was ommitted originally, looks at the type of the value.
     * Called from bindParam and bindValue. Add to the switch cases as necessary.
     * @param  mixed $value Value/variable thats type is assessed and is to be binded.
     * @param  int $type Initialized to NULL but could be overwritten. Explicit data type to be determined for the parameter using PDO::PARAM_* constants.
     * @return int Returns the explicit data type determined for the parameter using PDO::PARAM_* constants.
     */
    private function bind($value, $type) {
        //$type should be null when sent if ommitted
        //See bindParam & bindValue functions
        if(is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT; //1
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL; //5
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL; //0
                    break;
                default:
                    $type = PDO::PARAM_STR; //2
            }
        }
        return $type;
    }

    /**
     * bindParam uses a variable as a reference that is only evaluated at execute. 
     * Therefore, variable value could still be changed after bind and before execute.
     * @param mixed $param Parameter identifier/placeholder (e.g. :name). Most times a string.
     * @param mixed $value Name of the PHP variable to bind to the SQL statement parameter.
     * @param int $type Explicit data type for the parameter using PDO::PARAM_* constants. If ommitted will attempt initially to NULL and bind attempts to resolve.
     */
    public function bindParam($param, $value, $type = null) {
        $type = $this->bind($value, $type);
        $this->stmt->bindParam($param, $value, $type);
    }

    /**
     * bindValue binds the VALUE vs. reference to the potential variable (bindParam).
     * Should be called after query and before execute.
     * @param mixed $param String of the parameter identifier/placeholder (e.g. :name). Most times a string.
     * @param mixed $value The value to bind to the parameter.
     * @param int $type Explicit data type for the parameter using PDO::PARAM_* constants. If ommitted will attempt initially to NULL and bind attempts to resolve.
     */
    public function bindValue($param, $value, $type = null) {
        $type = $this->bind($value, $type);
        $this->stmt->bindValue($param, $value, $type);
    }
    
    /**
     * Dumps the information contained by the prepared statment directionly on the output.
     * @link http://php.net/manual/en/pdostatement.debugdumpparams.php Read more.
     */
    public function debugDumpParams(){
        $this->stmt->debugDumpParams();
    }

    /**
     * execute method should be called after query and potentially bind.
     * query method uses prepare which is why execute should be called after.
     * @return bool Returns true on success or failse on failure.
     */
    public function execute() {
        try {
            return $this->stmt->execute();
        }
        catch(Exception $e) { 
            die( $this->error = $e->getMessage() ); 
        }
    }
    
    /**
     * query method uses prepare therefore (bind and) execute will still need to be called.
     * @param string $queryString String containg the query to be executed with items to be binded.
     */
    public function query($queryString) {
            $this->stmt = $this->dbconn->prepare($queryString);
    }
    
    /**
     * Returns the number of affected rows from the previous delete, update or insert statement.
     * To be called after execute().
     * @return int The number of rows affected.
     */
    public function rowCount(){
        return $this->stmt->rowCount();
    }

    /**
     * selectVersion method just used for initial testing.
     * Gets the version of the database.
     * @return string Returns the database version.
     */
    public function selectVersion() {
        return $this->dbconn->query('select @@version')->fetchColumn();
    }
    
    /**
     * Used to convert result set array to JavaScript Object Notation (JSON)
     * ( i.e. $database->toJSON($database->XAGresultset()) ).
     * @param  array $array Array to convert to JSON (the result set array).
     * @return string The new JSON ecnoded string.
     */
    public function toJSON($array) {
        $formattedData = json_encode($array);
        return $formattedData;
    }
    
    /**
     * XAGresultset eXecutes And Gets the FULL result set (all rows).
     * Uses fetchAll(PDO::FETCH_ASSOC).
     * PDO::FETCH_ASSOC: returns an array indexed by the COLUMN NAME as returned in the result set.
     * @return array The full result set array.
     */
    public function XAGresultset(){
    $this->execute();
    return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * XAGsingle eXecutes And Gets a single row (the first row).
     * Uses fetch(PDO::FETCH_ASSOC).
     * PDO::FETCH_ASSOC: returns an array indexed by the COLUMN NAME as returned in the result set.
     * @return array The returned single/first row of the result set.
     */
    public function XAGsingle(){
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

}//end class Database
?>