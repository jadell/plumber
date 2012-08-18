<?php
namespace Everyman\Plumber\Pipe;

use Everyman\Plumber\Pipe,
    InvalidArgumentException;

/**
 * Pipe that runs every element of the starting Iterator through a transformation callback
 *
 * If no callback is given, the pipe acts like an Identity pipe, returning exactly the
 * values in the Iterator that was given to it.
 */
class TransformPipe extends Pipe
{
	/**
	 * Callback to transform each element in the starts Iterator
	 * @var callable
	 */
	protected $transform;

	/**
	 * Set the transformation callback and make sure it is callable
	 *
	 * @param callable $transform    callback with signature: function ($value, $key)
	 * @throws InvalidArgumentException
	 */
	public function __construct($transform=null)
	{
		if (!$transform) {
			$transform = function ($value, $key) {
				return $value;
			};
		}

		if (!is_callable($transform)) {
			throw new InvalidArgumentException('TransformPipe must be given a callable argument.');
		}

		$this->transform = $transform;
	}

	/**
	 * Get the current value of the starts iterator, transformed by the $transform callback
	 *
	 * @return mixed
	 */
	public function current()
	{
		$transform = $this->transform;
		return $transform(parent::current(), parent::key());
	}
}
