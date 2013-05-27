<?php

namespace Via\Module;

interface ModuleInterface
{
	public function setSelectFields($fields);
	public function setTables($tables);
	public function setSortOrder($sortOrder);
	public function loadAll();
	public function loadFlagged();
	public function loadSingleById($id);
	public function loadMultipleByIds(Array $ids);
	public function saveSingleById($id, Array $cleanInputData);
	public function insertSingle(Array $cleanInputData);
	public function deleteSingleById($id);
	public function loadDropdown($dropdownKey, $dropdownValue);
	public function sanitizeRequest($formName);
	public function prepareOutput(Array $rawOutput);
}