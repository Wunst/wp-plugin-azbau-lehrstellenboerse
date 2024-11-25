<div <?php echo get_block_wrapper_attributes(); ?>>
<?php

# Dictionary for translating internal job names to gendered job names.
$genderDict = array( 
  "ABF Fliesenarbeiten" => null,
  "ABF Zimmerarbeiten" => null,
  "Betonbauer" => "Betonbauer*in",
  "Brunnenbauer" => "Brunnenbauer*in",
  "Fliesenleger" => "Fliesenleger*in",
  "HBF Betonarbeiten" => null,
  "HBF Maurerarbeiten" => null,
  "Kanalbauer" => "Kanalbauer*in",
  "Maurer" => "Maurer*in",
  "Straßenbauer" => "Straßenbauer*in",
  "TBF Brunnenbauarbeiten" => null,
  "TBF Kanalbauarbeiten" => null,
  "TBF Straßenbauarbeiten" => null,
  "Zimmerer" => "Zimmerer*in",
);

# Dictionary for translating internal job names to internship column names (if
# applicable).
$praktikumDict = array(
  "Fliesenleger" => "Praktikum Fliesenleger",
  "Maurer" => "Praktikum Maurer",
  "Zimmerer" => "Praktikum Zimmerer",
  "Straßenbauer" => "Praktikum Straßenbauer",
  "Kanalbauer" => "Praktikum Kanalbauer",
  "Betonbauer" => "Praktikum Stahl und Beton"
);

$countCols = array_keys($genderDict);

lsb_render_filter_form($countCols, $genderDict);

$filteredCols = array_filter($countCols, function($job) {
  $job = str_replace(" ", "_", $job);
  return array_key_exists($job, $_GET) && $_GET[$job] == "on";
});
$wantsJob = array_key_exists("wantsJob", $_GET) && $_GET["wantsJob"] == "on";
$wantsInternship = array_key_exists("wantsInternship", $_GET) &&
  $_GET["wantsInternship"] == "on";

# Only filter if at least one box checked.
$countCols = $filteredCols ? $filteredCols : $countCols;
if (!$wantsJob && !$wantsInternship) {
  $wantsJob = $wantsInternship = true;
}

# Only show rows with at least 1 open position and company has consented to be shown.
$data = array_filter(get_option("lsb_file"), function($row) use(
  $countCols, $praktikumDict, $wantsJob, $wantsInternship
) {
  if ($row["Ausbildungszentrum Bau"] != 1) {
    return false;
  }
  foreach ($countCols as $col) {
    if ($wantsJob && intval($row[$col]) != 0) {
      return true;
    }

    # Internship.
    if ($wantsInternship && array_key_exists($col, $praktikumDict) &&
      intval($row[$praktikumDict[$col]]) != 0
    ) {
      return true;
    }
  }
  return false;
});

lsb_render_table($countCols, $data, $genderDict, $praktikumDict, $wantsJob, 
  $wantsInternship);

function lsb_gender_translate($genderDict, $job) {
  $tl = $genderDict[$job];
  return $tl ? $tl : $job;
}

function lsb_checkbox($id, $label) {
  # Keep selection on reload.
  $checked = array_key_exists($id, $_GET) && $_GET[$id] == "on";

?>
  <div class="check">
    <input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $id; ?>"
      <?php echo $checked ? "checked" : ""; ?>>
    <label for="<?php echo $id; ?>"><?php echo $label; ?></label>
  </div>
<?php
}

# Displays the filter form.
function lsb_render_filter_form($countCols, $genderDict) {
?>
<details>
  <summary class="azbau-lehrstellenboerse-lsb-filter-head">
    Nach Beruf/Art filtern
  </summary>
  <form>
    <h3>Ich suche...</h3>
<?php
  lsb_checkbox("wantsJob", "einen Ausbildungsplatz");
  lsb_checkbox("wantsInternship", "ein Praktikum");
?>
    <h3>...als...</h3>
    <div class="azbau-lehrstellenboerse-lsb-filter-checks">
<?php
  foreach ($countCols as $job) {
    lsb_checkbox($job, lsb_gender_translate($genderDict, $job));
  }
?>
    </div>
    <input class="wp-block-button wp-element-button" type="submit" value="Filtern"/>
    <a class="wp-block-button wp-element-button" href="?">
      Filter löschen
    </a>
  </form>
</details>
<?php
}

# Displays the table with final prepared data.
function lsb_render_table(
  $countCols, $data, $genderDict, $praktikumDict, $wantsJob, $wantsInternship
) {
?>
  <table class="azbau-lehrstellenboerse-lsb-table">
    <tr class="azbau-lehrstellenboerse-lsb-table-headers">
      <th>Ausbildungsplätze</th>
      <th>Ort</th>
      <th>Betrieb/Ansprechpartner</th>
      <th>Kontakt</th>
    </tr>
  <?php foreach ($data as $row) { ?>
    <tr>
      <td class="azbau-lehrstellenboerse-lsb-td-positions">
        <?php lsb_render_positions($countCols, $row, $genderDict, 
          $praktikumDict, $wantsJob, $wantsInternship); ?>
      </td>
      <td class="azbau-lehrstellenboerse-lsb-td-place">
        <?php echo esc_html(intval($row["PLZ"])); ?> <?php echo esc_html($row["Ort"]); ?>
      </td>
      <td class="azbau-lehrstellenboerse-lsb-td-company">
        <?php lsb_render_company($row); ?>
      </td>
      <td class="azbau-lehrstellenboerse-lsb-td-contact">
        <?php lsb_render_contact($row); ?>
      </td>
  <?php } ?>
  </table>
<?php
}

# Displays open positions in company.
function lsb_render_positions(
  $countCols, $row, $genderDict, $praktikumDict, $wantsJob, $wantsInternship
) {
  foreach ($countCols as $col) {
    $cnt = intval($row[$col]);
    if ($wantsJob && $cnt != 0) {
      echo $cnt . "x " . lsb_gender_translate($genderDict, $col) . "<br/>";
    }

    # Internships.
    if ($wantsInternship && array_key_exists($col, $praktikumDict)) {
      $cnt = intval($row[$praktikumDict[$col]]);
      if ($cnt != 0) {
        echo $cnt . "x Praktikum als " . lsb_gender_translate($genderDict, $col) . "<br/>";
      }
    }
  }
}

# Displays company name and contact.
function lsb_render_company($row) {
  $url = $row["Internet"];
  if ($url) { 
?>
  <a href="<?php echo esc_url($url); ?>">
<?php 
  } 

  # Weird old and inconsistent DB format. New entries are usually of the form
  # ["Name 1"] = Company Name, ["Vorname"] = Contact's Full (!) Name, but
  # ["Name 2"] is still used in some old entries.
?>
    <?php echo esc_html($row["Name 1"]); ?> <br/>
    <?php echo esc_html($row["Vorname"]); ?> <br/>
    <?php echo esc_html($row["Name 2"]); ?> <br/>
<?php

  if ($url) {
?>
  </a>
<?php
  }
}

# Displays various contact info.
function lsb_render_contact($row) {
  lsb_render_contact_bit("tel:", "📞", $row["Telefon"]);
  lsb_render_contact_bit("tel:", "📱", $row["Handy"]);
  lsb_render_contact_bit("mailto:", "📧", $row["E-Mail"]);
}

# Displays a contact link, if present.
# @param linktype e. g. tel:, mailto:
# @param prefix Prefix character or characters
# @param contact Contact info itself
function lsb_render_contact_bit($linktype, $prefix, $contact) {
  if ($contact) {
    # TODO: Need to escape link?
?>
  <a href="<?php echo $linktype; ?><?php echo $contact; ?>">
    <?php echo esc_html($prefix); ?> <?php echo esc_html($contact); ?>
  </a>
  <br/>
<?php
  }
}

?>
</div>

