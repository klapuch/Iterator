<?php
declare(strict_types = 1);
/**
 * @testCase
 * @phpVersion > 7.0
 */
namespace Klapuch\Iterator\Unit;

use Klapuch\Iterator;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

final class Mapped extends Tester\TestCase {
	public function testAnonymousCallbackFunction() {
		$iterator = new \ArrayIterator(['a', 'bb', 'ccc']);
		Assert::same(
			['aX', 'bbX', 'cccX'],
			iterator_to_array(
				new Iterator\Mapped(
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
			iterator_to_array(new Iterator\Mapped($iterator, [$object, 'map']))
		);
	}

	public function testStringCallback() {
		$iterator = new \ArrayIterator(['a', 'bb', 'ccc']);
		Assert::same(
			['A', 'BB', 'CCC'],
			iterator_to_array(new Iterator\Mapped($iterator, 'strtoupper'))
		);
	}
}


(new Mapped())->run();
