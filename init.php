<?php

/**
 * @author Andy Snowden
 * @copyright 2013
 * @version 1.1
 */
 
event::register("home_context_assembling", "corp_top_killers::add");

class corp_top_killers {

	function add($page)
	{
		global $sourcePage;
		$sourcePage = $page;
		$page->addBefore("topLists", "corp_top_killers::show");
	}
  
  function show(){
  	global $smarty;
 	include_once('mods/corp_top_killers/corp_top_killers.php');
  	$html .= $smarty->fetch("../../../mods/corp_top_killers/corp_top_killers.tpl");
    return $html;
  }
}

