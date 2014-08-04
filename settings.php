<?php

/**
 * @author MrRx7
 * @maintainer boris_t (boris@talovikov.ru)
 * @copyright 2010
 * @version 1.1p1
 */

//requires
require_once("common/admin/admin_menu.php");

//page setup
$settings = new Page();
//are we admin?
if (!$settings->isAdmin()){
  trigger_error('You are not a admin and thus shouldn\'t be able to access this page', E_ERROR);
}
//rest of page setup // no caching and title
$settings->setCachable(false);
$settings->setTitle('Settings - Corp Top Killers');

//page submit?
if (isset($_POST['submit'])) 
{
  //update settings
  (isset($_POST['corp_top_killer_limit'])) ? config::set('corp_top_killer_limit', $_POST['corp_top_killer_limit']) : config::set('corp_top_killer_limit', 10);
}

//pull any settings and set defaults if there are none
$limit = config::get('corp_top_killer_limit');
if (empty($limit)) {
  config::set('corp_top_killer_limit', 10);
  $limit = 10;
}

$html = '
<form method="post" action="">
  <div><label for="corp_top_killer_limit">Corp Limit:</label></div>
  <div><input type="text" maxlength="4" size="5" value="' . $limit . '" name="corp_top_killer_limit" id="corp_top_killer_limit"/></div>
  <div><small>How many corps to show on top list (Defaults to 10)</small></div><br />
  <div><input type="submit" value="submit" name="submit"></div>
</form><br />';

$html .= '<div class="block-header2"></div><div align="right"><small>Corp Top Killers Mod (Version 1.1p1)<br />It\'s fork <a href="http://www.evekb.org/forum/viewtopic.php?f=505&t=18523">Corp Top Killers Mod</a></small></div>';

//dump to screen
$settings->setContent($html);
$settings->addContext($menubox->generate());
$settings->generate();
