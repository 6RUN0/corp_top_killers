<?php

/**
 * $Id$
 * @author MrRx7
 * @maintainer boris_t (boris@talovikov.ru)
 * @copyright 2010
 */

class pCorpTopKillersSettings extends pageAssembly {

  public $page;
  private $limit;
  private $message = array();

  function __construct() {
    parent::__construct();
    $this->queue('start');
    $this->queue('form');
  }

  function start() {
    $this->page = new Page();
    $this->page->setTitle('Settings - Corp Top Killers');
    $this->page->addHeader('<link rel="stylesheet" type="text/css" href="' . KB_HOST . '/mods/corp_top_killers/style.css" />');

    $this->limit = config::get('corp_top_killers_limit');

    if (empty($this->limit)) {
      $this->limit = 10;
      config::set('corp_top_killers_limit', $this->limit);
    }

    if (isset($_POST['submit']) && isset($_POST['corp_top_killers_limit'])) {
      if($limit = $this->is_natural($_POST['corp_top_killers_limit'])) {
        $this->limit = $limit;
        config::set('corp_top_killers_limit', $this->limit);
      }
      else {
        $this->message = array(
          'text' => 'Limit it is natural numeric',
          'class' => 'corp-top-killers-err',
        );
      }
    }

  }

  function form() {
    global $smarty;
    $smarty->assign('corp_top_killers_limit', $this->limit);
    $smarty->assign('corp_top_killers_message', $this->message);
    return $smarty->fetch(get_tpl('./mods/corp_top_killers/corp_top_killers_settings'));
  }

  function context() {
    parent::__construct();
    $this->queue('menu');
  }

  function menu() {
    require_once('common/admin/admin_menu.php');
    return $menubox->generate();
  }

  private function is_natural($num) {
    $num = intval($num);
    if(is_int($num) && $num > 0) {
      return $num;
    }
    return FALSE;
  }

}

$pageAssembly = new pCorpTopKillersSettings();
event::call('pCorpTopKillersSettings_assembling', $pageAssembly);
$html = $pageAssembly->assemble();
$pageAssembly->page->setContent($html);

$pageAssembly->context();
event::call('pCorpTopKillersSettings_context_assembling', $pageAssembly);
$context = $pageAssembly->assemble();
$pageAssembly->page->addContext($context);

$pageAssembly->page->generate();
