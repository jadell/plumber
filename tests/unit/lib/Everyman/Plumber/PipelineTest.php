<?php
use Everyman\Plumber\Pipeline,
    Everyman\Plumber\Pipe\TransformPipe,
    Everyman\Plumber\Pipe\FilterPipe;

class PipelineTest extends PipeTestCase
{
	public function testPipeline_ProcessesInOrder()
	{
		$init = array('zero', 'one', 'two', 'three');

		$filter = new FilterPipe(function ($value) { return strlen($value) < 4; });
		$transform = new TransformPipe(function ($value) { return strtoupper($value); });

		$pipeline = new Pipeline($filter, $transform);
		$pipeline->setStarts(new \ArrayIterator($init));

		self::assertIteratorEquals(array(1=>'ONE', 2=>'TWO'), $pipeline);
	}

	public function testPipeline_PipesCanBeAddedAfterConstruction()
	{
		$init = array('zero', 'one', 'two', 'three');

		$filter = new FilterPipe(function ($value) { return strlen($value) < 4; });
		$transform = new TransformPipe(function ($value) { return strtoupper($value); });

		$pipeline = new Pipeline($filter);
		$pipeline->appendPipe($transform);
		$pipeline->setStarts(new \ArrayIterator($init));

		self::assertIteratorEquals(array(1=>'ONE', 2=>'TWO'), $pipeline);
	}

	public function testPipelinesAreComposable()
	{
		$init = array('zero', 'one', 'two', 'three');

		$filter = new FilterPipe(function ($value) { return strlen($value) < 4; });
		$transform = new TransformPipe(function ($value) { return strtoupper($value); });
		$pipeline = new Pipeline($filter, $transform);

		$reverse = new TransformPipe(function ($value) { return strrev($value); });
		$pipeline2 = new Pipeline($pipeline, $reverse);

		$pipeline2->setStarts(new \ArrayIterator($init));

		self::assertIteratorEquals(array(1=>'ENO', 2=>'OWT'), $pipeline2);
	}

	public function testPipelineWithNoPipesIsAnIdentityPipeline()
	{
		$init = array('zero', 'one', 'two', 'three');

		$pipeline = new Pipeline();
		$pipeline->setStarts(new \ArrayIterator($init));

		self::assertIteratorEquals($init, $pipeline);
	}
}
