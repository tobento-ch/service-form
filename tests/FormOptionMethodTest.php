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
use Tobento\Service\Form\Form;
use Tobento\Service\Form\Input;

/**
 * FormOptionMethodTest
 */
class FormOptionMethodTest extends TestCase
{
    public function testOption()
    {
        $form = new Form();
        
        $this->assertSame(
            '<option value="red">Red</option>',
            $form->option(
                value: 'red',
                text: 'Red',
            )
        );
    }
    
    public function testValueAttributeOnly()
    {
        $form = new Form();
        
        $this->assertSame(
            '<option value="red"></option>',
            $form->option(
                value: 'red',
            )
        );
    }    
    
    public function testEscapes()
    {
        $form = new Form();
        
        $this->assertSame(
            '<option value="&lt;p&gt;red&lt;/p&gt;">&lt;p&gt;Red&lt;/p&gt;</option>',
            $form->option(
                value: '<p>red</p>',
                text: '<p>Red</p>',
            )
        );
    }    
    
    public function testSelectedAttributeWithString()
    {
        $form = new Form();
        
        $this->assertSame(
            '<option value="red" selected>Red</option>',
            $form->option(
                value: 'red',
                text: 'Red',
                selected: 'red',
            )
        );
        
        $this->assertSame(
            '<option value="blue">Blue</option>',
            $form->option(
                value: 'blue',
                text: 'Blue',
                selected: 'red',
            )
        );       
    }
    
    public function testSelectedAttributeWithArray()
    {
        $form = new Form();
        
        $this->assertSame(
            '<option value="red" selected>Red</option>',
            $form->option(
                value: 'red',
                text: 'Red',
                selected: ['red'],
            )
        );
        
        $this->assertSame(
            '<option value="blue">Blue</option>',
            $form->option(
                value: 'blue',
                text: 'Blue',
                selected: ['red'],
            )
        );        
    }
    
    public function testAttributesAttribute()
    {
        $form = new Form();
        
        $this->assertSame(
            '<option class="foo" data-bar=\'{&quot;foo&quot;:&quot;bar&quot;}\' readonly value="blue">Blue</option>',
            $form->option(
                value: 'blue',
                text: 'Blue',
                attributes: ['class' => 'foo', 'data-bar' => ['foo' => 'bar'], 'readonly'],
            )
        );
    }    
}