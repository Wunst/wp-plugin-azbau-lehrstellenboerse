<?php
/**
 * Returns a list of all companies with free positions that have agreed to be 
 * displayed. A slightly less WTF-y wrapper around the raw CSV data for the
 * frontend to work with.
 *
 * ## Response
 * ```json
 * [
 *   {
 *     "name": "Musterbetrieb Inh. Max Mustermann",
 *     "plz": "24123",
 *     "city": "Musterstadt",
 *     "url": "https://musterbau.invalid",
 *     "contact": {
 *       "phone": "+49123456789",
 *       "cell": "+49176789123",
 *       "email": "info@musterbau.invalid"
 *     },
 *     "positions": {
 *       "ausbildung": {
 *         "fliesenleger": 1,
 *         "abf_fliesenarbeiten": 1,
 *         // ...
 *       },
 *       "praktikum": {
 *         "fliesenleger": 0,
 *         // ...
 *       }
 *     }
 *   },
 *   // ...
 * ]
 * ```
 */
function lsb_betriebe (WP_REST_Request $req) {
  $data = get_option ("lsb_file");

  $data_to_display = array_filter($data, function ($row) {
    // Consent to be displayed.
    if ($row["Ausbildungszentrum Bau"] != 1) {
      return false;
    }

    // Only display companies with open positions.
    $position_cols = array(
      "ABF Fliesenarbeiten",
      "ABF Zimmerarbeiten",
      "Betonbauer",
      "Fliesenleger",
      "HBF Betonarbeiten",
      "HBF Maurerarbeiten",
      "Kanalbauer",
      "Maurer",
      "Straßenbauer",
      "TBF Kanalbauarbeiten",
      "TBF Straßenbauarbeiten",
      "Zimmerer",
      "Praktikum Fliesenleger",
      "Praktikum Maurer",
      "Praktikum Zimmerer",
      "Praktikum Straßenbauer",
      "Praktikum Kanalbauer",
      "Praktikum Stahl und Beton",
    );
    foreach ($position_cols as $col) {
      if ($row[$col] != 0) {
        return true;
      }
    }
    
    return false;
  });

  return array_values(array_map(function ($row) {
    return array(
      // Weird old and inconsistent DB format. New entries are usually of the 
      // form `Name 1` = company name, `Vorname` = contact's full (!) name, but
      // `Name 2` is still used in some old entries.
      "name" => $row["Name 1"] . "\n" . $row["Vorname"] . "\n" . $row["Name 2"],

      "plz" => $row["PLZ"],
      "city" => $row["Ort"],

      "url" => esc_url ($row["Internet"]),

      "contact" => array(
        "phone" => $row["Telefon"],
        "cell" => $row["Handy"],
        "email" => $row["E-Mail"],
      ),

      "positions" => array(
        "ausbildung" => array_map("intval", array(
          "abf_fliesenarbeiten" => $row["ABF Fliesenarbeiten"],
          "abf_zimmerarbeiten" => $row["ABF Zimmerarbeiten"],
          "betonbauer" => $row["Betonbauer"],
          "fliesenleger" => $row["Fliesenleger"],
          "hbf_betonarbeiten" => $row["HBF Betonarbeiten"],
          "hbf_maurerarbeiten" => $row["HBF Maurerarbeiten"],
          "kanalbauer" => $row["Kanalbauer"],
          "maurer" => $row["Maurer"],
          "strassenbauer" => $row["Straßenbauer"],
          "tbf_kanalbauarbeiten" => $row["TBF Kanalbauarbeiten"],
          "tbf_strassenbauarbeiten" => $row["TBF Straßenbauarbeiten"],
          "zimmerer" => $row["Zimmerer"]
        )),

        "praktikum" => array_map("intval", array(
          "fliesenleger" => $row["Praktikum Fliesenleger"],
          "maurer" => $row["Praktikum Maurer"],
          "zimmerer" => $row["Praktikum Zimmerer"],
          "strassenbauer" => $row["Praktikum Straßenbauer"],
          "kanalbauer" => $row["Praktikum Kanalbauer"],
          "betonbauer" => $row["Praktikum Stahl und Beton"],
        )),
      ),
    );
  }, $data_to_display));
}

function lsb_rest_api_init () {
  register_rest_route ("lehrstellenboerse/v1", "/betriebe", array(
    "methods" => "GET",
    "callback" => "lsb_betriebe",
    "permission_callback" => function () {
      return true;
    },
  ));
}

add_action ("rest_api_init", "lsb_rest_api_init");

