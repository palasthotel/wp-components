<?php


namespace Palasthotel\WordPress\Model;


/**
 * @version 0.1.2
 */
class Option {

	var string $value;
	var string $label;

	public function __construct(string $value, string $label = "") {
		$this->value = $value;
		$this->label = empty($label) ? $value : $label;
	}

	public static function build(string $value, string $label = ""){
		return new static($value, $label);
	}
}
