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

namespace Tobento\Service\Form\Test;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Form\Each;
use Traversable;

/**
 * EachTest
 */
class EachTest extends TestCase
{
    public function testMethods()
    {
        $items = ['red', 'blue'];
        
        $callback = function($item, $key) {
            return [$item];
        };
        
        $each = new Each(
            items: $items,
            callback: $callback
        );
        
        $this->assertSame($items, $each->items());
        
        $this->assertSame($callback, $each->callback());
    }
    
    public function testGetIterator()
    {
        $each = new Each(
            items: ['red', 'blue'],
            callback: function($item, $key) {
                return [$item];
            }
        );
        
        $this->assertInstanceof(
            Traversable::class,
            $each->getIterator()
        );
    }    
}