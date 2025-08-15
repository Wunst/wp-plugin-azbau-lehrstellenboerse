<div <?php echo get_block_wrapper_attributes(); ?>>
<?php
require_once (LSB_PLUGDIR . "/includes/render_utils.php");
lsb_render_table($countCols, $data, $genderDict, $praktikumDict, $wantsJob, 
  $wantsInternship);
?>
</div>

