<?php
namespace Everyman\Plumber\Builtin;

use Everyman\Plumber\Pipe\FilterPipe;

/**
 * Pipe that returns only elements that have not yet been processed
 */
class Unique extends FilterPipe
{
	protected $seen = array();

	public function __construct()
	{
		parent::__construct(array($this, 'unique'));
	}

	public function unique($value, $key)
	{
		if (in_array($value, $this->seen)) {
			return false;
		}
		$this->seen[] = $value;
		return true;
	}
}
