<?php
namespace Everyman\Plumber\Builtin;

use Everyman\Plumber\Pipe\TransformPipe;

/**
 * Pipe that returns a value or array of values picked out of the input array or object
 */
class Pick extends TransformPipe
{
	protected $singlePick = false;
	protected $pickKey;

	public function __construct($pickKey)
	{
		if (!is_array($pickKey)) {
			$this->singlePick = true;
		}
		$this->pickKey = $pickKey;

		parent::__construct(array($this, 'pick'));
	}

	public function pick($value, $key)
	{
		if (is_object($value)) {
			return $this->handleObject($value);
		} else if (is_array($value)) {
			return $this->handleArray($value);
		}
		return null;
	}

	protected function handleObject($value)
	{
		$picked = $this->handleArray((array)$value);
		return $picked;
	}

	protected function handleArray($value)
	{
		if ($this->singlePick) {
			return $value[$this->pickKey];
		}

		$picked = array();
		foreach ($this->pickKey as $pickKey) {
			$picked[$pickKey] = $value[$pickKey];
		}
		return $picked;
	}
}
