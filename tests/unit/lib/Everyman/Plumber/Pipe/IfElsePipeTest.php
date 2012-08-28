<?php
use Everyman\Plumber\Pipe\IfElsePipe;

class IfElsePipeTest extends PipeTestCase
{
	public function testFirstCallbackFilters_SecondCallbackReturnsIfFilterIsTrue_ThirdIfFalse()
	{
		$init = array('zero', 'one', 'two', 'three', 'four', 'five', 'six');
		$expected = array(4, -1, -1, 5, 4, 4, -1);

		$pipe = new IfElsePipe(function ($value, $key) {
			return strlen($value) < 4;
		}, function ($value, $key) {
			return -1;
		}, function ($value, $key) {
			return strlen($value);
		});

		self::assertIteratorEquals($expected, $pipe($init));
	}

	public function testThirdCallbackNotSpecified_ReturnsValue()
	{
		$init = array('zero', 'one', 'two', 'three', 'four', 'five', 'six');
		$expected = array(4, 'one', 'two', 5, 4, 4, 'six');

		$pipe = new IfElsePipe(function ($value, $key) {
			return strlen($value) >= 4;
		}, function ($value, $key) {
			return strlen($value);
		});

		self::assertIteratorEquals($expected, $pipe($init));
	}
}
