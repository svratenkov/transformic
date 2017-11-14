<?php
/**
 * SharedTransformer contains some class name and constructor params.
 * First resolving will replace item value by created singleton instance.
 * Any other resolvings will return the same instance.
 */
namespace Transformic\Transformers;

class SharedTransformer extends ClassTransformer
{
	/**
	 * Resolve item to new SHARED object instance
	 * 
	 * @param	array	$rules	Additional transformation rules
	 * @return	object
	 */
	public function transform($rules = [])
	{
		// Force shared state
		$rules['shared'] = TRUE;

		return parent::transform($rules);
	}
}
