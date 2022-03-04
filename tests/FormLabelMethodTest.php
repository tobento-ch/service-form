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
 * FormLabelMethodTest
 */
class FormLabelMethodTest extends TestCase
{
    public function testLabel()
    {
        $form = new Form();
        
        $this->assertSame(
            '<label>text</label>',
            $form->label(
                text: 'text',
            )
        );
    }
    
    public function testForAttribute()
    {
        $form = new Form();
        
        $this->assertSame(
            '<label for="color">text</label>',
            $form->label(
                text: 'text',
                for: 'color',
            )
        );

        $this->assertSame(
            '<label for="options_color">text</label>',
            $form->label(
                text: 'text',
                for: 'options[color]',
            )
        );
        
        $this->assertSame(
            '<label for="options_color">text</label>',
            $form->label(
                text: 'text',
                for: 'options.color',
            )
        );        
    }
    
    public function testAttributesAttribute()
    {
        $form = new Form();
        
        $this->assertSame(
            '<label class="foo" data-bar=\'{&quot;foo&quot;:&quot;bar&quot;}\'>text</label>',
            $form->label(
                text: 'text',
                attributes: ['class' => 'foo', 'data-bar' => ['foo' => 'bar']],
            )
        );       
    }
    
    public function testRequiredTextAttribute()
    {
        $form = new Form();
        
        $this->assertSame(
            '<label>text<span class="required">Required</span></label>',
            $form->label(
                text: 'text',
                requiredText: 'Required',
            )
        );
        
        $this->assertSame(
            '<label>text<span class="required">&lt;p&gt;Required&lt;/p&gt;</span></label>',
            $form->label(
                text: 'text',
                requiredText: '<p>Required</p>',
            )
        );        
    }
    
    public function testOptionalTextAttribute()
    {
        $form = new Form();
        
        $this->assertSame(
            '<label>text<span class="optional">Optional</span></label>',
            $form->label(
                text: 'text',
                optionalText: 'Optional',
            )
        );
        
        $this->assertSame(
            '<label>text<span class="optional">&lt;p&gt;Optional&lt;/p&gt;</span></label>',
            $form->label(
                text: 'text',
                optionalText: '<p>Optional</p>',
            )
        );        
    }    
}