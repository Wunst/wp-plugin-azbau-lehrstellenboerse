<?php

/*
 * Plugin Name: Lehrstellenbörse
 * Author: Ben Matthies
 * Author URI: https://github.com/Wunst
 * Version: 1.0.0
 * Requires at least: 6.2
 * Requires PHP: 8.2
 */

require_once __DIR__ . '/vendor/autoload.php';

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

        settings_errors("lsb_file");
        do_settings_sections("lsb");

        submit_button();
      ?>
    </form>
  </div>
  <?php
}

function lsb_handle_file_upload($option) {
  if (!empty($_FILES["lsb_file"]["tmp_name"])) {
    $csv = new \ParseCsv\Csv();
    $csv->delimiter = ";";
    $csv->parseFile($_FILES["lsb_file"]["tmp_name"]);

    # Reject CSV if schema does not match.
    if (array_diff(array(
      "Name 1", 
      "Vorname", 
      "Name 2",
      "Telefon",
      "Handy",
      "Maurer",
      "Zimmerer",
      "Fliesenleger",
      "Betonbauer",
      "Straßenbauer",
      "Kanalbauer",
      "HBF Maurerarbeiten",
      "ABF Zimmerarbeiten",
      "ABF Fliesenarbeiten",
      "HBF Betonarbeiten",
      "TBF Straßenbauarbeiten",
      "TBF Kanalbauarbeiten",
      "TBF Brunnenbauarbeiten",
      "Brunnenbauer",
      "Wärme-, Kälte-, Schallschutzisolierer",
      "ABF Wärme, Kälte-Schallschutz-Arbeiten"
    ), $csv->titles)) {
      add_settings_error(     
        "lsb_file",
        "invalid_csv_schema",
        "Ungültige CSV. Sind Sie sicher, dass Sie die richtige Datenbankdatei hochgeladen haben?"
      );
      return $option;
    }
    
    return $csv->data;
  }
  return $option;
}

?>

