<?php
class PipeTestCase extends PHPUnit_Framework_TestCase
{
	public static function assertIteratorEquals($expected, Iterator $iterator, $message=null)
	{
		$result = array();
		foreach ($iterator as $key => $value) {
			$result[$key] = $value;
		}
		self::assertEquals($expected, $result, $message);
	}
}
