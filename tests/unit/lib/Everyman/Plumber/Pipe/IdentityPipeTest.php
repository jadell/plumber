<?php
namespace Everyman\Plumber\Pipe;

use Everyman\Plumber\Pipe\IdentityPipe,
    PipeTestCase;

class IdentityPipeTest extends PipeTestCase
{
	public function testNumericIndex_ReturnsEquivalentArray()
	{
		$init = array('zero', 'one', 'two', 'three');

		$pipe = new IdentityPipe();
		$pipe->setStarts(new \ArrayIterator($init));

		self::assertIteratorEquals($init, $pipe);
	}

	public function testAssociativeIndex_ReturnsEquivalentArray()
	{
		$init = array('zero'=>3, 'one'=>2, 'two'=>1, 'three'=>0);

		$pipe = new IdentityPipe();
		$pipe->setStarts(new \ArrayIterator($init));

		self::assertIteratorEquals($init, $pipe);
	}
}
