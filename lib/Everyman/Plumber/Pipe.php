<?php
namespace Everyman\Plumber;

use Iterator,
    ArrayIterator;

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
	 * Provides an abbreviated syntax for setting the internal Iterator
	 *
	 * @param mixed $starts an Iterator or array
	 * @return Pipeline
	 */
	public function __invoke($starts=null)
	{
		if (is_array($starts)) {
			$starts = new ArrayIterator($starts);
		}
		if ($starts) {
			$this->setStarts($starts);
		}
		return $this;
	}

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
	 * Loop through the entire iterator and return the result
	 *
	 * @return array
	 */
	public function drain()
	{
		$result = array();
		foreach ($this as $key => $value) {
			$result[$key] = $value;
		}
		return $result;
	}

	/**
	 * Rewind the starts iterator
	 */
	public function rewind()
	{
		$this->reset();
		$this->starts->rewind();
	}

	/**
	 * Reset any internal state the pipe has
	 *
	 * Occurs immediately before a rewind.
	 */
	protected function reset()
	{
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
