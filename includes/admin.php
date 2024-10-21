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
      return;
    }
    
    update_option("lsb_file", $csv->data);
  }
}

