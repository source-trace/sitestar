<?php

if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * Columns are returned into the array having the fieldname as the array index
 * Equals MYSQL_ASSOC
 */
define('RSTYPE_ASSOC', MYSQL_ASSOC);
/**
 * Columns are returned into the array having a numerical index to the fields
 * This index starts with 0, the first field in the result
 * Equals MYSQL_NUM
 */
define('RSTYPE_NUM', MYSQL_NUM);
/**
 * Columns are returned into the array having both a numerical index
 * and the fieldname as the array index
 * Equals MYSQL_BOTH
 */
define('RSTYPE_BOTH', MYSQL_BOTH);

/**
 * The global database connection reference
 *
 * @global object $GLOBALS['_DB_CONNECTION']
 * @name $_DB_CONNECTION
 */
$GLOBALS['_DB_CONNECTION'] = null;

/**
 * The MySQL connection class based on php_mysql extension
 *
 * @package mysql
 */
class MysqlConnection {
    /**
     * MySQL connection resource
     *
     * @access private
     * @var resource
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
    	$db_host .= ":".(Config::$port);
        $this->_db_ref = @mysql_connect($db_host, $db_user, $db_pwd);
        if (!$this->_db_ref) {
            die('database server '.$db_host.' connect error!<br />'
                .mysql_error());
        }

		@mysql_query("SET NAMES ".Config::$mysqli_charset ,  $this->_db_ref);

        $select_db_rs = @mysql_select_db($db_name, $this->_db_ref);
        if (!$select_db_rs) {
            $error = 'error '.mysql_errno($this->_db_ref).': '
                .mysql_error($this->_db_ref);
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
     * @return resource
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
                    .mysql_real_escape_string($params[$i - 1], $this->_db_ref)
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
		if(!mysql_ping($this->_db_ref)){
			mysql_close($this->_db_ref);
			$this->__construct(Config::$db_host,Config::$db_user,Config::$db_pass,Config::$db_name);
		}
        $rs = @mysql_query($sql, $this->_db_ref);
        if (!$rs) {
            if ($this->debug === true) {
                $error = 'error '.mysql_errno($this->_db_ref).': '
                    .mysql_error($this->_db_ref)."\n";
            }
            $error .= 'sql execution failed!'."\n";
//            $error .= 'sql execution failed!'.$sql."\n";
            throw new MysqlException($error);
        }

        if (is_resource($rs)) {
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
        return @mysql_insert_id($this->_db_ref);
    }

    /**
     * Close connection
     *
     * @access public
     */
    public function close() {
        @mysql_close($this->_db_ref);
    }
}

/**
 * MySQL data recordset wrapper
 *
 * @package mysql
 */
class MysqlRecordset {
    /**
     * The data resource
     *
     * @access private
     * @var resource
     */
    private $_rs;

    /**
     * Recordset constructor
     *
     * @param resource &$rs The recordset from query
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
        while ($row = @mysql_fetch_array($this->_rs, $rs_type)) {
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
        $row = @mysql_fetch_array($this->_rs, $rs_type);
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
            while ($object = @mysql_fetch_object($this->_rs)) {
                $objects[] = $object;
            }
        } else {
            if (!$params) {
                while ($object = @mysql_fetch_object($this->_rs,
                    $class_name)) {
                    $objects[] = $object;
                }
            } else {
                while ($object = @mysql_fetch_object($this->_rs,
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
            $object = @mysql_fetch_object($this->_rs);
        } else {
            if (!$params) {
                $object = @mysql_fetch_object($this->_rs, $class_name);
            } else {
                $object = @mysql_fetch_object($this->_rs,
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
        return @mysql_num_rows($this->_rs);
    }

    /**
     * Free data resource
     *
     * @access public
     */
    public function free() {
        @mysql_free_result($this->_rs);
    }

    /**
     * Reset the cursor to the begining of resource
     *
     * @access public
     */
    public function reset() {
        @mysql_data_seek($this->_rs, 0);
    }
}

/**
 * Exception class for handling MySQL query errors
 *
 * @package mysql
 */
class MysqlException extends Exception {
}
?>