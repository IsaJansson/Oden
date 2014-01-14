<?php
/**
* Database wrapper who provides a database API for the framework but hides the details of 
* implementation
* @package OdenCore
*/

class CMDatabase {
	private $db = null;
	private $stmt = null;
	private static $numQueries = 0;
	private static $queries = array();

  public function __construct($dsn, $username = null, $password = null, $driver_options = null) {
    $this->db = new PDO($dsn, $username, $password, $driver_options);
    $this->db->SetAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	// Set an attribute on the database
	public function SetAttribute($attribute, $value) {
		return $this->db->setAttribute($attribute, $value);
	}

	// Getters
	public function GetNumQueries() {return self::$numQueries;}
	public function GetQueries() {return self::$queries;}

	// Exevute a select-query with arguments and return the result.
	public function ExecuteSelectQueryAndfetchAll($query, $params = array()) {
		$this->stmt = $this->db->prepare($query);
	    self::$queries[] = $query; 
	    self::$numQueries++;
	    $this->stmt->execute($params);
	    return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function ExecuteSelectQuery($query, $params=array()){
	    $this->stmt = $this->db->prepare($query);
	    self::$queries[] = $query; 
	    self::$numQueries++;
	    $this->stmt->execute($params);
	    return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

	// Execute a SQL-query and ignore the result.
	public function ExecuteQuery($query, $params = array()) {
		$this->stmt = $this->db->prepare($query);
	    self::$queries[] = $query; 
	    self::$numQueries++;
	    return $this->stmt->execute($params);
	} 

	// Return last inserted id
	public function LastInsertId() {
		return $this->db->lastinsertid();
	}

	// Return rows affected of last INSERT, UPDATE or DELETE
	public function RowCount() {
		return is_null($this->stmt) ? $this->stmt : $this->stmt->rowCount();
	}

}