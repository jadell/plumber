<?php
use Everyman\Plumber\Pump;

class PumpTest extends PipeTestCase
{
	public function testPump_InitWithIterator_CanBeUsedAsIterator()
	{
		$init = new ArrayIterator(array('zero', 'two', 'one', 'two', false, 'three'));
		$expected = array(1=>'TWO1', 2=>'ONE2');

		$pump = new Pump($init);
		$pump->filter()
			->unique()
			->slice(1,2)
			->transform(function ($value, $key) { return strtoupper($value).$key; });

		self::assertIteratorEquals($expected, $pump);
	}

	public function testPump_InitWithArray_CanBeUsedAsIterator()
	{
		$init = array('zero', 'two', 'one', 'two', false, 'three');
		$expected = array(1=>'TWO1', 2=>'ONE2');

		$pump = new Pump($init);
		$pump->filter()
			->unique()
			->slice(1,2)
			->transform(function ($value, $key) { return strtoupper($value).$key; });

		self::assertIteratorEquals($expected, $pump);
	}

	public function testPump_Drain_ReturnsArray()
	{
		$init = array('zero', 'two', 'one', 'two', false, 'three');
		$expected = array(1=>'TWO1', 2=>'ONE2');

		$pump = new Pump($init);
		$pump->filter()
			->unique()
			->slice(1,2)
			->transform(function ($value, $key) { return strtoupper($value).$key; });

		$result = $pump->drain();
		self::assertEquals($expected, $result);

		$result = $pump->drain();
		self::assertEquals($expected, $result);
	}
}
