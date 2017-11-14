<?php
/*
 * TransformicInterface :== TransformicContainerInterface [set(), has(), get()] + setTransformer()
 */
namespace Transformic;

interface TransformicInterface extends TransformicContainerInterface
{
	/**
	 * Create new transformer item with given class and params
	 * and set it to the container by given key
	 * 
	 * Given class name doesn't start with '\' assumes to be
	 * an alias for same subclass of Transformic\Transformers\.
	 * 
	 * @param  string	$key	Container item key
	 * @param  string	$class	Transformer class name or alias
	 * @return void
	 */
	public function setTransformer($key, $class);
}
