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
        "capability_type" => "book", // capability book is added to the post
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

/**
 * Create a new role to manage the posts in "Books" custom post type
 * 
 */

$role = "librarian";
$role_display = "Librarian";

// Remove the role if it already exist (in case you need to update the capabilities)
if (get_role($role)) {
    remove_role($role);
}

add_role($role, $role_display, array(
    "read" => true,
    "read_books" => true, // read Books
    "read_private_books" => true, // read private Books
    "edit_books" => true, // edit Books
    "edit_others_books" => true, // edit other users published Books
    "edit_published_books" => true, // edit published Books
    "publish_books" => true, // publish Books
    "delete_others_books" => true, // Delete other users Books
    "delete_private_books" => true, // Delete private Books
    "delete_published_books" => true, // Delete published Books
));
