<?php

/**
 * TOBENTO
 *
 * @copyright   Tobias Strub, TOBENTO
 * @license     MIT License, see LICENSE file distributed with this source code.
 * @author      Tobias Strub
 * @link        https://www.tobento.ch
 */

declare(strict_types=1);

namespace Tobento\Service\Form;

use Closure;
use IteratorAggregate;
use ArrayIterator;
use Traversable;

/**
 * @implements IteratorAggregate<array-key, mixed>
 */
class Each implements IteratorAggregate
{
    /**
     * Create a new Each.
     * 
     * @param iterable $items
     * @param Closure $callback
     */
    public function __construct(
        protected iterable $items,
        protected Closure $callback
    ) {}
    
    /**
     * Returns the items.
     *
     * @return iterable
     */
    public function items(): iterable
    {
        return $this->items;
    }
    
    /**
     * Returns the callback.
     *
     * @return Closure
     */
    public function callback(): Closure
    {
        return $this->callback;
    }  

    /**
     * Get the iterator. 
     *
     * @return Traversable<array-key, mixed>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items());
    }
}