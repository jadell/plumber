<?php
namespace Everyman\Plumber;

use Everyman\Plumber\Pipe,
    Everyman\Plumber\Pipe\TransformPipe,
    ReflectionClass,
    ArrayIterator,
    Iterator;

/**
 * Composes multiple pipes together in a processing chain
 */
class Pipeline extends Pipe
{
	protected $end;

	/**
	 * Create the pipeline and set up the head of the pipeline
	 *
	 * @param mixed $starts an Iterator or array
	 */
	public function __construct($starts=null)
	{
		$this->appendPipe(new TransformPipe());

		if (is_array($starts)) {
			$starts = new ArrayIterator($starts);
		}
		if ($starts) {
			$this->setStarts($starts);
		}
	}

	/**
	 * Automatically append pipes by chaining calls
	 */
	public function __call($method, $args)
	{
		$fqcName = '\Everyman\Plumber\Pipe\\'.ucfirst($method).'Pipe';
		if (!isset($this->refl[$fqcName])) {
			$this->refl[$fqcName] = new ReflectionClass($fqcName);
		}
		$refl = $this->refl[$fqcName];
		$pipe = $refl->newInstanceArgs($args);
		$this->appendPipe($pipe);
		return $this;
	}

	/**
	 * Append another pipe onto the end of this pipeline
	 *
	 * Appended pipes are processed after any pipes before them.
	 *
	 * @param Pipe $pipe
	 * @return Pipeline
	 */
	public function appendPipe(Pipe $pipe)
	{
		if (!$this->end) {
			$this->end =
			$this->starts = $pipe;
		} else {
			$pipe->setStarts($this->starts);
			$this->starts = $pipe;
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
		$this->end->setStarts($starts);
	}
}
