<?php
declare(strict_types = 1);
namespace Klapuch\Iterator;

/**
 * array_filter for iterator
 */
final class FilteredIterator extends \FilterIterator {
	private $callback;
	private $flag;

	public function __construct(
		\Iterator $iterator,
		callable $callback,
		int $flag = 0
	) {
		parent::__construct($iterator);
		$this->callback = $callback;
		$this->flag = $flag;
	}

	public function accept(): bool {
		if($this->flag === ARRAY_FILTER_USE_KEY)
			return $this->result(parent::key());
		elseif($this->flag === ARRAY_FILTER_USE_BOTH)
			return $this->result(parent::current(), parent::key());
		return $this->result(parent::current());
	}

	private function result(...$args): bool {
		return (bool)call_user_func_array($this->callback, $args);
	}
}