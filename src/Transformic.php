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
 * 	!!!	Define special class for rules (`Rules`), then in $transformic->set($key, $value)
 * 		we can easily distinquish the request for Transformer by testing ($value instanceof Rules)
 * 		DECLINED: no sense because rules is a data container,
 * 		while Transformer is as well a data container for rules too.
 */
namespace Transformic;
use Transformic\Containers\DataContainer;
use Transformic\Transformers\TransformerInterface;

class Transformic extends DataContainer implements TransformicInterface
{
	use TransformicTrait;
	use TransformicAddTrait;
}
