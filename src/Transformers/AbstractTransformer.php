<?php
/**
 * Abstract rules transformer - parent for rules-based transformers
 * Transformer rules defines specific behaviour based on named rules
 * 
 * Transformation process may require specific parameters called `rules`.
 * Transformation rules are implemented as associative array ['rule-name' => rule-value].
 * The treating of rules is specific for each transformer class.
 * There are some common rule names:
 * 	- Back reference to owner container used for replacement shared transformer by its value:
 * 		- shared	- FALSE for unshared transformers
 * 	//	- ownerObj	- Transformic instance which contains this transformer
 * 	//	- ownerKey	- The key of this transformer in the owner Transformic instance
 * 
 * Transformer inheritance hierarchy:
 *		TransformerInterface ==> [
 * 			AbstractTransformer ==> [			// TransformerInterface, setRule(), getRule()
 * 					ValueTransformer,			// rules['value'] = {Any PHP value}
 * 					ClassTransformer ==> [		// rules['class'] = 'object class name OR entry name'
 * 						SharedTransformer,		// Forced SHARED ClassTransformer
 * 						FactoryTransformer,		// Forced UNSHARED ClassTransformer
 * 						MethodTransformer,		// rules['method'] = 'class method name'
 * 					]
 * 				]
 * 			]
 * 		]
 */
namespace Transformic\Transformers;

abstract class AbstractTransformer implements TransformerInterface
{
	/**
	 * @var array	$rules	Each transformer has it's own behaviour rules
	 */
	protected $rules = [];

	/**
	 * Initialize new transformer with given value
	 * 
	 * @param	array	$rules	Item rules
	 * @return	void
	 */
	public function __construct($rules = [])
	{
		$this->setRule($rules);
	}

	/**
	 * Set given transformer rule or an array of rules
	 * 
	 * @param	string|array	$name	Rule name or rules array
	 * @param	mixed|NULL		$value	Rule value if given
	 * @return	TransformerInterface	Chained return
	 */
	public function setRule($name, $value = NULL)
	{
		if (is_array($name)) {
			foreach ($name as $key => $value) {
				$this->rules[$key] = $value;
			}
		}
		else {
			$this->rules[$name] = $value;
		}

		// Chained return
		return $this;
	}

	/**
	 * Get transformer rule value given by name or an array of all rules
	 * 
	 * @param	string|NULL	$name		Rule name or rules array if NULL
	 * @param	mixed		$default	Default value if given rule is undefined
	 * @return	mixed
	 */
	public function getRule($name = NULL, $default = NULL)
	{
		if (NULL === $name) {
			return $this->rules;
		}

		return isset($this->rules[$name]) ? $this->rules[$name] : $default;
	}

	/**
	 * Resolve transformer and return its value
	 * 
	 * 
	 * @param	array	$rules	Additional transformation rules
	 * @return	mixed
	 */
	abstract public function transform($rules = []);
}
