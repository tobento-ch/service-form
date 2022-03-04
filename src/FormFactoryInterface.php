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

/**
 * FormFactoryInterface
 */
interface FormFactoryInterface
{
    /**
     * Create a new Form.
     *
     * @return Form
     */
    public function createForm(): Form;
}