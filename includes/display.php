<?php

add_action("init", "lsb_register_block");

function lsb_register_block() {
  wp_register_script(
    "azbau-lsb-block-editor",
    LSB_PLUGURL . "/lsb/index.js",
    array(
      "wp-block-editor",
      "wp-blocks",
      "wp-element"
    ),
    filemtime(LSB_PLUGDIR . "/lsb/index.js")
  );
  register_block_type(
    LSB_PLUGDIR . "/lsb",
    array(
      "editor_script" => "azbau-lsb-block-editor"
    )
  );
}

