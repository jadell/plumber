<?php
use Everyman\Plumber\Pipe\RandomPipe;

class RandomPipeTest extends PipeTestCase
{
	public function testOnlyReturnsElementsWhenRandomIsLessThanTheThreshold()
	{
		$init = array('foo', 'bar', 'baz', 'qux', 'lorem', 'ipsum');
		$expected = array(0=>'foo', 3=>'qux', 5=>'ipsum');

		$random = function () {
			static $i = 0;
			$rand = array(15,75,51,21,90,1);
			return $rand[$i++];
		};

		$pipe = new RandomPipe(50, $random);

		self::assertIteratorEquals($expected, $pipe($init));
	}

	public function testZeroThreshold_NoValuesEmitted()
	{
		$init = array('foo', 'bar', 'baz', 'qux', 'lorem', 'ipsum');
		$expected = array();

		$pipe = new RandomPipe(0);

		self::assertIteratorEquals($expected, $pipe($init));
	}

	public function testHundredPercentThreshold_AllValuesEmitted()
	{
		$init = array('foo', 'bar', 'baz', 'qux', 'lorem', 'ipsum');

		$pipe = new RandomPipe(100);

		self::assertIteratorEquals($init, $pipe($init));
	}
}
