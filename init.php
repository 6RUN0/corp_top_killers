<?php

/**
 * @author Andy Snowden
 * @maintainer boris_t (boris@talovikov.ru)
 * @copyright 2013
 * @version 1.1p1
 */
 
event::register("home_context_assembling", "corp_top_killers::add");

$modInfo['corp_top_killers']['name'] = 'Corp Top Killers';
$modInfo['corp_top_killers']['abstract'] = '';
$modInfo['corp_top_killers']['about'] = 'It\'s fork <a href="http://www.evekb.org/forum/viewtopic.php?f=505&t=18523">Corp Top Killers Mod</a>. Fork by <a href="https://github.com/6RUN0">boris_t</a>.<br />
<a href="https://github.com/6RUN0/corp_top_killers">Get Corp Top Killers</a>';


class corp_top_killers {

  function add($page) {
    global $sourcePage;
    $sourcePage = $page;
    $page->addBefore('topLists', 'corp_top_killers::show');
  }
  
  function show() {
    global $smarty;
    include_once('mods/corp_top_killers/corp_top_killers.php');
    $html .= $smarty->fetch('../../../mods/corp_top_killers/corp_top_killers.tpl');
    return $html;
  }

}
