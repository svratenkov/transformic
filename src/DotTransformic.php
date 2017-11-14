<?php
/**
 * DotTransformic - Ioc Container based on self-transformed items with dot access
 */
namespace Transformic;
use Transformic\Containers\DotContainer;
use Transformic\Transformers\TransformerInterface;

class DotTransformic extends DotContainer implements TransformicInterface
{
	use TransformicTrait;
	use TransformicAddTrait;
}
