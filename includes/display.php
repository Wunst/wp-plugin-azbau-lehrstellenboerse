<?php

add_action("init", "lsb_register_block");

function lsb_register_block() {
  register_block_type(
    LSB_PLUGDIR . "/build"
  );
}

