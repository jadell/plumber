<?php
use Everyman\Plumber\Pipe\FilterPipe;

class FilterPipeTest extends PipeTestCase
{
	public function testNoCallback_ReturnsArrayWithoutFalseyValues()
	{
		$init = array(true, false, null, '', 0, 'foo', 'zero'=>1, 'three'=>0);
		$expected = array(0 => true, 5 => 'foo', 'zero'=>1);

		$pipe = new FilterPipe();

		self::assertIteratorEquals($expected, $pipe($init));
	}

	public function testReturnsFilteredArray()
	{
		$init = array('zero'=>3, 'one'=>2, 'two'=>1, 'three'=>0);

		$pipe = new FilterPipe(function ($value, $key) {
			return ($value == 2 || $key == 'three');
		});

		self::assertIteratorEquals(array('one'=>2, 'three'=>0), $pipe($init));
	}

	public function testAllMatch_ReturnsFilteredArray()
	{
		$init = array('zero'=>3, 'one'=>2, 'two'=>1, 'three'=>0);

		$pipe = new FilterPipe(function ($value, $key) {
			return true;
		});

		self::assertIteratorEquals($init, $pipe($init));
	}

	public function testNoneMatch_ReturnsFilteredArray()
	{
		$init = array('zero'=>3, 'one'=>2, 'two'=>1, 'three'=>0);

		$pipe = new FilterPipe(function ($value, $key) {
			return false;
		});

		self::assertIteratorEquals(array(), $pipe($init));
	}

	public function testFirstMatch_ReturnsFilteredArray()
	{
		$init = array('zero'=>3, 'one'=>2, 'two'=>1, 'three'=>0);

		$pipe = new FilterPipe(function ($value, $key) {
			return $key == 'zero';
		});

		self::assertIteratorEquals(array('zero'=>3), $pipe($init));
	}

	public function testLastMatch_ReturnsFilteredArray()
	{
		$init = array('zero'=>3, 'one'=>2, 'two'=>1, 'three'=>0);

		$pipe = new FilterPipe(function ($value, $key) {
			return $key == 'three';
		});

		self::assertIteratorEquals(array('three'=>0), $pipe($init));
	}

	public function testNonCallable_ThrowsException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$pipe = new FilterPipe('foo');
	}
}
