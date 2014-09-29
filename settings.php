<?php

/**
 * @author MrRx7
 * @maintainer boris_t (boris@talovikov.ru)
 * @copyright 2010
 * @version 1.1p3
 */

class pCorpTopKillersSettings extends pageAssembly {

  public $page;
  private $limit;
  private $version = '1.1p3';

  function __construct() {
    parent::__construct();
    $this->queue('start');
    $this->queue('form');
  }

  function start() {
    $this->page = new Page();
    $this->page->setTitle('Settings - Corp Top Killers');
    $this->limit = config::get('corp_top_killers_limit');

    if (empty($this->limit)) {
      $this->limit = 10;
      config::set('corp_top_killers_limit', $this->limit);
    }

    if (isset($_POST['submit']) && isset($_POST['corp_top_killers_limit'])) {
      $this->limit = $_POST['corp_top_killers_limit'];
      config::set('corp_top_killers_limit', $this->limit);
    }
  }

  function form() {
    global $smarty;
    $smarty->assign('corp_top_killers_limit', $this->limit);
    $smarty->assign('corp_top_killers_version', $this->version);
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
