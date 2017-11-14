<?php
/**
 * Immortal MethodTransformer contains some instance or class name.
 * Each resolving retrieves a method name as param and
 * returns [object, method] callable array.
 * 
 * Applicable to:
 * 	- instance static or dynamic method 
 * 	- class static method 
 * Not applicable to:
 * 	- class dynamic method - an instance should be constructed in advance
 */
namespace Transformic\Transformers;

class MethodTransformer extends ValueTransformer
{
	/**
	 * Resolve item value to a valid [value, method] callable array
	 * First param is a method name of this item's value,
	 * which should be an existing instance or static class name
	 * 
	 * @param	list ...$params	Transformer item params, first param is the method name
	 * @param	string	$method	Transformer item instance or class method name
	 * @return	callable
	 */
//	public function resolve(...$params)
	public function resolve($method)
	{
		return [$this->value, $method];
	}
}
