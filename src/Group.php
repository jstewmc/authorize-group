<?php
/**
 * The file for a group
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2016 Jack Clayton
 * @license    MIT
 */

namespace Jstewmc\AuthorizeGroup;

/**
 * A group
 *
 * @since  0.1.0
 */
interface Group
{
    /* !Public methods */
    
    /**
     * Returns the group's 
     *
     * @return  string
     * @since   0.1.0
     */
    public function getName(): string;   
}