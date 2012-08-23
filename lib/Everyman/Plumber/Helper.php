<?php
namespace Everyman\Plumber;

use RuntimeException,
    ReflectionClass;

/**
 * Register custom pipe namespaces and classes
 */
class Helper
{
	protected static $refl = array();
	protected static $classRegistry = array();
	protected static $namespaceRegistry = array(
		'Everyman\Plumber\Pipe\\' => 'Pipe',
	);

	/**
	 * Clear any custom registrations
	 */
	public static function clearRegistry()
	{
		self::$refl = array();
		self::$classRegistry = array();
		self::$namespaceRegistry = array(
			'Everyman\Plumber\Pipe\\' => 'Pipe',
		);
	}

	/**
	 * Lookup the handling class for the given pipe type
	 *
	 * Looks first in the list of specific type handlers,
	 * then each registered namespace in LIFO order.
	 *
	 * @param string $pipeName
	 * @return string fully qualified class name
	 */
	public static function lookupPipeHandler($pipeName)
	{
		if (isset(self::$classRegistry[$pipeName])) {
			return self::$classRegistry[$pipeName];
		}

		foreach (self::$namespaceRegistry as $namespace => $suffix) {
			$fqcn = $namespace.ucfirst($pipeName).$suffix;
			if (class_exists($fqcn)) {
				return $fqcn;
			}
		}

		return null;
	}

	/**
	 * Create a handling reflection class for the given pipe type
	 *
	 * @param string $pipeName
	 * @return ReflectionClass
	 * @throws RuntimeException
	 */
	public static function getPipeHandler($pipeName)
	{
		$fqcn = self::lookupPipeHandler($pipeName);
		if (!$fqcn) {
			throw new RuntimeException("Handling class for pipe '$pipeName' not found.");
		}

		if (!isset(self::$refl[$fqcn])) {
			self::$refl[$fqcn] = new ReflectionClass($fqcn);
		}
		return self::$refl[$fqcn];
	}

	/**
	 * Set a specific class to handle a given pipe type
	 *
	 * @param string $pipeName
	 * @param string $fqcn
	 */
	public static function registerPipe($pipeName, $fqcn)
	{
		self::$classRegistry[$pipeName] = $fqcn;
	}

	/**
	 * Register a namespace to lookup custom pipe handlers
	 *
	 * @param string $namespace
	 * @param string $suffic
	 */
	public static function registerNamespace($namespace, $suffix='Pipe')
	{
		if ($namespace[strlen($namespace)-1] != '\\') {
			$namespace .= '\\';
		}
		$rev = array_reverse(self::$namespaceRegistry);
		$rev[$namespace] = $suffix;
		self::$namespaceRegistry = array_reverse($rev);
	}
}
