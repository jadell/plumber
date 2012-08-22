<?php
use Everyman\Plumber\Pipeline,
    Everyman\Plumber\Pipe\TransformPipe,
    Everyman\Plumber\Pipe\FilterPipe;

class PipelineTest extends PipeTestCase
{
	public function testPipeline_InitWithAnIterator_ActsAsIdentityPipe()
	{
		$init = array('zero', 'one', 'two', 'three');

		$pipeline = new Pipeline(new \ArrayIterator($init));

		self::assertIteratorEquals($init, $pipeline);
	}

	public function testPipeline_SetStartsAfterInit_ActsAsIdentityPipe()
	{
		$init = array('zero', 'one', 'two', 'three');

		$pipeline = new Pipeline(new \ArrayIterator($init));
		$pipeline->setStarts(new \ArrayIterator($init));

		self::assertIteratorEquals($init, $pipeline);
	}

	public function testPipeline_InitWithAnArray_ActsAsIdentityPipe()
	{
		$init = array('zero', 'one', 'two', 'three');

		$pipeline = new Pipeline($init);

		self::assertIteratorEquals($init, $pipeline);
	}

	public function testPipeline_AppendPipes_ProcessesInOrder()
	{
		$init = array('zero', 'one', 'two', 'three');

		$filter = new FilterPipe(function ($value) { return strlen($value) < 4; });
		$transform = new TransformPipe(function ($value) { return strtoupper($value); });

		$pipeline = new Pipeline($init);
		$pipeline->appendPipe($filter)
			->appendPipe($transform);

		self::assertIteratorEquals(array(1=>'ONE', 2=>'TWO'), $pipeline);
	}

	public function testPipeline_AppendAnotherPipeline_ProcessesInOrder()
	{
		$init = array('zero', 'one', 'two', 'three');

		$filter = new FilterPipe(function ($value) { return strlen($value) < 4; });
		$transform = new TransformPipe(function ($value) { return strtoupper($value); });
		$reverse = new TransformPipe(function ($value) { return strrev($value); });

		$pipeline = new Pipeline();
		$pipeline2 = new Pipeline($init);

		$pipeline->appendPipe($filter)
			->appendPipe($transform);

		$pipeline2->appendPipe($pipeline)
			->appendPipe($reverse);

		self::assertIteratorEquals(array(1=>'ENO', 2=>'OWT'), $pipeline2);
	}

	public function testPipeline_Fluent_AppendsPipes()
	{
		$init = array('zero', 'two', 'one', 'two', false, 'three');
		$expected = array(1=>'TWO1', 2=>'ONE2');

		$pipeline = new Pipeline($init);
		$pipeline->filter()
			->unique()
			->slice(1,2)
			->transform(function ($value, $key) { return strtoupper($value).$key; });

		self::assertIteratorEquals($expected, $pipeline);
	}

	public function testPipeline_Drain_ReturnsArray()
	{
		$init = array('zero', 'two', 'one', 'two', false, 'three');
		$expected = array(1=>'TWO1', 2=>'ONE2');

		$pipeline = new Pipeline($init);
		$pipeline->filter()
			->unique()
			->slice(1,2)
			->transform(function ($value, $key) { return strtoupper($value).$key; });

		$result = $pipeline->drain();
		self::assertEquals($expected, $result);
	}

	public function testPipeline_IterateMultipleTimes()
	{
		$init = array('zero', 'two', 'one', 'two', false, 'three');
		$expected = array(1=>'TWO1', 2=>'ONE2');

		$pipeline = new Pipeline($init);
		$pipeline->filter()
			->unique()
			->slice(1,2)
			->transform(function ($value, $key) { return strtoupper($value).$key; });

		self::assertIteratorEquals($expected, $pipeline);
		self::assertIteratorEquals($expected, $pipeline);
		self::assertEquals($expected, $pipeline->drain());
		self::assertEquals($expected, $pipeline->drain());
		self::assertIteratorEquals($expected, $pipeline);
	}
}
