import { useBlockProps } from '@wordpress/block-editor'

export default function save() {
  return (
    <div id="lehrstellenboerse" { ...useBlockProps.save() }/>
  )
}
