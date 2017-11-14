<?php
/**
 * ReflectionTransformer contains some class name and constructor params.
 * First resolving will replace item value by created singleton instance.
 * Any other resolvings will return the same instance.
 */
namespace Transformic\Transformers;

class ReflectionTransformer extends ClassTransformer
{
	/**
	 * Initialize new transformer with given value
	 * Transformer rules should contain items:
	 * 		'class'  --> Class name of object instance
	 * 		'params' --> Constructor params
	 * 		'shared' --> If TRUE then instance will be shared
	 * 
	 * @param	array	$rules	Item rules
	 * @return	void
	public function __construct($rules = []) {}
	 */

	/**
	 * Resolve item to new SHARED object instance
	 * 
	 * @param	array	$rules	Additional transformation rules
	 * @return	object
	 */
	public function transform($rules = [])
	{
		// Add resolution rules which will override existing
		$this->setRule($rules);

		// Resolve class constructor params against actual arguments
		$class = new \ReflectionClass($this->getRule('class'));
		if (! is_null($constructor = $class->getConstructor())) {
			$resolvedParams = $this->resolveParams($constructor, $this->getRule('params', []));
			$this->setRule('params', $resolvedParams);
		}

		// Create new instance with it current rules
		$instance = parent::transform();

		// Call instance methods if set
		foreach ($this->getRule('call', []) as $method) {
			// Generate the method arguments and call the method
			$resolvedParams = $this->resolveParams($class->getMethod($method[0]), $method[1]);
			call_user_func_array([$instance, $method[0]], $resolvedParams);
		}

		return $instance;
	}

	/**
	 * Resolve reflection function params using given arguments and inject all dependencies
	 * 
	 * @param	\ReflectionFunctionAbstract	$func	Reflection function
	 * @param	array	$args	An index array of actual arguments
	 * @return	array			Resolved params array
	 */
	public function resolveParams(\ReflectionFunctionAbstract $func, array $args = [])
	{
		$parameters = [];

		// Find a value for each parameter
		foreach ($func->getParameters() as $param)
		{
			// Type hinted parameter
			if (NULL !== ($class = $param->getClass())) {
			 	$class = $class->name;

				// First loop through $args and see whether or not each value can match the current parameter based on type hint
				foreach ($args as $i => $arg) {
					if ($arg instanceof $class || (NULL === $arg && $param->allowsNull())) {
						// The argument matched, store it and remove it from $args so it won't wrongly match another parameter
						$parameters[] = array_splice($args, $i, 1)[0];
						// Move on to the next parameter
						continue 2;
					}
				}

				// When nothing from $args matches but a class is type hinted, retrieve it from container
				$parameters[] = $this->getRule('container')->get($class);
			}

			// For variadic parameters, provide remaining $args
			else if ($param->isVariadic()) {
				$parameters = array_merge($parameters, $args);
				$args = [];
			}

			// There is no type hint, take all available values from $args
			else if ($args) {
				$parameters[] = array_shift($args);
			}

			// There's no type hint and nothing left in $args, provide the default value
			else if ($param->isDefaultValueAvailable()) {
				$parameters[] = $param->getDefaultValue();
			}

			// Parameter is not found in $args
			else {
				$callable = $param->getDeclaringFunction();
				$instanceOf = $param->getClass();
				throw new \InvalidArgumentException(sprintf('Argument %d%s declared in %s::%s() is undefined',
                	$param->getPosition() + 1,
                	$instanceOf ? " ({$instanceOf})" : '',
                	$callable->class, $callable->name
				));
			}
		}

		return $parameters;
	}
}
