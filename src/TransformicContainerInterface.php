<?php
/**
 * TransformicContainerInterface :== PsrContainerInterface [get(), has()] + set()
 */
namespace Transformic;
use Psr\Container\ContainerInterface as PsrContainerInterface;

interface TransformicContainerInterface extends PsrContainerInterface
{
	/**
	 * Set container item with given key and value
	 * 
	 * @param  string	$key	Container item key
	 * @param  mixed	$value	Container item value
	 * @return void
	 */
	public function set($key, $value);
}
