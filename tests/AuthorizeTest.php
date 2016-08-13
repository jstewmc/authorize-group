<?php
/**
 * The file for the authorize-group service tests
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2016 Jack Clayton
 * @license    MIT
 */

namespace Jstewmc\AuthorizeGroup;

use Jstewmc\TestCase\TestCase;

/**
 * Tests for the authorize-group service
 */
class AuthorizeTest extends TestCase
{
    /**
     * __construct() should set the properties
     */
    public function testConstruct()
    {
        $groups = ['foo' => ['bar']];
        $roles  = ['bar' => ['baz' => 'qux']];
        
        $context = new Authorize($groups, $roles);
        
        $this->assertEquals($groups, $this->getProperty('groups', $context));
        $this->assertEquals($roles, $this->getProperty('roles', $context));
        
        return;
    }
    
    
    /* !__invoke() */
    
    /**
     * __invoke() should return false if the group does not exist
     */
    public function testInvokeReturnsFalseIfGroupDoesNotExist()
    {
        // create a group named "foo"
        $group = new class implements Group {
            public function getName(): string
            {
                return 'foo';
            }  
        };
        
        // create a list of groups, excluding "foo"
        $groups = ['bar'];
        
        // create a list of roles
        $roles  = ['baz' => ['qux' => ['quux']]];
        
        // create the context
        $context = new Authorize($groups, $roles);
        
        // note the "foo" group is not a valid group
        $this->assertFalse($context($group, 'corge', 'grault'));
        
        return;
    }
    
    /**
     * __invoke() should return false if roles does not exist
     */
    public function testInvokeReturnsFalseIfRoleDoesNotExist()
    {
        // create a group named "foo"
        $group = new class implements Group {
            public function getName(): string
            {
                return 'foo';
            }  
        };
        
        // assign the "foo" group the "bar" role
        $groups = ['foo' => ['bar']];
        
        // create a list of roles, excluding the "bar" role
        $roles  = ['baz' => ['qux' => ['quux']]];
        
        // create the context
        $context = new Authorize($groups, $roles);
        
        // note the "bar" role is not a valid role
        $this->assertFalse($context($group, 'corge', 'grault'));
        
        return;
    }
    
    /**
     * __invoke() should return false if the group does not have the resource
     */
    public function testInvokeReturnsFalseIfGroupDoesNotHavePermissionForResource()
    {
        // create a group named "foo"
        $group = new class implements Group {
            public function getName(): string
            {
                return 'foo';
            }  
        };
        
        // assign the "foo" group the "bar" role
        $groups = ['foo' => ['bar']];
        
        // create a list of roles, including the "bar" role
        $roles  = ['bar' => ['qux' => ['quux']]];
        
        // create the context
        $context = new Authorize($groups, $roles);
        
        // note the "bar" role does not have access to the "grault" resource
        $this->assertFalse($context($group, 'corge', 'grault'));
        
        return;
    }
    
    /**
     * authorize() should return false if the group does not have the action
     */
    public function testAuthorizeReturnsFalseIfGroupDoesNotHavePermissionForAction()
    {
        // create a group named "foo"
        $group = new class implements Group {
            public function getName(): string
            {
                return 'foo';
            }  
        };
        
        // assign the "foo" group the "bar" role
        $groups = ['foo' => ['bar']];
        
        // create a list of roles, including the "bar" role
        $roles  = ['bar' => ['qux' => ['quux']]];
        
        // create the context
        $context = new Authorize($groups, $roles);
        
        // note the "bar" role *does not* have access to the "grault" action on the 
        //     "qux" resource
        //
        $this->assertFalse($context($group, 'grault', 'qux'));
        
        return;
    }
    
    /**
     * authorize() should return true if the group has permission
     */
    public function testAuthorizeReturnsTrueIfGroupHasPermission()
    {
        // create a group named "foo"
        $group = new class implements Group {
            public function getName(): string
            {
                return 'foo';
            }  
        };
        
        // assign the "foo" group the "bar" role
        $groups = ['foo' => ['bar']];
        
        // create a list of roles, including the "bar" role
        $roles  = ['bar' => ['qux' => ['quux']]];
        
        // create the context
        $context = new Authorize($groups, $roles);
        
        // note the "bar" role *does* have access to the "quux" action on the "qux"
        //     resource
        //
        $this->assertTrue($context($group, 'quux', 'qux'));
        
        return;
    }
}
