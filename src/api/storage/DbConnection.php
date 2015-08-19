<?php
class DbConnection
{
	private static $instance;
	private  $db;

	/**
	 * @return FilmPreferencesStorage
	 */
	public static function getInstance() {
		if (self::$instance === NULL) {
			self::$instance = Injector::get(__CLASS__);
			self::$instance->connect();
		}
		return self::$instance;
	}

	public function connect()
	{
		if ($this->db === null) {
			$dbConfig = DbConfig::create()->getDbConfig();
			$dsn = 'mysql:dbname=' . $dbConfig[DbConfig::DB_NAME] . ';host=' . $dbConfig[DbConfig::HOST];
			$this->db = new PDO($dsn, $dbConfig[DbConfig::USER], $dbConfig[DbConfig::PASSWD]);
		}
	}

	/**
	 * Execute a query
	 * @param $queryStr
	 * @param array $params
	 *
	 * @return array
	 * @throws PDOException
	 */
	public function query($queryStr, $params = array())
	{
		$this->connect();
		$query = $this->db->prepare($queryStr);
		/* @var $query PDOStatement */
		if ($query->execute($params)) {
			return $query->fetchAll();
		} else {
			throw new PDOException("ERROR on query " . var_export(func_get_args(),true));
		}
	}
}