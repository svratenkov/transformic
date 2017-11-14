<?php
/**
 * FactoryTransformer contains some class name and constructor params.
 * Each resolving returns another new instance of this class & params.
 * 
 * FactoryTransformer is simply an immortal SharedTransformer!
 */
namespace Transformic\Transformers;

class FactoryTransformer extends ClassTransformer
{
	/**
	 * Resolve item to new UNSHARED object instance
	 * 
	 * @param	array	$rules	Additional transformation rules
	 * @return	object
	 */
	public function transform($rules = [])
	{
		// Force UNshared state
		$rules['shared'] = FALSE;

		return parent::transform($rules);
	}
}
