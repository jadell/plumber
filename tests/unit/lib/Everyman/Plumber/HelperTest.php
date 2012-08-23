<?php
namespace Everyman\Plumber\HelperTest;

use Everyman\Plumber\Pipeline,
    Everyman\Plumber\Helper,
    Everyman\Plumber\Pipe\TransformPipe,
    PipeTestCase;

class HelperTest extends PipeTestCase
{
	public function setup()
	{
		Helper::clearRegistry();
	}

	public function teardown()
	{
		Helper::clearRegistry();
	}

	public function testCustomPipeNotRegistered()
	{
		$fqcn = Helper::lookupPipeHandler('myHelperMissing');
		self::assertNull($fqcn);

		$this->setExpectedException('RuntimeException');
		Helper::getPipeHandler('myHelperMissing');
	}

	public function testCustomPipeRegistered()
	{
		Helper::registerPipe('myHelper', 'Everyman\Plumber\HelperTest\MyHelperCustom');

		$fqcn = Helper::lookupPipeHandler('myHelper');
		self::assertEquals('Everyman\Plumber\HelperTest\MyHelperCustom', $fqcn);

		$handler = Helper::getPipeHandler('myHelper');
		self::assertInstanceOf('\ReflectionClass', $handler);
		self::assertEquals('Everyman\Plumber\HelperTest\MyHelperCustom', $handler->getName());
	}

	public function testCustomNamespaceRegisteredWithDefaultSuffix()
	{
		Helper::registerNamespace('Everyman\Plumber\HelperTest\\');

		$fqcn = Helper::lookupPipeHandler('otherHelper');
		self::assertEquals('Everyman\Plumber\HelperTest\OtherHelperPipe', $fqcn);

		$handler = Helper::getPipeHandler('otherHelper');
		self::assertInstanceOf('\ReflectionClass', $handler);
		self::assertEquals('Everyman\Plumber\HelperTest\OtherHelperPipe', $handler->getName());
	}

	public function testCustomNamespaceRegisteredWithCustomSuffix()
	{
		Helper::registerNamespace('Everyman\Plumber\HelperTest\\', 'Custom');

		$fqcn = Helper::lookupPipeHandler('myHelper');
		self::assertEquals('Everyman\Plumber\HelperTest\MyHelperCustom', $fqcn);

		$handler = Helper::getPipeHandler('myHelper');
		self::assertInstanceOf('\ReflectionClass', $handler);
		self::assertEquals('Everyman\Plumber\HelperTest\MyHelperCustom', $handler->getName());
	}

	public function testCustomPipeOverridesCustomNamespace()
	{
		Helper::registerNamespace('Everyman\Plumber\HelperTest\\');
		Helper::registerPipe('otherHelper', 'Everyman\Plumber\HelperTest\MyHelperCustom');

		$fqcn = Helper::lookupPipeHandler('otherHelper');
		self::assertEquals('Everyman\Plumber\HelperTest\MyHelperCustom', $fqcn);

		$handler = Helper::getPipeHandler('otherHelper');
		self::assertInstanceOf('\ReflectionClass', $handler);
		self::assertEquals('Everyman\Plumber\HelperTest\MyHelperCustom', $handler->getName());
	}

	public function testCustomPipeOverridesBuiltinPipe()
	{
		$init = array(1, 2, 3, 4);

		// With custom
		Helper::registerPipe('transform', 'Everyman\Plumber\HelperTest\MyHelperCustom');
		$pipeline = new Pipeline($init);
		$pipeline->transform();
		self::assertIteratorEquals(array(1, 0, 1, 0), $pipeline);

		// Mix custom and builtin
		$pipeline = new Pipeline($init);
		$pipeline->transform()->filter();
		self::assertIteratorEquals(array(0 => 1, 2 => 1), $pipeline);

		// Reset
		Helper::clearRegistry();
		$pipeline = new Pipeline($init);
		$pipeline->transform();
		self::assertIteratorEquals($init, $pipeline);
	}
}

class MyHelperCustom extends TransformPipe
{
	public function __construct()
	{
		parent::__construct(function ($value) {
			return $value % 2;
		});
	}
}

class OtherHelperPipe extends TransformPipe
{
}
