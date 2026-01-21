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
use Tobento\Service\Support\HtmlString;

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
    
    public function testLabelWithHtmlString()
    {
        $form = new Form();
        
        $this->assertSame(
            '<label><span>text</span></label>',
            $form->label(
                text: new HtmlString('<span>text</span>'),
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
        
        $this->assertSame(
            '<label>text<span class="required"><span>required</span></span></label>',
            $form->label(
                text: 'text',
                requiredText: new HtmlString('<span>required</span>'),
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
        
        $this->assertSame(
            '<label>text<span class="optional"><span>optional</span></span></label>',
            $form->label(
                text: 'text',
                optionalText: new HtmlString('<span>optional</span>'),
            )
        );
    }    
}