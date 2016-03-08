<?php
/**
* Walker_Nav_Menu_Dropdown class extending Walker_Nav_Menu class to change the li to options
*/
class Walker_Nav_Menu_Dropdown extends Walker_Nav_Menu{
	
	function start_lvl( &$output, $depth ){
		$indent = str_repeat( "\t", $depth ); // don't output children opening tag (`<ul>`)
	}

	function end_lvl( &$output, $depth ){
		$indent = str_repeat( "\t", $depth ); // don't output children closing tag
	}
	function start_el( &$output, $item, $depth, $args ) {
 		$url = '#' !== $item->url ? $item->url : '';
 		$output .= '<option class="menu-element col-xs-12 col-sm-12" value="' . $url . '">' . $item->title;
	}	

	function end_el( &$output, $item, $depth ){
		$output .= "</option>\n"; // replace closing </li> with the option tag
	}
}
?>