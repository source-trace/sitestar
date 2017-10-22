<?php


if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * Columns are returned into the array having the fieldname as the array index
 * Equals MYSQLI_ASSOC
 */
define('RSTYPE_ASSOC', MYSQLI_ASSOC);
/**
 * Columns are returned into the array having a numerical index to the fields
 * This index starts with 0, the first field in the result
 * Equals MYSQLI_NUM
 */
define('RSTYPE_NUM', MYSQLI_NUM);
/**
 * Columns are returned into the array having both a numerical index
 * and the fieldname as the array index
 * Equals MYSQLI_BOTH
 */
define('RSTYPE_BOTH', MYSQLI_BOTH);

/**
 * The global database connection reference
 *
 * @global object $GLOBALS['_DB_CONNECTION']
 * @name $_DB_CONNECTION
 */
$GLOBALS['_DB_CONNECTION'] = null;

/**
 * The MySQL connection class based on php_mysqli extension
 *
 * @package mysqli
 */
class MysqlConnection {
    /**
     * MySQL connection resource
     *
     * @access private
     * @var object
     */
    private $_db_ref;

    /**
     * Whether using debug mode
     *
     * @access public
     * @var bool
     */
    public $debug;

    /**
     * The MySQL connection constructor
     *
     * @global object
     * @param string $db_host The hostname or IP of database server
     * @param string $db_user The user name for accessing databases
     * @param string $db_pwd The password of the user
     * @param string $db_name The name of database you want to use
     */
    public function __construct($db_host, $db_user, $db_pwd, $db_name) {
        $this->_db_ref = @mysqli_connect($db_host, $db_user, $db_pwd, $db_name, Config::$port);
        if (!$this->_db_ref) {
            die('database server '.$db_host.' connect error!<br />'
                .mysqli_error());
        }

        @mysqli_set_charset($this->_db_ref, Config::$mysqli_charset);

        $select_db_rs = @mysqli_select_db($this->_db_ref, $db_name);
        if (!$select_db_rs) {
            $error = 'error '.mysqli_errno($this->_db_ref).': '
                .mysqli_error($this->_db_ref);
            die($error);
        }

        $this->debug = false;

        global $_DB_CONNECTION;
        $_DB_CONNECTION = $this;
    }

    /**
     * Get the global database connection reference
     *
     * @access public
     * @static
     * @global object
     * @return object
     */
    public static function &get() {
        global $_DB_CONNECTION;

        if ($_DB_CONNECTION == null) {
            die('record database connection not set!');
        }
        return $_DB_CONNECTION;
    }

    /**
     * Define the sql parameter place holder.
     * Default is '?'.
     *
     * @access private
     */
    private function _getParamHolder() {
        return '?';
    }

    /**
     * Rebuild sql that contains parameter holders
     *
     * @access private
     * @param string $sql The input SQL string
     * @param array &$params Parameters used for replacing place holders in the SQL
     * @return string
     */
    private function _rebuildSql($sql, &$params) {
        if (!$params) {
            return $sql;
        } else {
            $sql_part = explode($this->_getParamHolder(), $sql);
            $sql = $sql_part[0];
            for ($i = 1; $i < sizeof($sql_part); $i++) {
                $sql .= "'"
                    .mysqli_real_escape_string($this->_db_ref, $params[$i - 1])
                    ."'".$sql_part[$i];
            }
            return $sql;
        }
    }

    /**
     * Query data or execute updates
     *
     * @access public
     * @param string $sql The input SQL string
     * @param array $params Parameters used for replacing place holders in the SQL
     * @return mixed
     */
    public function &query($sql, $params = false)
    {
        $error = '';

        $sql = $this->_rebuildSql($sql, $params);
        if ($this->debug === true) {
            echo $sql."\n========\n";
        }
        
        if(Memorycache::$clear_flag != true) Memorycache::UpdateMemTable($sql);
        if(!mysqli_ping($this->_db_ref)){
			mysqli_close($this->_db_ref);
			$this->__construct(Config::$db_host,Config::$db_user,Config::$db_pass,Config::$db_name);
		}
        $rs = @mysqli_query($this->_db_ref, $sql);
        if (!$rs) {
            if ($this->debug === true) {
                $error = 'error '.mysqli_errno($this->_db_ref).': '
                    .mysqli_error($this->_db_ref)."\n";
            }
            $error .= 'sql execution failed!'."\n";
            throw new MysqlException($error);
        }

        if (is_object($rs)) {
            $mysql_rs = new MysqlRecordset($rs);
            Memorycache::$clear_flag = false;
            return $mysql_rs;
        } else {
        	Memorycache::$clear_flag = false;
            return $rs;
        }
    }

    /**
     * Get last inserted id
     *
     * @access public
     * @return int
     */
    public function getInsertId() {
        return @mysqli_insert_id($this->_db_ref);
    }

    /**
     * Close connection
     *
     * @access public
     */
    public function close() {
        @mysqli_close($this->_db_ref);
    }
}

/**
 * MySQL data recordset wrapper
 *
 * @package mysqli
 */
class MysqlRecordset {
    /**
     * The data resource
     *
     * @access private
     * @var object
     */
    private $_rs;

    /**
     * Recordset constructor
     *
     * @param object &$rs The recordset from query
     */
    public function __construct(&$rs) {
        $this->_rs = $rs;
    }

    /**
     * Fetch data resource as array
     *
     * @access public
     * @param const $rs_type The result type
     * @return array
     */
    public function &fetchRows($rs_type = RSTYPE_ASSOC) {
        $rows = array();
        while ($row = @mysqli_fetch_array($this->_rs, $rs_type)) {
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * Fetch first record from current cursor as array
     *
     * @access public
     * @param const $rs_type The result type
     * @return array
     */
    public function &fetchRow($rs_type = RSTYPE_ASSOC) {
        $row = @mysqli_fetch_array($this->_rs, $rs_type);
        return $row;
    }

    /**
     * Fetch data resource as objects
     *
     * @access public
     * @param string $class_name The class name of record object in the return array
     * @param array $params Parameters passed to the contructor of record object
     * @return array
     */
    public function &fetchObjects($class_name = false, $params = false) {
        $objects = array();
        if (!$class_name) {
            while ($object = @mysqli_fetch_object($this->_rs)) {
                $objects[] = $object;
            }
        } else {
            if (!$params) {
                while ($object = @mysqli_fetch_object($this->_rs,
                    $class_name)) {
                    $objects[] = $object;
                }
            } else {
                while ($object = @mysqli_fetch_object($this->_rs,
                    $class_name, $params)) {
                    $objects[] = $object;
                }
            }
        }
        return $objects;
    }

    /**
     * Fetch first record from current cursor as object
     *
     * @access public
     * @param string $class_name The class name of record object to be returned
     * @param array $params Parameters passed to the contructor of record object
     * @return object
     */
    public function &fetchObject($class_name = false, $params = false) {
        if (!$class_name) {
            $object = @mysqli_fetch_object($this->_rs);
        } else {
            if (!$params) {
                $object = @mysqli_fetch_object($this->_rs, $class_name);
            } else {
                $object = @mysqli_fetch_object($this->_rs,
                    $class_name, $params);
            }
        }
        return $object;
    }

    /**
     * Get record number
     *
     * @access public
     * @return int
     */
    public function getRecordNum() {
        return @mysqli_num_rows($this->_rs);
    }

    /**
     * Free data resource
     *
     * @access public
     */
    public function free() {
        @mysqli_free_result($this->_rs);
    }

    /**
     * Reset the cursor to the begining of resource
     *
     * @access public
     */
    public function reset() {
        @mysqli_data_seek($this->_rs, 0);
    }
}

/**
 * Exception class for handling MySQL query errors
 *
 * @package mysqli
 */
class MysqlException extends Exception {
}
?>