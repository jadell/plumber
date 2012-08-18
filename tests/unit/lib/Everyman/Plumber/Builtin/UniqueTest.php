<?php
use Everyman\Plumber\Builtin\Unique;

class UniqueTest extends PipeTestCase
{
	public function testAllUniques_ReturnsAllElements()
	{
		$init = array('foo', 'bar', 'baz', 'qux');

		$pipe = new Unique();
		$pipe->setStarts(new \ArrayIterator($init));

		self::assertIteratorEquals($init, $pipe);
	}

	public function testSomeUniques_ReturnsAllElements()
	{
		$init = array('foo', 'bar', 'foo', 'baz', 'qux', 'qux');
		$expected = array(0=>'foo', 1=>'bar', 3=>'baz', 4=>'qux');

		$pipe = new Unique();
		$pipe->setStarts(new \ArrayIterator($init));

		self::assertIteratorEquals($expected, $pipe);
	}
}
