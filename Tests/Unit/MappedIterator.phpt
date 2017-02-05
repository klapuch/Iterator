<?php
declare(strict_types = 1);
/**
 * @testCase
 * @phpVersion > 7.0
 */
namespace Klapuch\Iterator\Unit;

use Tester;
use Tester\Assert;
use Klapuch\Iterator;

require __DIR__ . '/../bootstrap.php';

final class MappedIterator extends Tester\TestCase {
	public function testAnonymousCallbackFunction() {
		$iterator = new \ArrayIterator(['a', 'bb', 'ccc']);
		Assert::same(
			['aX', 'bbX', 'cccX'],
			iterator_to_array(
				new Iterator\MappedIterator(
					$iterator,
					function(string $value): string {
						return $value . 'X';
					}
				)
			)
		);
	}

	public function testObjectCallbackMethod() {
		$iterator = new \ArrayIterator(['a', 'bb', 'ccc']);
		$object = new class {
			function map(string $value): string {
				return $value . 'X';
			}
		};
		Assert::same(
			['aX', 'bbX', 'cccX'],
			iterator_to_array(new Iterator\MappedIterator($iterator, [$object, 'map']))
		);
	}

	public function testStringCallback() {
		$iterator = new \ArrayIterator(['a', 'bb', 'ccc']);
		Assert::same(
			['A', 'BB', 'CCC'],
			iterator_to_array(new Iterator\MappedIterator($iterator, 'strtoupper'))
		);
	}
}


(new MappedIterator())->run();