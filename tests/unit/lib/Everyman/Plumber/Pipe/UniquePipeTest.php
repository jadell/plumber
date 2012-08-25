<?php
use Everyman\Plumber\Pipe\UniquePipe;

class UniquePipeTest extends PipeTestCase
{
	public function testAllUniques_ReturnsAllElements()
	{
		$init = array('foo', 'bar', 'baz', 'qux');

		$pipe = new UniquePipe();

		self::assertIteratorEquals($init, $pipe($init));
	}

	public function testSomeUniques_ReturnsUniqueElements()
	{
		$init = array('foo', 'bar', 'foo', 'baz', 'qux', 'qux');
		$expected = array(0=>'foo', 1=>'bar', 3=>'baz', 4=>'qux');

		$pipe = new UniquePipe();

		self::assertIteratorEquals($expected, $pipe($init));
	}

	public function testIteratedMoreThanOnce_ReturnsUniqueElements()
	{
		$init = array('foo', 'bar', 'foo', 'baz', 'qux', 'qux');
		$expected = array(0=>'foo', 1=>'bar', 3=>'baz', 4=>'qux');

		$pipe = new UniquePipe();

		self::assertIteratorEquals($expected, $pipe($init));
		self::assertIteratorEquals($expected, $pipe);
	}
}
