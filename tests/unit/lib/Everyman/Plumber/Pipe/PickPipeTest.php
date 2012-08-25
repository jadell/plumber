<?php
use Everyman\Plumber\Pipe\PickPipe;

class PickPipeTest extends PipeTestCase
{
	public function testScalarElements_ReturnsNulls()
	{
		$init = array('foo', 'bar', 'baz');

		$pickKey = 'qux';
		$expected = array(null, null, null);

		$pipe = new PickPipe($pickKey);

		self::assertIteratorEquals($expected, $pipe($init));
	}

	public function testArrayElements_ReturnsArrayOfSelectedValues()
	{
		$init = array(
			array('foo'=>'foo', 'bar'=>'bar', 'baz'=>'baz'),
			array('foo'=>'bar', 'bar'=>'baz', 'baz'=>'foo'),
			array('foo'=>'baz', 'bar'=>'foo', 'baz'=>'bar'),
		);

		$pickKey = 'bar';
		$expected = array('bar', 'baz', 'foo');

		$pipe = new PickPipe($pickKey);

		self::assertIteratorEquals($expected, $pipe($init));
	}

	public function testArrayElements_MultiplePickKeys_ReturnsArrayOfArraysOfSelectedValues()
	{
		$init = array(
			array('foo'=>'foo', 'bar'=>'bar', 'baz'=>'baz'),
			array('foo'=>'bar', 'bar'=>'baz', 'baz'=>'foo'),
			array('foo'=>'baz', 'bar'=>'foo', 'baz'=>'bar'),
		);

		$pickKey = array('bar', 'baz');
		$expected = array(
			array('bar'=>'bar', 'baz'=>'baz'),
			array('bar'=>'baz', 'baz'=>'foo'),
			array('bar'=>'foo', 'baz'=>'bar'),
		);

		$pipe = new PickPipe($pickKey);

		self::assertIteratorEquals($expected, $pipe($init));
	}

	public function testObjectElements_ReturnsArrayOfSelectedValues()
	{
		$init = array(
			(object)array('foo'=>'foo', 'bar'=>'bar', 'baz'=>'baz'),
			(object)array('foo'=>'bar', 'bar'=>'baz', 'baz'=>'foo'),
			(object)array('foo'=>'baz', 'bar'=>'foo', 'baz'=>'bar'),
		);

		$pickKey = 'bar';
		$expected = array('bar', 'baz', 'foo');

		$pipe = new PickPipe($pickKey);

		self::assertIteratorEquals($expected, $pipe($init));
	}

	public function testObjectElements_MultiplePickKeys_ReturnsArrayOfArraysOfSelectedValues()
	{
		$init = array(
			(object)array('foo'=>'foo', 'bar'=>'bar', 'baz'=>'baz'),
			(object)array('foo'=>'bar', 'bar'=>'baz', 'baz'=>'foo'),
			(object)array('foo'=>'baz', 'bar'=>'foo', 'baz'=>'bar'),
		);

		$pickKey = array('bar', 'baz');
		$expected = array(
			array('bar'=>'bar', 'baz'=>'baz'),
			array('bar'=>'baz', 'baz'=>'foo'),
			array('bar'=>'foo', 'baz'=>'bar'),
		);

		$pipe = new PickPipe($pickKey);

		self::assertIteratorEquals($expected, $pipe($init));
	}
}
