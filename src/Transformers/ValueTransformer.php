<?php
/**
 * ValueTransformer contains any ordinary PHP value.
 * First resolving will simply replace this transformer with it's value.
 * 
 * This class is for comprehensiveness only.
 * The natural way is to directly assign a value to transformic key:
 * 		$transformic->set('some_key', 'some_value')
 */
namespace Transformic\Transformers;

class ValueTransformer extends AbstractTransformer
{
	/**
	 * Resolve item - simply returns it's value
	 * Customic will automatically replace this transformer by it's value
	 * 
	 * @param	array	$rules	Additional transformation rules
	 * @return	mixed
	 */
	public function transform($rules = [])
	{
		// Add transformation rules
		$this->setRule($rules);

		return $this->getRule('value');
	}
}
