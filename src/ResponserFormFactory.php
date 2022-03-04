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

namespace Tobento\Service\Form;

use Tobento\Service\Responser\ResponserInterface;

/**
 * ResponserFormFactory
 */
class ResponserFormFactory implements FormFactoryInterface
{
    /**
     * Create a new ResponserFormFactory.
     *
     * @param ResponserInterface $responser
     * @param null|TokenizerInterface $tokenizer
     * @param null|ActiveElementsInterface $activeElements
     */    
    public function __construct(
        protected ResponserInterface $responser,
        protected null|TokenizerInterface $tokenizer = null,
        protected null|ActiveElementsInterface $activeElements = null,
    ) {}
    
    /**
     * Create a new Form.
     *
     * @return Form
     */
    public function createForm(): Form
    {
        return new Form(
            input: new Input($this->responser->getInput()),
            tokenizer: $this->tokenizer,
            activeElements: $this->activeElements,
            messages: $this->responser->messages()
        );
    }
}