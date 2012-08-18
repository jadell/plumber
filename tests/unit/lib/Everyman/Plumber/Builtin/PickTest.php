<?php
use Everyman\Plumber\Builtin\Pick;

class PickTest extends PipeTestCase
{
	public function testScalarElements_ReturnsNulls()
	{
		$init = array('foo', 'bar', 'baz');

		$pickKey = 'qux';
		$expected = array(null, null, null);

		$pipe = new Pick($pickKey);
		$pipe->setStarts(new \ArrayIterator($init));

		self::assertIteratorEquals($expected, $pipe);
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

		$pipe = new Pick($pickKey);
		$pipe->setStarts(new \ArrayIterator($init));

		self::assertIteratorEquals($expected, $pipe);
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

		$pipe = new Pick($pickKey);
		$pipe->setStarts(new \ArrayIterator($init));

		self::assertIteratorEquals($expected, $pipe);
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

		$pipe = new Pick($pickKey);
		$pipe->setStarts(new \ArrayIterator($init));

		self::assertIteratorEquals($expected, $pipe);
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

		$pipe = new Pick($pickKey);
		$pipe->setStarts(new \ArrayIterator($init));

		self::assertIteratorEquals($expected, $pipe);
	}
}