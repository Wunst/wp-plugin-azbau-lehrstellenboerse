import { registerBlockType } from "@wordpress/blocks"

import Lehrstellenboerse from "./Lehrstellenboerse";
import save from "./save"
import metadata from "./block.json"

registerBlockType(metadata.name, {
  edit: () => <Lehrstellenboerse />, 
  save
})
