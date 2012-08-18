<?php
namespace Everyman\Plumber\Pipe;

use Everyman\Plumber\Pipe,
    InvalidArgumentException;

/**
 * Pipe that returns only elements in the starting Iterator that match the filter callback
 *
 * If no callback is given, only truthy values will be returned.
 */
class FilterPipe extends Pipe
{
	/**
	 * Callback to filter each element in the starts Iterator
	 * @var callable
	 */
	protected $filter;

	/**
	 * Set the filter callback and make sure it is callable
	 *
	 * @param callable $filter    callback with signature: function ($value, $key)
	 * @throws InvalidArgumentException
	 */
	public function __construct($filter=null)
	{
		if (!$filter) {
			$filter = function ($value, $key) {
				return (boolean)$value;
			};
		}

		if (!is_callable($filter)) {
			throw new InvalidArgumentException('FilterPipe must be given a callable argument.');
		}

		$this->filter = $filter;
	}

	/**
	 * Rewind the starts iterator
	 *
	 * After rewinding, fast-forward to the first matching element
	 */
	public function rewind()
	{
		parent::rewind();
		$this->advance();
	}

	/**
	 * Advance the internal position of the starts iterator
	 *
	 * Continue advancing until the next matching element
	 */
	public function next()
	{
		parent::next();
		$this->advance();
	}

	/**
	 * Fast-forward until a matching value is found
	 */
	protected function advance()
	{
		while (parent::valid() && !call_user_func($this->filter, parent::current(), parent::key())) {
			parent::next();
		}
	}
}
