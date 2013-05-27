<?php

namespace MattKirwan\Module;

interface ModuleModelInterface
{
	public function sanitizeInput(Array $dirtyInput);
	public function prepareFieldForOutput($field, $value);
}