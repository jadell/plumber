<?php
use Everyman\Plumber\Pipe\SlicePipe;

class SlicePipeTest extends PipeTestCase
{
	public function testNoEnpoints_ReturnsAllElements()
	{
		$init = array('foo', 'bar', 'baz', 'qux', 'lorem', 'ipsum');

		$pipe = new SlicePipe();

		self::assertIteratorEquals($init, $pipe($init));
	}

	public function testOffsetGiven_ReturnsAllElementsFromOffsetToEnd()
	{
		$init = array('foo', 'bar', 'baz', 'qux', 'lorem', 'ipsum');
		$expected = array(2=>'baz', 3=>'qux', 4=>'lorem', 5=>'ipsum');

		$pipe = new SlicePipe(2);

		self::assertIteratorEquals($expected, $pipe($init));
	}

	public function testIteratedMoreThanOnce_ReturnsAllElementsFromOffsetToEnd()
	{
		$init = array('foo', 'bar', 'baz', 'qux', 'lorem', 'ipsum');
		$expected = array(2=>'baz', 3=>'qux', 4=>'lorem', 5=>'ipsum');

		$pipe = new SlicePipe(2);

		self::assertIteratorEquals($expected, $pipe($init));
		self::assertIteratorEquals($expected, $pipe);
	}

	public function testLengthGiven_ReturnsLengthNumberOfElementsFromBeginning()
	{
		$init = array('foo', 'bar', 'baz', 'qux', 'lorem', 'ipsum');
		$expected = array('foo', 'bar', 'baz');

		$pipe = new SlicePipe(0, 3);

		self::assertIteratorEquals($expected, $pipe($init));
	}

	public function testOffsetAndLengthGiven_ReturnsLengthNumberOfElementsFromOffset()
	{
		$init = array('foo', 'bar', 'baz', 'qux', 'lorem', 'ipsum');
		$expected = array(2=>'baz', 3=>'qux', 4=>'lorem');

		$pipe = new SlicePipe(2, 3);

		self::assertIteratorEquals($expected, $pipe($init));
	}

	public function testOffsetGreaterThanSize_ReturnsNoElements()
	{
		$init = array('foo', 'bar', 'baz', 'qux', 'lorem', 'ipsum');
		$expected = array();

		$pipe = new SlicePipe(count($init));

		self::assertIteratorEquals($expected, $pipe($init));
	}

	public function testLengthGreaterThanSize_ReturnsAllElementsFromOffsetToEnd()
	{
		$init = array('foo', 'bar', 'baz', 'qux', 'lorem', 'ipsum');
		$expected = array(2=>'baz', 3=>'qux', 4=>'lorem', 5=>'ipsum');

		$pipe = new SlicePipe(2, 5);

		self::assertIteratorEquals($expected, $pipe($init));
	}

	public function testAssociativeKeys_ReturnsElements()
	{
		$init = array('foo'=>'off', 'bar'=>'rab', 'baz'=>'zab', 'qux'=>'xuq');
		$expected = array('bar'=>'rab', 'baz'=>'zab');

		$pipe = new SlicePipe(1, 2);

		self::assertIteratorEquals($expected, $pipe($init));
	}
}
