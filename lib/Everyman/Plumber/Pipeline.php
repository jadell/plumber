<?php
namespace Everyman\Plumber;

use Everyman\Plumber\Pipe,
    Everyman\Plumber\Pipe\IdentityPipe,
    Iterator;

/**
 * Composes multiple pipes together in a processing chain
 */
class Pipeline extends Pipe
{
	protected $end;

	/**
	 * Set multiple pipes
	 *
	 * @param Pipe $pipe1
	 * ...
	 * @param Pipe $pipeN
	 */
	public function __construct()
	{
		$pipeList = func_get_args();
		if (count($pipeList) < 1) {
			$pipeList = array(new IdentityPipe());
		}

		foreach ($pipeList as $pipe) {
			$this->appendPipe($pipe);
		}
	}

	/**
	 * Append another pipe onto the end of this pipeline
	 *
	 * Appended pipes are processed after any pipes before them.
	 *
	 * @param Pipe $pipe
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
