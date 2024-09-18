<div <?php echo get_block_wrapper_attributes(); ?>>
<?php

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

lsb_render_filter_form($countCols);

$filteredCols = array_filter($countCols, function($job) {
  $job = str_replace(" ", "_", $job);
  return array_key_exists($job, $_GET) && $_GET[$job] == "on";
});

# Only filter if at least one box checked.
$countCols = $filteredCols ? $filteredCols : $countCols;

# Only show rows with at least 1 open position and company has consented to be shown.
$data = array_filter(get_option("lsb_file"), function($row) use($countCols) {
  if ($row["Ausbildungszentrum Bau"] != 1) {
    return false;
  }
  foreach ($countCols as $col) {
    if (intval($row[$col]) != 0) {
      return true;
    }
  }
  return false;
});

lsb_render_table($countCols, $data);

# Displays the filter form.
function lsb_render_filter_form($countCols) {
?>
  <form>
    <div class="azbau-lehrstellenboerse-lsb-filter-checks">
  <?php foreach ($countCols as $job) { ?>
      <div class="check">
        <input type="checkbox" id="<?php echo $job; ?>" name="<?php echo $job ?>"/>
        <label for="<?php echo $job ?>"><?php echo $job ?></label>
      </div>
  <?php } ?>
    </div>
    <input class="wp-block-button wp-element-button" type="submit" value="Filtern"/>
    <a class="wp-block-button wp-element-button" href="?">
      Filter löschen
    </a>
  </form>
<?php
}

# Displays the table with final prepared data.
function lsb_render_table($countCols, $data) {
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
        <?php lsb_render_positions($countCols, $row); ?>
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
function lsb_render_positions($countCols, $row) {
  foreach ($countCols as $col) {
    $cnt = intval($row[$col]);
    if ($cnt != 0) {
      echo $cnt . "x " . $col . "<br/>";
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

