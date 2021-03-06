<?php
declare(strict_types = 1);
namespace Klapuch\Iterator;

/**
 * array_map for iterator
 */
final class Mapped extends \IteratorIterator {
	private $callback;

	public function __construct(\Traversable $iterator, callable $callback) {
		parent::__construct($iterator);
		$this->callback = $callback;
	}

	/**
	 * @return mixed
	 */
	public function current() {
		return call_user_func($this->callback, parent::current());
	}
}
