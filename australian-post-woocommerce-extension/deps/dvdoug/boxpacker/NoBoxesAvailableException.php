<?php

/**
 * Box packing (3D bin packing, knapsack problem).
 *
 * @author Doug Wright
 */
namespace WPRubyAustraliaPost\Deps\DVDoug\BoxPacker;

use RuntimeException;
/**
 * Class NoBoxesAvailableException
 * Exception used when an item cannot be packed into any box.
 * @internal
 */
class NoBoxesAvailableException extends RuntimeException
{
    /** @var Item */
    public $item;
    /**
     * NoBoxesAvailableException constructor.
     */
    public function __construct($message, Item $item)
    {
        $this->item = $item;
        parent::__construct($message);
    }
    public function getItem()
    {
        return $this->item;
    }
}
