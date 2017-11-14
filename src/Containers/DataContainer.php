<?php
/*
	DataContainer - base container for extending by Transformic
*/
namespace Transformic\Containers;
use Transformic\TransformicContainerInterface;

class DataContainer implements TransformicContainerInterface
{
	// Container's data array
	protected $data = [];

	/**
	 * Set item with given key and value pair
	 * 
	 * @param  string	$key	Item key
	 * @param  mixed	$value	Item value
	 * @return void
	 */
	public function set($key, $value)
	{
		$this->data[$key] = $value;
	}

	/**
	 * Returns true if the container can return an entry for the given identifier.
	 * Returns false otherwise.
	 *
	 * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
	 * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
	 *
	 * @param string $id Identifier of the entry to look for.
	 *
	 * @return bool
	 */
	public function has($id)
	{
		return isset($this->data[$id]) or array_key_exists($id, $this->data);
	}

	/**
	 * Finds an entry of the container by its identifier and returns it.
	 *
	 * @param string $id Identifier of the entry to look for.
	 *
	 * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
	 * @throws ContainerExceptionInterface Error while retrieving the entry.
	 *
	 * @return mixed Entry.
	 */
	public function get($id)
	{
		try {
			return $this->data[$id];
		} catch (\Exception $e) {
			throw new \NotFoundException("No entry was found for {$id} identifier");
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
	public function gain($key, $default = NULL)
	{
		return $this->has($key) ? $this->get($key) : $default;
	}
	 */

	/**
	 * Remove given key from the container
	 * 
	 * @return void
	 */
	public function remove($key)
	{
		if (isset($this->data[$key])) {
			unset($this->data[$key]);
		}
	}

	/**
	 * Magic getter
	 * 
	 * @return mixed	A given key value
	public function __get($key)
	{
		return $this->get($key);
	}
	 */

	/**
	 * Magic setter
	 * 
	 * @param  mixed	$key	Data key
	 * @param  mixed	$val	Data value
	 * @return void
	public function __set($key, $val)
	{
		$this->set($key, $val);
	}
	 */
}
