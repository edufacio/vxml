<?php
abstract class DbStorage
{

	/**
	 * @var PDO
	 */
	private $db = null;

	/**
	 * @return array with the columns of the table
	 */
	abstract protected function getColumnsName();

	/**
	 * @return String the class in order to encapsualte a row
	 */
	abstract protected function getStorageObjectClass();

	/**
	 * @return String
	 */
	abstract protected function getTableName();

	/**
	 * @return array with the primary keys
	 */
	abstract protected function getPrimaryKeyColumns();

	private function connect()
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

	/**
	 * Execute a custom query
	 *
	 * @param $fields
	 * @param $where
	 * @param $params
	 *
	 * @return array(array($Row1Field1 => value1, $Row1Field1...),array($Row1Field2 => value1, $Row1Field2...)...)
	 */
	public function selectQuery($fields, $where, $params)
	{
		$queryStr = "Select $fields from " . $this->getTableName() . " $where";
		return $this->query($queryStr, $params);
	}

	/**
	 * Execute a delete
	 * @param $where
	 * @param $params
	 */
	public function deleteQuery($where, $params)
	{
		$queryStr = "delete from " . $this->getTableName() . " $where";
		$this->query($queryStr, $params);
	}

	/**
	 * @param $where
	 * @param $params
	 *
	 * @return StorageObject[]
	 */
	public function get($where, $params)
	{
		$records = $this->selectQuery('*', $where, $params);
		$storageObjects = array();
		foreach ($records as $storageRecord) {
			$preparedRecord = array_intersect_key($storageRecord, array_flip($this->getColumnsName()));
			$storageObjects[] = Injector::callStatic($this->getStorageObjectClass(), 'createFromRecord', $preparedRecord);
		}
		return $storageObjects;
	}

	/**
	 * Save or update and StorageObject
	 * @param StorageObject $storageObject
	 * @Return StorageObject
	 */
	public function save(StorageObject $storageObject)
	{
		$rawRecord = $storageObject->getRawRecord();
		$columns = implode(',', array_keys($rawRecord));
		$placeHolders = implode(',', array_fill(0, count($rawRecord), '?'));
		$tableName = $this->getTableName();
		$query = "Insert into $tableName($columns) values($placeHolders)";
		try {
			$this->query($query, array_values($rawRecord));
			return $this->find($storageObject->getRawRecord());
		} catch (PDOException $e) {
			return $this->update($storageObject);
		}

	}

	/**
	 * @param StorageObject $storageObject
	 *
	 * @return mixed|null
	 */
	public function update(StorageObject $storageObject)
	{
		$whereParams = $whereStatement =  $setParams = $setStatement = array();
		$rawRecord = $storageObject->getRawRecord();
		$pks = $this->getPrimaryKeyColumns();
		foreach ($rawRecord as $column => $value) {
			if (in_array($column, $pks)) {
				$whereParams[] = $value;
				$whereStatement[] = "$column = ?";
			}
			$setParams[] = $value;
			$setStatement[] = "$column = ?";
		}

		$params = array_merge($setParams, $whereParams);
		$setStatementStr = implode(',', $setStatement);
		$whereStatementStr = implode('and', $whereStatement);
		$tableName = $this->getTableName();
		$query = "update $tableName set $setStatementStr where $whereStatementStr";
		$this->query($query, $params);
		return $this->find($storageObject->getRawRecord());
	}

	/**
	 * find a storageObject by his primaykey;
	 * @param array $keys array(PKField1 => PKValue1, PKField2 => PKValue2)
	 *
	 * @return StorageObject | null
	 */
	public function find(array $keys, $default = null) {
		$columns = $this->getPrimaryKeyColumns();
		$params = array();
		$where = array();
		foreach ($columns as $pkColumn) {
			if (!isset($keys[$pkColumn])) {
				return null;
			}
			$params[] = $keys[$pkColumn];
			$where[] = "$pkColumn = ?";
		}

		$result =  array_shift($this->get("where " . implode('and', $where), $params));
		return $result !== null ? $result : $default;
	}

	/**
	 * Delete a storageObject
	 * @param StorageObject $storageObject
	 */
	public function delete(StorageObject $storageObject)
	{
		$whereParams = $whereStatement = array();
		$rawRecord = $storageObject->getRawRecord();
		$pks = $this->getPrimaryKeyColumns();
		foreach ($rawRecord as $column => $value) {
			if (in_array($column, $pks)) {
				$whereParams[] = $value;
				$whereStatement[] = "$column = ?";
			}
		}

		$where = 'where ' . implode('and', $whereStatement);
		$this->deleteQuery($where, $whereParams);
	}
}