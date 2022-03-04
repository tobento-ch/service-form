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
 * FormDatalistMethodTest
 */
class FormDatalistMethodTest extends TestCase
{
    public function testDatalist()
    {
        $form = new Form();
        
        $this->assertSame(
            '<datalist id="colors"><option value="red"></option><option value="blue"></option></datalist>',
            $form->datalist(
                name: 'colors',
                items: ['red', 'blue'],
                attributes: [],
            )
        );
    }

    public function testIdAttribute()
    {
        $form = new Form();
        
        $this->assertSame(
            '<datalist id="options_colors"><option value="red"></option></datalist>',
            $form->datalist(
                name: 'options.colors',
                items: ['red'],
            )
        );
    }
    
    public function testEscapes()
    {
        $form = new Form();
        
        $this->assertSame(
            '<datalist id="colors"><option value="&lt;p&gt;red&lt;/p&gt;"></option></datalist>',
            $form->datalist(
                name: 'colors',
                items: ['<p>red</p>'],
            )
        );
    }
    
    public function testAttributesAttribute()
    {
        $form = new Form();
        
        $this->assertSame(
            '<datalist class="foo" data-bar=\'{&quot;foo&quot;:&quot;bar&quot;}\' readonly id="colors"><option value="red"></option></datalist>',
            $form->datalist(
                name: 'colors',
                items: ['red'],
                attributes: ['class' => 'foo', 'data-bar' => ['foo' => 'bar'], 'readonly'],
            )
        );
    }
    
    public function testWithEachMethod()
    {
        $form = new Form();
        
        $items = ['red' => 'Red', 'blue' => 'Blue'];
        
        $this->assertSame(
            '<datalist id="colors"><option value="RED"></option><option value="BLUE"></option></datalist>',
            $form->datalist(
                name: 'colors',
                items: $form->each(items: $items, callback: function($item, $key): array {
                    // value:string
                    return [strtoupper($item)];
                })
            )
        );
    }    
}