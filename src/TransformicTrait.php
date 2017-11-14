<?php
/**
 * Transformic - Ioc Container based on self-transformed items
 * 
 * incredibly simple, extremely customizable, fantastically compact and clean!
 * Thanks to amazing SoC (Separation of Concerns design principle)!
 * 
 * Container items could be of two kinds:
 * 	-	values - any ordinary PHP values;
 * 	-	and... AND... and Transformers! These are special objects that can
 * 		transform themselfs to anything they want!
 * 
 * To add new fuctionality to the container the only thing you need is to
 * define new simple Transformer class with only one transform method! Thats all!
 * 
 * Transformic does NOT implement any data container functionality.
 * Instead it uses external data container to get/set it's data items.
 * Out of the box Transformic use it's own ascetic container with only two methods: get() & set().
 * To assign another data container to Transformic use class aliasing:
 * 		class_alias('your-container-class', TransformicContainer::class);
 * 
 * !!! TODO: !!!
 * 	+++	createTransformer($alias, $rules) - include `alias` into rules
 * 		Will increase flexibility (can have both `alias` & `class` in the rules)
 * 	+++	Define special assoc array for Transformer definition
 * 		This could be done by special key (like '%class') to distinquish it from ordinary arrays
 * 		DECLINED: unneeded for minimalistic core implementation
 */
namespace Transformic;
use Transformic\Transformers\TransformerInterface;

trait TransformicTrait
{
	/**
	 * Create new transformer item with given class and params
	 * and add it to the container as entry with given key
	 * 
	 * @param	string	$key	Container item key
	 * @param	string	$alias	Transformer alias or full class name
	 * @param	array	$rules	Transformer rules
	 * @return	void
	 */
	public function setTransformer($key, $alias, $rules = [])
	{
		$this->set($key, $this->createTransformer($alias, $rules));
	}

	/**
	 * Create new transformer item with given class and params.
	 * Given class name doesn't start with '\' assumes to be
	 * a subclass of Customic\Resolvers\.
	 * 
	 * @param	string	$class	Transformer alias or full class name
	 * @param	array	$rules	Transformer rules
	 * @return	TransformerInterface	Transformer instance
	 */
	public function createTransformer($class, $rules = [])
	{
		$class = '\\' === $class[0] ? ltrim($class, '\\') : $this->makeTransformerClass($class);

		return new $class($rules);
	}

	/**
	 * Make transformer class name from given alias
	 * 
	 * @param	string	$alias	Transformer class name alias
	 * @return	string			Transformer's full class name
	 */
	public function makeTransformerClass($alias)
	{
		return Transformers::class.'\\'.ucfirst($alias).'Transformer';
	}

	/**
	 * Resolve item with given key in the container
	 * 
	 * @param	string	$key	Container item key
	 * @param	array	$rules	Additional transformer rules
	 * @return	mixed			Resolved item value
	 */
	public function get($key, $rules = [])
	{
		if (parent::has($key)) {
			$value = parent::get($key);
			if (! $value instanceof TransformerInterface) {
				return $value;
			}
			$transformer = $value;
		}
		else {
			// Requested entry does not exist - create ReflectionTransformer entry
			$transformer = new Transformers\ReflectionTransformer(['class' => $key, 'container'	=> $this]);
			$this->set($key, $transformer);
		}

		// $value is a transformer instance here
		$value = $transformer->transform($rules);

		// Replace shared transformer with its value, default is UNSHARED
		if ($transformer->getRule('shared', FALSE)) {
			$this->set($key, $value);
			// Transformer instance will be destroed by PHP if it is unused now
		}

		return $value;
	}

	/**
	 * Retrieve row value (without transforming) for given key
	 * 
	 * @param	string	$key	Container item key
	 * @return	mixed			Container item raw value
	 */
	public function raw($key)
	{
		return parent::get($key);
	}
}
