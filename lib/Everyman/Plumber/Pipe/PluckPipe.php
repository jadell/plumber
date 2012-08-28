<?php
namespace Everyman\Plumber\Pipe;

use Everyman\Plumber\Pipe\TransformPipe;

/**
 * Pipe that returns a value or array of values plucked out of the input array or object
 */
class PluckPipe extends TransformPipe
{
	protected $singlePluck = false;
	protected $pluckKey;

	public function __construct($pluckKey)
	{
		if (!is_array($pluckKey)) {
			$this->singlePluck = true;
		}
		$this->pluckKey = $pluckKey;

		parent::__construct(array($this, 'pluck'));
	}

	public function pluck($value, $key)
	{
		if (is_object($value)) {
			$value = (array)$value;
		}

		if (!is_array($value)) {
			return null;
		}

		if ($this->singlePluck) {
			return array_key_exists($this->pluckKey, $value) ? $value[$this->pluckKey] : null;
		}

		$plucked = array();
		foreach ($this->pluckKey as $pluckKey) {
			$plucked[$pluckKey] = array_key_exists($pluckKey, $value) ? $value[$pluckKey] : null;
		}
		return $plucked;
	}
}
