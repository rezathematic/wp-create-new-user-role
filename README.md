# Creating custom roles in WordPress

WordPress user Roles and Capabilities give you the ability to control what users can or cannot do based on the specified role.
You can manage user capabilities for writing and editing posts, creating new pages, moderating comments, installing plugins, adding new users,etc.

## Find user roles in the Database

WordPress stores this data in the `wp_options` table under the serialized `wp_user_roles`.
The `WP_Roles` class defines how to store roles and capabilities in the database.
You can find the source in `/wp-includes/class-wp-roles.php`.

## Roles and Capabilities official chart

You can find this chart on the [WordPress Codex](https://wordpress.org/support/article/roles-and-capabilities/#capability-vs-role-table)

## Customize an existing WordPress User Role

You can customize the capabilities of an already existing role using the following code

```php
<?php
// Get the Subscriber role's object from WP_Role class
$subscriber = get_role("subscriber");

// List of capabilities we like to add to a subscriber
$caps = array(
    'install_plugins',
    'install_themes',
    'update_core',
    'update_plugins',
    'edit_published_posts',
    'upload_files',
    'publish_posts',
    'delete_published_posts',
    'edit_posts',
    'delete_posts',
    'read',
);

// add all the capabilities by looping through them
foreach($caps as $cap) {
    $subscriber->add_cap($cap)
}
```

## Duplicating a User Role

You can duplicate an existing role and inherit its existing capabilities

```php
<?php

// add_role($role, $display_name, $capabilities)
add_role(
  "admin_clone",
  "Administrator Clone",
  get_role("administrator")->capabilities
);
```

## Create a new Custom User Role

To create a new role you can use the `add_role()` function.

```php
<?php

// add_role($role, $display_name, $capabilities)

//  'My New Role' is create with reading, moderating comments, editing own, others and already published posts
add_role("my_new_role", "My New Role", [
  "read" => true,
  "moderate_comments" => true,
  "edit_posts" => true,
  "edit_others_posts" => true,
  "edit_published_posts" => true,
]);
```

You should be able to find the newly created user role inside the database

## Remove a User Role

To remove an existing user role you can use the function `remove_role()`.

```php
<?php

remove_role("my_new_role");
```

## Create Custom Capabilites in WordPress (Custom Post Type)

Upon creation of a new Custom Post Type, you can specify a new `capability_type`.
If you are using `CPT-UI`, by default the `capability_type` is registered as `post`.

```php
<?php
/**
 * Register a custom post type called "book".
 *
 * @see get_post_type_labels() for label keys.
 */
function wpdocs_codex_book_init()
{
  $labels = [
    "name" => _x("Books", "Post type general name", "textdomain"),
    "singular_name" => _x("Book", "Post type singular name", "textdomain"),
    "menu_name" => _x("Books", "Admin Menu text", "textdomain"),
    "name_admin_bar" => _x("Book", "Add New on Toolbar", "textdomain"),
    "add_new" => __("Add New", "textdomain"),
    "add_new_item" => __("Add New Book", "textdomain"),
    "new_item" => __("New Book", "textdomain"),
    "edit_item" => __("Edit Book", "textdomain"),
    "view_item" => __("View Book", "textdomain"),
    "all_items" => __("All Books", "textdomain"),
    "search_items" => __("Search Books", "textdomain"),
    "parent_item_colon" => __("Parent Books:", "textdomain"),
    "not_found" => __("No books found.", "textdomain"),
    "not_found_in_trash" => __("No books found in Trash.", "textdomain"),
  ];

  $args = [
    "labels" => $labels,
    "public" => true,
    "publicly_queryable" => true,
    "show_ui" => true,
    "show_in_menu" => true,
    "query_var" => true,
    "rewrite" => ["slug" => "book"],
    "capability_type" => "post", // change "post" to another name
    "has_archive" => true,
    "hierarchical" => false,
    "menu_position" => null,
    "supports" => [
      "title",
      "editor",
      "author",
      "thumbnail",
      "excerpt",
      "comments",
    ],
  ];

  register_post_type("book", $args);
}

add_action("init", "wpdocs_codex_book_init");
```

You can change the `"capability_type"->"post"` to a unique name such as `"capability_type"->"tests"`.

Now you can add capabilities to a new or existing `User Role`.

```php
<?php

$role = "my_new_role";
$role_display = "My New Role";

// Remove the role if it already exist (in case you need to update the capabilities)
if (get_role($role)) {
  remove_role($role);
}

add_role($role, $role_display, [
  "read" => true,
  "read_tests" => true,
  "read_private_tests" => true,
  "edit_tests" => true,
  "edit_others_tests" => true,
  "edit_published_tests" => true,
  "publish_tests" => true,
  "delete_others_tests" => true,
  "delete_private_tests" => true,
  "delete_published_tests" => true,
]);
```

## Caveats

Everytime you create a new User Role, a new row is added to the database. To update the capabilities, you need to remove the role and add it with the new capabilities again.

## Alternative option

You can use the [User Role Editor](https://wordpress.org/plugins/user-role-editor/)
