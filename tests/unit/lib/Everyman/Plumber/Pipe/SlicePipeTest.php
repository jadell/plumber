<?php
use Everyman\Plumber\Pipe\SlicePipe;

class SlicePipeTest extends PipeTestCase
{
	public function testNoEnpoints_ReturnsAllElements()
	{
		$init = array('foo', 'bar', 'baz', 'qux', 'lorem', 'ipsum');

		$pipe = new SlicePipe();
		$pipe->setStarts(new \ArrayIterator($init));

		self::assertIteratorEquals($init, $pipe);
	}

	public function testOffsetGiven_ReturnsAllElementsFromOffsetToEnd()
	{
		$init = array('foo', 'bar', 'baz', 'qux', 'lorem', 'ipsum');
		$expected = array(2=>'baz', 3=>'qux', 4=>'lorem', 5=>'ipsum');

		$pipe = new SlicePipe(2);
		$pipe->setStarts(new \ArrayIterator($init));

		self::assertIteratorEquals($expected, $pipe);
	}

	public function testIteratedMoreThanOnce_ReturnsAllElementsFromOffsetToEnd()
	{
		$init = array('foo', 'bar', 'baz', 'qux', 'lorem', 'ipsum');
		$expected = array(2=>'baz', 3=>'qux', 4=>'lorem', 5=>'ipsum');

		$pipe = new SlicePipe(2);
		$pipe->setStarts(new \ArrayIterator($init));

		self::assertIteratorEquals($expected, $pipe);
		self::assertIteratorEquals($expected, $pipe);
	}

	public function testLengthGiven_ReturnsLengthNumberOfElementsFromBeginning()
	{
		$init = array('foo', 'bar', 'baz', 'qux', 'lorem', 'ipsum');
		$expected = array('foo', 'bar', 'baz');

		$pipe = new SlicePipe(0, 3);
		$pipe->setStarts(new \ArrayIterator($init));

		self::assertIteratorEquals($expected, $pipe);
	}

	public function testOffsetAndLengthGiven_ReturnsLengthNumberOfElementsFromOffset()
	{
		$init = array('foo', 'bar', 'baz', 'qux', 'lorem', 'ipsum');
		$expected = array(2=>'baz', 3=>'qux', 4=>'lorem');

		$pipe = new SlicePipe(2, 3);
		$pipe->setStarts(new \ArrayIterator($init));

		self::assertIteratorEquals($expected, $pipe);
	}

	public function testOffsetGreaterThanSize_ReturnsNoElements()
	{
		$init = array('foo', 'bar', 'baz', 'qux', 'lorem', 'ipsum');
		$expected = array();

		$pipe = new SlicePipe(count($init));
		$pipe->setStarts(new \ArrayIterator($init));

		self::assertIteratorEquals($expected, $pipe);
	}

	public function testLengthGreaterThanSize_ReturnsAllElementsFromOffsetToEnd()
	{
		$init = array('foo', 'bar', 'baz', 'qux', 'lorem', 'ipsum');
		$expected = array(2=>'baz', 3=>'qux', 4=>'lorem', 5=>'ipsum');

		$pipe = new SlicePipe(2, 5);
		$pipe->setStarts(new \ArrayIterator($init));

		self::assertIteratorEquals($expected, $pipe);
	}

	public function testAssociativeKeys_ReturnsElements()
	{
		$init = array('foo'=>'off', 'bar'=>'rab', 'baz'=>'zab', 'qux'=>'xuq');
		$expected = array('bar'=>'rab', 'baz'=>'zab');

		$pipe = new SlicePipe(1, 2);
		$pipe->setStarts(new \ArrayIterator($init));

		self::assertIteratorEquals($expected, $pipe);
	}
}
