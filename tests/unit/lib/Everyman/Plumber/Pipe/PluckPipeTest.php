<?php
use Everyman\Plumber\Pipe\PluckPipe;

class PluckPipeTest extends PipeTestCase
{
	public function testScalarElements_ReturnsNulls()
	{
		$init = array('foo', 'bar', 'baz');

		$pluckKey = 'qux';
		$expected = array(null, null, null);

		$pipe = new PluckPipe($pluckKey);

		self::assertIteratorEquals($expected, $pipe($init));
	}

	public function testArrayElements_ReturnsArrayOfSelectedValues()
	{
		$init = array(
			array('foo'=>'foo', 'bar'=>'bar', 'baz'=>'baz'),
			array('foo'=>'bar', 'bar'=>'baz', 'baz'=>'foo'),
			array('foo'=>'baz', 'bar'=>'foo', 'baz'=>'bar'),
		);

		$pluckKey = 'bar';
		$expected = array('bar', 'baz', 'foo');

		$pipe = new PluckPipe($pluckKey);

		self::assertIteratorEquals($expected, $pipe($init));
	}

	public function testArrayElements_MultiplePluckKeys_ReturnsArrayOfArraysOfSelectedValues()
	{
		$init = array(
			array('foo'=>'foo', 'bar'=>'bar', 'baz'=>'baz'),
			array('foo'=>'bar', 'bar'=>'baz', 'baz'=>'foo'),
			array('foo'=>'baz', 'bar'=>'foo', 'baz'=>'bar'),
		);

		$pluckKey = array('bar', 'baz');
		$expected = array(
			array('bar'=>'bar', 'baz'=>'baz'),
			array('bar'=>'baz', 'baz'=>'foo'),
			array('bar'=>'foo', 'baz'=>'bar'),
		);

		$pipe = new PluckPipe($pluckKey);

		self::assertIteratorEquals($expected, $pipe($init));
	}

	public function testObjectElements_ReturnsArrayOfSelectedValues()
	{
		$init = array(
			(object)array('foo'=>'foo', 'bar'=>'bar', 'baz'=>'baz'),
			(object)array('foo'=>'bar', 'bar'=>'baz', 'baz'=>'foo'),
			(object)array('foo'=>'baz', 'bar'=>'foo', 'baz'=>'bar'),
		);

		$pluckKey = 'bar';
		$expected = array('bar', 'baz', 'foo');

		$pipe = new PluckPipe($pluckKey);

		self::assertIteratorEquals($expected, $pipe($init));
	}

	public function testObjectElements_MultiplePluckKeys_ReturnsArrayOfArraysOfSelectedValues()
	{
		$init = array(
			(object)array('foo'=>'foo', 'bar'=>'bar', 'baz'=>'baz'),
			(object)array('foo'=>'bar', 'bar'=>'baz', 'baz'=>'foo'),
			(object)array('foo'=>'baz', 'bar'=>'foo', 'baz'=>'bar'),
		);

		$pluckKey = array('bar', 'baz');
		$expected = array(
			array('bar'=>'bar', 'baz'=>'baz'),
			array('bar'=>'baz', 'baz'=>'foo'),
			array('bar'=>'foo', 'baz'=>'bar'),
		);

		$pipe = new PluckPipe($pluckKey);

		self::assertIteratorEquals($expected, $pipe($init));
	}

	public function testElementDoesNotContainKey_ReturnsNull()
	{
		$init = array(
			array('bar'=>'bar', 'baz'=>'baz'),
			(object)array('bar'=>'baz', 'baz'=>'foo'),
		);

		$pluckKey = 'foo';
		$expected = array(null, null);

		$pipe = new PluckPipe($pluckKey);

		self::assertIteratorEquals($expected, $pipe($init));
	}

	public function testElementDoesNotContainMultipleKeys_ReturnsNull()
	{
		$init = array(
			array('bar'=>'bar', 'baz'=>'baz'),
			(object)array('foo'=>'bar', 'baz'=>'foo'),
			(object)array('baz'=>'bar'),
		);

		$pluckKey = array('foo', 'bar');
		$expected = array(
			array('foo'=>null, 'bar'=>'bar'),
			array('foo'=>'bar', 'bar'=>null),
			array('foo'=>null, 'bar'=>null),
		);

		$pipe = new PluckPipe($pluckKey);

		self::assertIteratorEquals($expected, $pipe($init));
	}
}
