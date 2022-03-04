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
 * InputInterface
 */
interface InputInterface
{
    /**
     * Has input data for the specified name.
     *
     * @param string $name The name.
     * @return bool
     */
    public function has(string $name): bool;
    
    /**
     * Returns the input data for the specified name.
     *
     * @param string $name The name.
     * @param mixed $default A default value
     * @return mixed
     */
    public function get(string $name, mixed $default = null): mixed;
}