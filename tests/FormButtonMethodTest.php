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
 * FormButtonMethodTest
 */
class FormButtonMethodTest extends TestCase
{
    public function testButton()
    {
        $form = new Form();
        
        $this->assertSame(
            '<button type="submit">Submit Text</button>',
            $form->button(
                text: 'Submit Text',
                attributes: [],
                escText: true,
            )
        );
    }
    
    public function testAttributesAttribute()
    {
        $form = new Form();
        
        $this->assertSame(
            '<button class="foo" data-bar=\'{&quot;foo&quot;:&quot;bar&quot;}\' disabled type="submit">name</button>',
            $form->button(
                text: 'name',
                attributes: ['class' => 'foo', 'data-bar' => ['foo' => 'bar'], 'disabled'],
            )
        );
    }
    
    public function testTypeAttribute()
    {
        $form = new Form();
        
        $this->assertSame(
            '<button type="reset">name</button>',
            $form->button(
                text: 'name',
                attributes: ['type' => 'reset'],
            )
        );
    }    
}