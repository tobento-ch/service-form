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
use Tobento\Service\Form\ActiveElement;

/**
 * ActiveElementTest
 */
class ActiveElementTest extends TestCase
{
    public function testMethods()
    {
        $el = new ActiveElement(
            name: 'options[color]',
            id: 'options.color',
            value: 'red',
            label: 'Red',
            group: 'optcol',
            type: 'input.checkbox',
        );
        
        $this->assertSame('options[color]', $el->name());
        $this->assertSame('options.color', $el->id());
        $this->assertSame('red', $el->value());
        $this->assertSame('Red', $el->label());
        $this->assertSame('input.checkbox', $el->type());
        $this->assertSame('optcol', $el->group());
    }
    
    public function testMethodsWithNull()
    {
        $el = new ActiveElement(
            name: 'options[color]',
        );
        
        $this->assertSame('options[color]', $el->name());
        $this->assertSame(null, $el->id());
        $this->assertSame(null, $el->value());
        $this->assertSame(null, $el->label());
        $this->assertSame(null, $el->type());
        $this->assertSame(null, $el->group());
    }    
}