# authorize-group
Authorize a _group_ to perform an _action_ on a _resource_.

## Terminology

This library uses the following terminology:

* A _user_ is a person.
* A _resource_ is a thing, typically a model name. By convention, resources are plural and lower-case (e.g., `'users'`).
* An _action_ is something done to a resource, typically a CRUD operation. By convention, actions are singular, present-tense, and lower-case (e.g., `'create'`).
* A _permission_ is the right to perform an _action_ on a _resource_ (e.g., `'create'` + `'users'`).
* A _role_ is a named set of permissions. By convention, roles are singular and lower-cased (e.g., `'administrator'`)
* A _group_ is a set of users with a _unique_ name. By convention, groups are plural and lower-case (e.g., `'administrators'`).

## Methodology

This library's methodology is rather simple:

1. A _user_ is assigned to a _group_.
2. A _group_ is assigned one or more _roles_.
3. A _role_ is granted one or more _permissions_.
4. A _permission_ allows an _action_ on a _resource_.

While _users_ are assigned one or more _groups_ in the database, a _group_ is assigned a _role_ and a _role_ is assigned _permissions_ in a configuration array.

## Example

Finally (haha):

```php
use Jstewmc\AuthorizeGroup;

// grant permissions to roles
$roles = [
    // the "administrator" role...
    'administrator' => [
        // for the "users" resource...
        'users' => [
            // has the "create" action
            'create'   
        ]
    ]
];

// assign roles to groups
$groups = [
    // the "administrators" group...
    'administrators' => [
        // has the "administrator" role
        'administrator'
    ]
];

// implement a group named "administrators"
$group = new class implements Group {
    public function getName(): string {
        return 'administrators';
    }
}

// create our authorization service
$authorizer = new Authorize($groups, $roles);

// is the group authorized to create users? (yes)
$authorizer($group, 'create', 'users');

// is the group authorized to delete users? (no)
$authorizer($group, 'delete', 'users');
```

That's about it!

## License

[MIT](https://github.com/jstewmc/authorize-group)

## Author

[Jack Clayton](mailto:clayjs0@gmail.com)

## Version

### 0.1.0, August 3, 2016

* Initial release
