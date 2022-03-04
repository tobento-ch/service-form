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
 * FormFieldsetMethodTest
 */
class FormFieldsetMethodTest extends TestCase
{
    public function testFieldset()
    {
        $form = new Form();
        
        $this->assertSame(
            '<fieldset><legend>Legend</legend>',
            $form->fieldset(
                legend: 'Legend',
                attributes: [],
                legendAttributes: [],
            )
        );
    }
    
    public function testFieldsetEscapesLegend()
    {
        $form = new Form();
        
        $this->assertSame(
            '<fieldset><legend>&lt;p&gt;Legend&lt;/p&gt;</legend>',
            $form->fieldset(
                legend: '<p>Legend</p>',
            )
        );
    }    
    
    public function testAttributesAttribute()
    {
        $form = new Form();
        
        $this->assertSame(
            '<fieldset class="foo" data-bar=\'{&quot;foo&quot;:&quot;bar&quot;}\' readonly><legend>name</legend>',
            $form->fieldset(
                legend: 'name',
                attributes: ['class' => 'foo', 'data-bar' => ['foo' => 'bar'], 'readonly'],
            )
        );
    }
    
    public function testLegendAttributesAttribute()
    {
        $form = new Form();
        
        $this->assertSame(
            '<fieldset><legend class="foo" data-bar=\'{&quot;foo&quot;:&quot;bar&quot;}\' readonly>name</legend>',
            $form->fieldset(
                legend: 'name',
                legendAttributes: ['class' => 'foo', 'data-bar' => ['foo' => 'bar'], 'readonly'],
            )
        );
    }
    
    public function testClose()
    {
        $form = new Form();
        
        $this->assertSame(
            '</fieldset>',
            $form->fieldsetClose()
        );
    }    
}