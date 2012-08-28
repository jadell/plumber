<?php
namespace Everyman\Plumber\Pipe;

use Everyman\Plumber\Pipe\TransformPipe,
    InvalidArgumentException;

/**
 * If the first callback returns a truthy value, emit the value of the second callback.
 * Else, emit the value of the third callback.
 * If a third callback is not given, the value is emitted as-is.
 */
class IfElsePipe extends TransformPipe
{
	protected $if;
	protected $then;
	protected $else;

	public function __construct($if, $then, $else=null)
	{
		if (!$else) {
			$else = array($this, 'defaultElse');
		}

		if (!is_callable($if) || !is_callable($then) || !is_callable($else)) {
			throw new InvalidArgumentException('IfElsePipe must be given callable arguments.');
		}

		$this->if = $if;
		$this->then = $then;
		$this->else = $else;

		parent::__construct(array($this, 'ifElse'));
	}

	public function ifElse($value, $key)
	{
		if (call_user_func($this->if, $value, $key)) {
			return call_user_func($this->then, $value, $key);
		}
		return call_user_func($this->else, $value, $key);
	}

	public function defaultElse($value, $key)
	{
		return $value;
	}
}
