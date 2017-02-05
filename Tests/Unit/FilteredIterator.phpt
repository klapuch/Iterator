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

final class FilteredIterator extends Tester\TestCase {
	public function testAnonymousCallbackFunction() {
		$iterator = new \ArrayIterator(['a', 'bb', 'ccc', 'dddd', 'eeeee']);
		Assert::same(
			[3 => 'dddd', 'eeeee'],
			iterator_to_array(
				new Iterator\FilteredIterator(
					$iterator,
					function(string $value): bool {
						return strlen($value) > 3;
					}
				)
			)
		);
	}

	public function testObjectCallbackMethod() {
		$iterator = new \ArrayIterator(['a', 'bb', 'ccc', 'dddd', 'eeeee']);
		$object = new class {
			function filter(string $value): bool {
				return strlen($value) > 3;
			}
		};
		Assert::same(
			[3 => 'dddd', 'eeeee'],
			iterator_to_array(new Iterator\FilteredIterator($iterator, [$object, 'filter']))
		);
	}

	public function testStringCallback() {
		$iterator = new \ArrayIterator([1, 2, 3, 'dddd', 'eeeee']);
		Assert::same(
			[3 => 'dddd', 'eeeee'],
			iterator_to_array(new Iterator\FilteredIterator($iterator, 'is_string'))
		);
	}

	public function testAutomaticConversionToBool() {
		$iterator = new \ArrayIterator(['', '', '', 'dddd', 'eeeee']);
		Assert::same(
			[3 => 'dddd', 'eeeee'],
			iterator_to_array(new Iterator\FilteredIterator($iterator, 'strlen'))
		);
	}

	public function testUsingKeys() {
		$iterator = new \ArrayIterator([
			'' => 1, '' => 2, '' => 3, 'dddd' => 4, 'eeeee' => 5
		]);
		Assert::same(
			['dddd' => 4, 'eeeee' => 5],
			iterator_to_array(
				new Iterator\FilteredIterator(
					$iterator, 'strlen', ARRAY_FILTER_USE_KEY
				)
			)
		);
	}

	public function testUsingBoth() {
		$iterator = new \ArrayIterator([1, 2, 3, 4, 5]);
		$callback = function(int $value, int $key): bool {
			return $key < 5 && ($value + $key) > 5; // ensure value is first
		};
		Assert::same(
			[3 => 4, 5],
			iterator_to_array(
				new Iterator\FilteredIterator(
					$iterator, $callback, ARRAY_FILTER_USE_BOTH
				)
			)
		);
	}
}


(new FilteredIterator())->run();