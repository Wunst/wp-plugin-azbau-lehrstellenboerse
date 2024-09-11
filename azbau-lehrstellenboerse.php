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
  add_settings_section("lsb", "Lehrstellenbörse", null, "lsb");

  add_settings_field(
    "lsb_file",
    "Datei",
    "lsb_file_html",
    "lsb",
    "lsb"
  );

  register_setting("lsb", "lsb_file", "lsb_handle_file_upload");

  add_menu_page(
    "Lehrstellenbörse",
    "Lehrstellenbörse",
    "edit_posts",
    "lehrstellenboerse",
    "lsb_upload_page_html"
  );
}

function lsb_file_html() {
  ?>
    <input type="file" accept=".csv" name="lsb_file" />
  <?php
}

function lsb_upload_page_html() {
  ?>
  <div class="wrap">
    <h1>Lehrstellenbörse</h1>
    <form action="options.php" enctype="multipart/form-data" method="post">
      <?php
        settings_fields("lsb");

        do_settings_sections("lsb");

        submit_button();
      ?>
    </form>
  </div>
  <?php
}

function lsb_handle_file_upload($option) {
  if (!empty($_FILES["lsb_file"]["tmp_name"])) {
    $csv = array_map("str_getcsv", file($_FILES["lsb_file"]["tmp_name"]));
    return $csv;
  }
  return $option;
}

?>

