<?php

namespace MattKirwan\Module;

use Via\QueryBuilder\QueryBuilderImp;

class ModuleCrud extends QueryBuilderImp
{
	protected $conn = null;

	public function __construct(\Doctrine\DBAL\Connection $conn)
	{
		$this->conn = $conn;		
	}

	public function setSelectFields($selectFields)
	{
		$this->selectFields = $selectFields;
	}

	public function setTables($tables)
	{
		$this->tables = $tables;
	}

	public function setSortOrder($sortOrder)
	{
		$this->sortOrder = $sortOrder;
	}

	public function setUpdateId($id)
	{
		$this->updateId = $id;
	}

	public function setUpdateData($data)
	{
		$this->updateData = $data;
	}

	public function setInsertData($data)
	{
		$this->insertData = $data;
	}

	public function getAll()
	{
		try
		{
			$sql = parent::buildAllQuery();

			return $this->conn->fetchAll($sql);
		}
		catch(\Exception $e)
		{	
			echo $e->getMessage();
		}		
	}

	public function getAllFlagged($flag)
	{
		try
		{
			$sql = parent::buildAllFlaggedQuery($flag);

			return $this->conn->fetchAll($sql);
		}
		catch(\Exception $e)
		{	
			echo $e->getMessage();
		}		
	}

	public function getSingleById($id)
	{
		try
		{
			$sql = parent::buildSingleByIdQuery();

			return $this->conn->fetchAssoc($sql, array($id));
		}
		catch(\Exception $e)
		{	
			echo $e->getMessage();
		}		
	}

	public function getMultipleByIds($ids)
	{
		try
		{
			$sql = parent::buildMultipleByIdsQuery();

			$statement = $this->conn->executeQuery(
				$sql,
				array($ids),
				array(\Doctrine\DBAL\Connection::PARAM_INT_ARRAY)
			);

			return $statement->fetchAll();
		}
		catch(\Exception $e)
		{	
			echo $e->getMessage();
		}		
	}

	public function updateSingleById($id, $cleanInputData)
	{
		try
		{
			$this->conn->update($this->tables, $this->escapeInput($cleanInputData), array('id' => $id));
		}
		catch(\Exception $e)
		{
			echo $e->getMessage();
		}
	}

	public function insertSingle($cleanInputData)
	{
		try
		{
			$this->conn->insert($this->tables, $this->escapeInput($cleanInputData));
			return $this->conn->lastInsertId();
		}
		catch (\Exception $e)
		{
			echo $e->getMessage();
		}
	}

	public function deleteSingleById($id)
	{
		try
		{
			$this->conn->delete($this->tables, array('id' => $id));
			return true;
		}
		catch (\Exception $e)
		{
			return false;
		}
	}

	public function getDropdown($dropdownValue, $dropdownKey)
	{
		try
		{
			$this->setSelectFields($dropdownValue.', '.$dropdownKey);

			$sql = parent::buildAllQuery();

			foreach($this->conn->fetchAll($sql) as $dropdownData)
			{
				$dropdown[$dropdownData[$dropdownKey]] = $dropdownData[$dropdownValue];
			}

			return $dropdown;
		}
		catch (\Exception $e)
		{
			echo $e->getMessage();
		}
	}

	public function verifySanitization(Array $cleanInput)
	{
		foreach($cleanInput as $filterResult)
		{
			if(false === $filterResult)
			{
				return false;
			}
		}

		return true;		
	}

	public function escapeInput($cleanInputData)
	{
		$iter = new \ArrayIterator($cleanInputData);
		
		foreach($iter as $field => $value)
		{
			$iter[$field] = filter_var($value, FILTER_SANITIZE_MAGIC_QUOTES);
		}

		return $iter->getArrayCopy();
	}
}