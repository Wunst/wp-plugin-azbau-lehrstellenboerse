<?php

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

  register_setting("lsb", "lsb_file");

  add_menu_page(
    "Lehrstellenbörse",
    "Lehrstellenbörse",
    "publish_posts",
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
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    lsb_handle_file_upload();
  }

  ?>
  <div class="wrap">
    <h1>Lehrstellenbörse</h1>
    <form action="" enctype="multipart/form-data" method="post">
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

function lsb_handle_file_upload() {
  if (!current_user_can("publish_posts")) {
    status_header(403);
    die;
  }

  if (!empty($_FILES["lsb_file"]["tmp_name"])) {
    $csv = new \ParseCsv\Csv();
    $csv->delimiter = ";";
    $csv->parseFile($_FILES["lsb_file"]["tmp_name"]);

    # Reject CSV if schema does not match.
    if ( array_diff( LSB_ALL_COLUMNS , $csv->titles ) ) {
      add_settings_error(     
        "lsb_file",
        "invalid_csv_schema",
        "Ungültige CSV. Sind Sie sicher, dass Sie die richtige Datenbankdatei hochgeladen haben?"
      );
      return;
    }

    $data = $csv->data;

    // Sanitize job columns and PLZ, which for some reasons are stored as string representations of 
    // floating point numbers...
    foreach ( $data as $idx => $row ) {
      $data[ $idx ][ "PLZ" ] = intval( $row[ "PLZ" ] );
      foreach ( LSB_JOB_COLUMNS as $job_col ) {
        $data[ $idx ][ $job_col ] = intval( $row[ $job_col ] );
      }
    }
    update_option("lsb_file", $data);
  }
}

