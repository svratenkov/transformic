<?php
/**
 * Self-transformed object interface
 */
namespace Transformic\Transformers;

interface TransformerInterface
{
	/**
	 * Resolve transformer and return its value
	 * Shared transformer will replace itself in the owner container by its value
	 * 
	 * @return	mixed			Transformer value
	 */
	public function transform();
}
