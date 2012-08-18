<?php
namespace Everyman\Plumber\Pipe;

use Everyman\Plumber\Pipe\FilterPipe,
    PipeTestCase;

class FilterPipeTest extends PipeTestCase
{
	public function testNoCallback_ReturnsArrayWithoutFalseyValues()
	{
		$init = array(true, false, null, '', 0, 'foo', 'zero'=>1, 'three'=>0);
		$expected = array(0 => true, 5 => 'foo', 'zero'=>1);

		$pipe = new FilterPipe();
		$pipe->setStarts(new \ArrayIterator($init));

		self::assertIteratorEquals($expected, $pipe);
	}

	public function testReturnsFilteredArray()
	{
		$init = array('zero'=>3, 'one'=>2, 'two'=>1, 'three'=>0);

		$pipe = new FilterPipe(function ($value, $key) {
			return ($value == 2 || $key == 'three');
		});
		$pipe->setStarts(new \ArrayIterator($init));

		self::assertIteratorEquals(array('one'=>2, 'three'=>0), $pipe);
	}

	public function testAllMatch_ReturnsFilteredArray()
	{
		$init = array('zero'=>3, 'one'=>2, 'two'=>1, 'three'=>0);

		$pipe = new FilterPipe(function ($value, $key) {
			return true;
		});
		$pipe->setStarts(new \ArrayIterator($init));

		self::assertIteratorEquals($init, $pipe);
	}

	public function testNoneMatch_ReturnsFilteredArray()
	{
		$init = array('zero'=>3, 'one'=>2, 'two'=>1, 'three'=>0);

		$pipe = new FilterPipe(function ($value, $key) {
			return false;
		});
		$pipe->setStarts(new \ArrayIterator($init));

		self::assertIteratorEquals(array(), $pipe);
	}

	public function testFirstMatch_ReturnsFilteredArray()
	{
		$init = array('zero'=>3, 'one'=>2, 'two'=>1, 'three'=>0);

		$pipe = new FilterPipe(function ($value, $key) {
			return $key == 'zero';
		});
		$pipe->setStarts(new \ArrayIterator($init));

		self::assertIteratorEquals(array('zero'=>3), $pipe);
	}

	public function testLastMatch_ReturnsFilteredArray()
	{
		$init = array('zero'=>3, 'one'=>2, 'two'=>1, 'three'=>0);

		$pipe = new FilterPipe(function ($value, $key) {
			return $key == 'three';
		});
		$pipe->setStarts(new \ArrayIterator($init));

		self::assertIteratorEquals(array('three'=>0), $pipe);
	}

	public function testNonCallable_ThrowsException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$pipe = new FilterPipe('foo');
	}
}
