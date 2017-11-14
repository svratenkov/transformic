<?php
/**
 * Additional methods for ransformic
 */
namespace Transformic;

trait TransformicAddTrait
{
	//*****************************************************************************
	//	Additional container helpers: add(), gain(), __get(), __set(), _call()
	//*****************************************************************************

	/**
	 * Set an array of items in form:
	 * 	- ['key', $value]				for any ordinary NONE ARRAY PHP value
	 * 	- ['key', [NULL, $value]]		for any ARRAY PHP value
	 * 	- ['key', ['alias', $rules]]	for transformer's definition
	 * 
	 * @param	array	$items	Packed array of item definitions ['key', 'class', params]
	 * @return	void
	 */
	public function add(array $items)
	{
		foreach ($items as $key => $params) {
			if (is_array($params)) {
				$alias = array_shift($params);

				if (is_null($alias)) {
	 				// ['key', [NULL, $value]]		for any ARRAY PHP value
					$this->set($key, $params);
				}
				else {
	 				// ['key', ['alias', $rules]]	for transformer's definition
					$this->setTransformer($key, $alias, $params);
				}
			}
			else {
				// Simplistic form for any ordinary value
				$this->set($key, $params);
			}
		}
	}

	/**
	 * Gain a value of a given key if exists, otherwise return given $default
	 * Shortcut for:
	 * 	$container->has($key) ? $container->get($key) : $default
	 * 
	 * @param  string	$key
	 * @param  mixed	$default
	 * @return mixed
	 */
	public function gain($key, $default = NULL)
	{
		return $this->has($key) ? $this->get($key) : $default;
	}

	/**
	 * Magic getter
	 * 
	 * @return mixed	A given key value
	 */
	public function __get($key)
	{
		return $this->get($key);
	}

	/**
	 * Magic setter
	 * 
	 * @param  mixed	$key	Data key
	 * @param  mixed	$val	Data value
	 * @return void
	 */
	public function __set($key, $val)
	{
		$this->set($key, $val);
	}

	/**
	 * Magic setter for transformer classes:
	 * 		setSomeTransformer($key, $rules)
	 * is equivalent to:
	 * 		setTransformer($key, 'SomeTransformer', $rules)
	 * 
	 * @param	string	$name	Inaccessible method name
	 * @param	array 	$args	Inaccessible method args: item key & transformer params
	 * @return	void
	 */
	public function __call($name, $args)
	{
		if (substr($name, 0, 3) == 'set') {
	 		$alias = substr($name, 3);			// Transformer class name
	 		$key = array_shift($args);			// Item key & transformer params
			$this->setTransformer($key, $alias, $args);
			return;
		}

		$class = static::class;
		throw new \Exception("Fatal error: Call to undefined method {$class}::{$name}()");
	}
}
