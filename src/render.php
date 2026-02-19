<div <?php echo get_block_wrapper_attributes(); ?>>
<?php

// 1. Get and filter data.
$data = get_option( "lsb_file" );
$data = array_filter( $data, function ($company) {
  // Check company consent.
  if ( !$company["Ausbildungszentrum Bau"] )
    return false;

  // Check for at least one open position.
  foreach ( LSB_ALL_JOB_COLUMNS as $job_col ) {
    if ( $company[ $job_col ] )
      return true;
  }
  return false;
} );

// 2. Render form for filtering.
echo '<div id="lehrstellenboerse-filter"></div>';

// 3. List with all companies.
echo '<div id="lehrstellenboerse">';
echo '<ul class="list">';
foreach ( $data as $company ) {
  echo <<<END
    <li>
      <h4 class="name">
        {$company[ "Name 1" ]}<br/>
        {$company[ "Vorname" ]} {$company[ "Name 2" ]}
      </h4>
      <address>
        <span class="plz">{$company[ "PLZ" ]}</span> <span class="city">{$company[ "Ort" ]}</span>
      </address>
      <ul class="contact">
        <li>
          <a href="http://{$company[ "Internet" ]}">{$company[ "Internet" ]}</a>
        </li>
        <li>
          <a href="mailto:{$company[ "E-Mail" ]}">{$company[ "E-Mail" ]}</a>
        </li>
        <li>
          <a href="tel:{$company[ "Telefon" ]}">{$company[ "Telefon" ]}</a>
        </li>
      </ul>
      <h5>Offene Stellen</h5>
      <ul class="positions">
  END;

  // Open positions. Only render columns actually present.
  foreach ( LSB_JOB_COLUMNS as $job_col ) {
    if ( !$company[ $job_col ] )
      continue;

    $name = LSB_GENDER_DICT[ $job_col ] ?? $job_col;
    echo "<li>{$company[ $job_col ]}× {$name}</li>";
  }
  echo '</ul>';

  // Open internships.
  echo '<ul class="internships">';
  foreach ( LSB_INTERNSHIP_COLUMNS as $job_col ) {
    if ( !$company[ $job_col ] )
      continue;

    $name = LSB_GENDER_DICT[ $job_col ] ?? $job_col;
    echo "<li>{$company[ $job_col ]}× {$name}</li>";
  }
  echo '</ul>';
}

?>
</ul>
</div>
</div>
