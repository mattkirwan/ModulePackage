<?php

namespace MattKirwan\Module;

use Symfony\Component\HttpFoundation\Request;

class Module
{
	protected $model = null;

	protected $selectFields = '*';
	protected $tables;
	protected $sortOrder = 'ORDER BY id ASC';

	protected $cleanInput;

	public function __construct($model)
	{
		$this->model = $model;
	}

	public function setSelectFields($selectFields = null)
	{
		if(null !== $selectFields)
		{
			$this->model->setSelectFields($selectFields);
		}
	}

	public function setTables($tables = null)
	{
		if(null !== $tables)
		{
			$this->model->setTables($tables);
		}
	}

	public function setSortOrder($sortOrder = null)
	{
		if(null !== $sortOrder)
		{
			$this->model->setSortOrder($sortOrder);
		}
	}

	public function setUpdateId($id = 0)
	{
		$this->model->setUpdateId($id);
	}

	public function setUpdateData(Array $data = null)
	{
		if(null !== $data)
		{
			$this->model->setUpdateData($data = null);
		}
	}

	public function setInsertData(Array $data = null)
	{
		if(null !== $data)
		{
			$this->model->setInsertData($data);
		}
	}

	public function getCleanInput()
	{
		return $this->cleanInput;
	}

	public function loadAll()
	{
		return $this->model->getAll();
	}

	public function loadFlagged($flag = null)
	{
		return $this->model->getAllFlagged($flag);
	}

	public function loadSingleById($id = 0)
	{
		return $this->model->getSingleById($id);
	}

	public function loadMultipleByIds(Array $ids)
	{
		return $this->model->getMultipleByIds($ids);
	}

	public function saveSingleById($id, Array $cleanInputData)
	{
		$this->model->updateSingleById($id, $cleanInputData);
	}

	public function insertSingle(Array $cleanInputData)
	{
		return $this->model->insertSingle($cleanInputData);
	}

	public function deleteSingleById($id)
	{
		return $this->model->deleteSingleById($id);
	}

	public function loadDropdown($dropdownValue, $dropdownKey = 'id')
	{
		return $this->model->getDropdown($dropdownValue, $dropdownKey);
	}

	public function sanitizeRequest($formName)
	{
		$request = Request::createFromGlobals();

		$dirty = $request->request->get($formName);

		$this->cleanInput = $this->model->sanitizeInput($dirty);

		unset($dirty);

		return $this->model->verifySanitization($this->cleanInput);
	}

	public function prepareOutput(Array $rawOutput)
	{
		$rawIter = new \RecursiveArrayIterator($rawOutput);

		while($rawIter->valid())
		{
			if($rawIter->hasChildren())
			{
				$rawChildIter = $rawIter->getChildren();
				
				while($rawChildIter->valid())
				{
					$rawIter[$rawIter->key()][$rawChildIter->key()] = $this->model->prepareFieldForOutput($rawChildIter->key(), $rawChildIter->current());
					$rawChildIter->next();
				}
			}
			else
			{
				$rawIter[$rawIter->key()] = $this->model->prepareFieldForOutput($rawIter->key(), $rawIter->current());
			}

			$rawIter->next();
		}

		return $rawIter->getArrayCopy();
	}	
}