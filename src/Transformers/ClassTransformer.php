<?php
/**
 * ClassTransformer contains in its rules some class name and constructor params.
 * Resolving will return new instance of given class and params.
 * 
 * New instance will be shared if NOT explicitly defined ['shared' => FALSE] rule.
 */
namespace Transformic\Transformers;

class ClassTransformer extends AbstractTransformer
{
	/**
	 * Resolve item to new object instance
	 * If defined 'shared' rule then new instance will be shared.
	 * Default is unshared.
	 * 
	 * @param	array	$rules	Additional transformation rules
	 * @return	object
	 */
	public function transform($rules = [])
	{
		// Add resolution rules which will override existing
		$this->setRule($rules);

		// Create object instance with class and constructor params defined in rules
		$class = $this->getRule('class');
		$params = $this->getRule('params', []);
		$instance = new $class(...$params);

		return $instance;
	}
}
