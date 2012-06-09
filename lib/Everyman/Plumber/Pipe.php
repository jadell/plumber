<?php
namespace Everyman\Plumber;

use Iterator;

/**
 * Base class for all pipes
 */
abstract class Pipe implements Iterator
{
	/**
	 * The iterator which generates the initial values
	 * @var Iterator
	 */
	protected $starts;

	/**
	 * Set the Iterator which generates the values to transform
	 *
	 * @param Iterator $starts
	 */
	public function setStarts(Iterator $starts)
	{
		$this->starts = $starts;
	}

	/**
	 * Rewind the starts iterator
	 */
	public function rewind()
	{
		$this->starts->rewind();
	}

	/**
	 * Get the current value of the starts iterator
	 *
	 * @return mixed
	 */
	public function current()
	{
		return $this->starts->current();
	}

	/**
	 * Get the current key of the starts iterator
	 *
	 * @return mixed
	 */
	public function key()
	{
		return $this->starts->key();
	}

	/**
	 * Advance the internal position of the starts iterator
	 */
	public function next()
	{
		$this->starts->next();
	}

	/**
	 * Are there more elements in the starts iterator?
	 *
	 * Return true if there is a current value in the starts iterator,
	 * false otherwise.
	 *
	 * @return boolean
	 */
	public function valid()
	{
		return $this->starts->valid();
	}
}
