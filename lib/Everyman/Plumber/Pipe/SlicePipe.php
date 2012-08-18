<?php
namespace Everyman\Plumber\Pipe;

use Everyman\Plumber\Pipe\FilterPipe;

/**
 * Pipe that only returns elements after a certain number of previous elements
 * have been seen, and stops after a certain number of elements have been returned.
 */
class SlicePipe extends FilterPipe
{
	protected $offset;
	protected $length;
	protected $at = 0;
	protected $emitted = 0;

	public function __construct($offset=null, $length=null)
	{
		$this->offset = $offset;
		$this->length = $length;

		parent::__construct(array($this, 'slice'));
	}

	public function slice($value, $key)
	{
		$this->at++;
		$pastOffset = ($this->offset === null || $this->offset < $this->at);
		$beforeLength = ($this->length === null || $this->emitted < $this->length);
		if ($pastOffset && $beforeLength) {
			$this->emitted++;
			return true;
		}
		return false;
	}
}
