<?php

/*
 * Plugin Name: Lehrstellenbörse
 * Plugin URI: https://github.com/Wunst/wp-plugin-azbau-lehrstellenboerse
 * Author: Ben Matthies
 * Author URI: https://github.com/Wunst
 * Version: 3.0.1
 * Update URI: false
 * GitHub Plugin URI: Wunst/wp-plugin-azbau-lehrstellenboerse
 * Primary Branch: main
 * Release Asset: true
 * Requires at least: 6.2
 * Requires PHP: 8.2
 */

define( "LSB_PLUGDIR", plugin_dir_path(__FILE__) );
define( "LSB_PLUGURL", plugin_dir_url(__FILE__) );

define( "LSB_INTERNSHIP_COLUMNS", array(
  "Praktikum Fliesenleger",
  "Praktikum Maurer",
  "Praktikum Zimmerer",
  "Praktikum Straßenbauer",
  "Praktikum Kanalbauer",
  "Praktikum Stahl und Beton",
) );
define( "LSB_JOB_COLUMNS", array(
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
) );

define( "LSB_ALL_JOB_COLUMNS", array_merge( LSB_INTERNSHIP_COLUMNS, LSB_JOB_COLUMNS ) );
define( "LSB_ALL_COLUMNS", array_merge ( LSB_ALL_JOB_COLUMNS,
  array(
    "Name 1", 
    "Vorname", 
    "Name 2",
    "Telefon",
    "Internet",
  )
));

/**
 * Translate job column names to gendered job titles.
 */
define( "LSB_GENDER_DICT", array(
  "Maurer" => "Maurer*in",
  "Zimmerer" => "Zimmerer*in",
  "Fliesenleger" => "Fliesenleger*in",
  "Betonbauer" => "Stahl- und Betonbauer*in",
  "Straßenbauer" => "Straßenbauer*in",
  "Kanalbauer" => "Kanalbauer*in",
  "Praktikum Fliesenleger" => "Praktikum als Fliesenleger*in",
  "Praktikum Maurer" => "Praktikum als Maurer*in",
  "Praktikum Zimmerer" => "Praktikum als Zimmerer*in",
  "Praktikum Straßenbauer" => "Praktikum als Straßenbauer*in",
  "Praktikum Kanalbauer" => "Praktikum als Kanalbauer*in",
  "Praktikum Stahl und Beton" => "Praktikum als Stahl- und Betonbauer*in",
) );

require_once __DIR__ . '/vendor/autoload.php';

require_once(LSB_PLUGDIR . "/includes/admin.php");
require_once(LSB_PLUGDIR . "/includes/display.php");

