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

/**
 * FormFormMethodTest
 */
class FormFormMethodTest extends TestCase
{
    public function testWithoutAttributesSetPostMethodAsDefault()
    {
        $form = new Form();
        
        $this->assertSame(
            '<form method="POST">',
            $form->form()
        );
    }
    
    public function testUsesSpecifiedMethod()
    {
        $form = new Form();
        
        $this->assertSame(
            '<form method="GET">',
            $form->form(['method' => 'get'])
        );
    }
    
    public function testMethodSpoofing()
    {
        $form = new Form();
        
        $this->assertSame(
            '<form method="POST"><input name="_method" type="hidden" value="PUT">',
            $form->form(['method' => 'put'])
        );
        
        $this->assertSame(
            '<form method="POST"><input name="_method" type="hidden" value="PATCH">',
            $form->form(['method' => 'patch'])
        );
        
        $this->assertSame(
            '<form method="POST"><input name="_method" type="hidden" value="DELETE">',
            $form->form(['method' => 'DELETE'])
        );        
    }
    
    public function testAttributes()
    {
        $form = new Form();
        
        $this->assertSame(
            '<form name="login" action="/foo" method="POST">',
            $form->form(['name' => 'login', 'action' => '/foo'])
        );
    }
    
    public function testCloseMethod()
    {
        $form = new Form();
        
        $this->assertSame(
            '</form>',
            $form->close()
        );
    }    
}