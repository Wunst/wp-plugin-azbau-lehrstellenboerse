<div <?php echo get_block_wrapper_attributes(); ?>>
<div class="wp-block-stack">
<form action="?filter=true">
<?php

# Spalten, die Ausbildungsplätze enthalten.
$countCols = array( 
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
);
?>
  <input type="submit" value="Filtern"/>
</form>
<div class="wp-block-group">
<table>
  <tr>
    <th>Ausbildungsplätze</th>
    <th>Ort</th>
    <th>Betrieb/Ansprechpartner</th>
    <th>Kontakt</th>
  </tr>
<?php
# Ausbildungsberufe filtern.
$countCols = array_filter($countCols, function($job) {
  return !array_key_exists("filter", $_GET) ||
    array_key_exists($job, $_GET) && $_GET[$job] == "on";
});

$data = get_option("lsb_file");

foreach ($data as $row) {
  # Nur Zeilen mit mind. 1 Ausbildungsplatz darstellen.
  $render = false;
  foreach ($countCols as $col) {
    $cnt = intval($row[$col]);
    if ($cnt != 0) {
      $render = true;
      break;
    }
  }
  if (!$render) continue;

?>
  <tr>
    <td><?php

  # Ausbildungsplätze.
  foreach ($countCols as $col) {
    $cnt = intval($row[$col]);
    if ($cnt != 0) {
      echo $cnt . "x " . $col . "<br/>";
    }
  }
  echo "</td><td>";

  # Ort.
  $plz = intval($row["PLZ"]);
  echo $plz . " " . esc_html($row["Ort"]);
  echo "</td><td>";

  # Betrieb.
  $url = $row["Internet"];
  if ($url) {
    echo '<a href="' . esc_url($url) . '">';
  }
  echo esc_html($row["Name 1"]) . "<br/>" . 
    esc_html($row["Vorname"]) . "<br/>" . 
    esc_html($row["Name 2"]);
  if ($url) {
    echo "</a>";
  }
  echo "</td><td>";

  # Kontakt.
  $tel = $row["Telefon"];
  if ($tel) {
    echo 'Tel. <a href="tel:' . $tel . '">' . $tel . "</a><br/>";
  }

  $mobile = $row["Handy"];
  if ($mobile) {
    echo 'Handy <a href="tel:' . $mobile . '">' . $mobile . "</a><br/>";
  }

  $mail = $row["E-Mail"];
  if ($mail) {
    echo 'Email <a href="mailto:' . $mail . '">' . $mail . "</a><br/>";
  }

    ?></td>
  </tr>
<?php
}
?>
</table>
</div>

