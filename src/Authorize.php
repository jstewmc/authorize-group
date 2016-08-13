<?php
/**
 * The file for the authorize-group service
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2016 Jack Clayton
 * @license    MIT
 */

namespace Jstewmc\AuthorizeGroup;

/**
 * The authorize-group service
 *
 * @since  0.1.0
 */
class Authorize
{
    /* !Private properties */
    
    /**
     * @var  mixed[]  the application's groups in the form ["<group>" => ["<role>", 
     *     "<role>", ...], ...]
     * @since  0.1.0
     */
    private $groups;
    
    /**
     * @var  mixed[]  the application's roles in the form ["<role>" => ["<resource>" 
     *     => ["<action>", "<action>", ...], ...], ...]
     * @since  0.1.0
     */
    private $roles;
    
    
    /* !Magic methods */
    
    /**
     * Called when the service is constructed
     *
     * @param  mixed[]  $groups  the application's groups
     * @param  mixed[]  $roles   the application's roles
     * @since  0.1.0
     */
    public function __construct(array $groups, array $roles)
    {
        $this->groups = $groups;
        $this->roles  = $roles;
    }
    
    /**
     * Called when the service is treated like a function
     *
     * I'll return true if the group is authorized to perform the action on the 
     * resource.
     *
     * @param   Group   $group     a user group
     * @param   string  $action    the desired action (e.g., "create")
     * @param   string  $resource  the desired resource (e.g., "foo")
     * @return  bool
     * @since   0.1.0
     */
    public function __invoke(Group $group, string $action, string $resource): bool
    {
        // if the group exists
        if (array_key_exists($group->getName(), $this->groups)) {
            // loop through the group's roles
            foreach ($this->groups[$group->getName()] as $role) {
                // if the role exists
                if (array_key_exists($role, $this->roles)) {
                    // if the role has permissions on the resource
                    if (array_key_exists($resource, $this->roles[$role])) {
                        // if the role has permissions for the action
                        if (in_array($action, $this->roles[$role][$resource])) {
                            // great success!
                            return true;
                        }
                    }
                }
            }
        }
        
        return false;
    }
}
