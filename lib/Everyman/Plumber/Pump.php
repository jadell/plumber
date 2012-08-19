<?php
namespace Everyman\Plumber;

use Iterator,
    ArrayIterator,
    ReflectionClass,
    Everyman\Plumber\Pipeline;

/**
 * Fluent interface for processing pipelines
 */
class Pump implements Iterator
{
	/**
	 * The processing pipeline
	 * @var Pipeline
	 */
	protected $pipeline;

	/**
	 * Reflection classes for use pipe types
	 */
	protected $refl = array();

	public function __construct($starts)
	{
		if (is_array($starts)) {
			$starts = new ArrayIterator($starts);
		}

		$this->pipeline = new Pipeline();
		$this->pipeline->setStarts($starts);
	}

	public function __call($method, $args)
	{
		$fqcName = '\Everyman\Plumber\Pipe\\'.ucfirst($method).'Pipe';
		if (!isset($this->refl[$fqcName])) {
			$this->refl[$fqcName] = new ReflectionClass($fqcName);
		}
		$refl = $this->refl[$fqcName];
		$pipe = $refl->newInstanceArgs($args);
		$this->pipeline->appendPipe($pipe);
		return $this;
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
		echo "rewind\n";
		$this->pipeline->rewind();
	}

	/**
	 * Get the current value of the starts iterator
	 *
	 * @return mixed
	 */
	public function current()
	{
		return $this->pipeline->current();
	}

	/**
	 * Get the current key of the starts iterator
	 *
	 * @return mixed
	 */
	public function key()
	{
		return $this->pipeline->key();
	}

	/**
	 * Advance the internal position of the starts iterator
	 */
	public function next()
	{
		$this->pipeline->next();
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
		return $this->pipeline->valid();
	}
}
