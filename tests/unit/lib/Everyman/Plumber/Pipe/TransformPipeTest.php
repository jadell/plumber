<?php
use Everyman\Plumber\Pipe\TransformPipe;

class TransformPipeTest extends PipeTestCase
{
	public function testNoTransformCallback_NumericIndex_ReturnsIdentityArray()
	{
		$init = array('zero', 'one', 'two', 'three');

		$pipe = new TransformPipe();

		self::assertIteratorEquals($init, $pipe($init));
	}

	public function testNoTransformCallback_AssociativeArray_ReturnsIdentityArray()
	{
		$init = array('zero'=>3, 'one'=>2, 'two'=>1, 'three'=>0);

		$pipe = new TransformPipe();

		self::assertIteratorEquals($init, $pipe($init));
	}

	public function testTransformCallbackGiven_ReturnsTransformedArray()
	{
		$init = array('zero'=>3, 'one'=>2, 'two'=>1, 'three'=>0);
		$expected = array('zero'=>'zero3', 'one'=>'one2', 'two'=>'two1', 'three'=>'three0');

		$pipe = new TransformPipe(function ($value, $key) {
			return $key.$value;
		});

		self::assertIteratorEquals($expected, $pipe($init));
	}

	public function testNonCallable_ThrowsException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$pipe = new TransformPipe('foo');
	}
}
