<?php

/*
 * Plugin Name: Lehrstellenbörse
 * Author: Ben Matthies
 * Author URI: https://github.com/Wunst
 * Version: 1.0.0
 * Requires at least: 6.2
 * Requires PHP: 8.2
 */

add_action("admin_menu", "lsb_upload_page");

function lsb_upload_page() {
  add_menu_page(
    "Lehrstellenbörse",
    "Lehrstellenbörse",
    "edit_posts",
    plugin(__FILE__) .. "admin/view.php"
  );
}

?>

