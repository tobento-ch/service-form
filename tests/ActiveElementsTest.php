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
use Tobento\Service\Form\ActiveElements;
use Tobento\Service\Form\ActiveElement;

/**
 * ActiveElementsTest
 */
class ActiveElementsTest extends TestCase
{
    public function testAddMethod()
    {
        $els = new ActiveElements();
        
        $els->add(
            name: 'options[color]',
            id: 'options.color',
            value: 'red',
            label: 'Red',
            group: 'optcol',
            type: 'input.checkbox',            
        );
        
        $el = $els->get('optcol');
        
        $this->assertSame('optcol', $el->name());
        $this->assertSame('optcol', $el->id());
        $this->assertSame('Red', $el->value());
        $this->assertSame('Optcol', $el->label());
        $this->assertSame('input.checkbox', $el->type());
        $this->assertSame('optcol', $el->group());
    }    
    
    public function testFilterMethod()
    {
        $els = new ActiveElements();
        $els->add(name: 'options[color]', value: 'red');
        $els->add(name: 'options[color]', value: 'blue');

        $newEls = $els->filter(
            fn(ActiveElement $a): bool => $a->value() === 'blue'
        );
        
        $this->assertFalse($els === $newEls);
        
        $this->assertSame(
            1,
            count($newEls->all())
        );        
    }
}