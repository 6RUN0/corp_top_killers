<?php

/**
 * $Id$
 * @author Andy Snowden
 * @maintainer boris_t (boris@talovikov.ru)
 * @copyright 2013
 */

class corp_top_killers {

  static function add($pHome) {
    $pHome->addBefore('topLists', 'corp_top_killers::show');
  }

  static function show($pHome) {

    global $smarty;

    $pilot_id = config::get('cfg_pilotid');
    $corp_id = config::get('cfg_corpid');
    $alliance_id = config::get('cfg_allianceid');
    $limit = config::get('corp_top_killers_limit');

    if (empty($limit)) {
      config::set('corp_top_killers_limit', 10);
      $limit = 10;
    }

    if ($pHome->getView() == 'losses') {
      $corptop = new TopList_CorpLosses();
      if(!empty($alliance_id)) {
        $corptop->addVictimAlliance($alliance_id);
      }
      if(!empty($corp_id)) {
        $corptop->addVictimCorp($corp_id);
      }
      if(!empty($pilot_id)) {
        $corptop->addVictimPilot($pilot_id);
      }
      $smarty->assign('title', 'Top Corp Losers');
      $award_img = '/awards/moon.png';
    }
    else {
      $corptop = new TopList_CorpKills();
      if(!empty($alliance_id)) {
        $corptop->addInvolvedAlliance($alliance_id);
      }
      if(!empty($corp_id)) {
        $corptop->addInvolvedCorp($corp_id);
      }
      if(!empty($pilot_id)) {
        $corptop->addInvolvedPilot($pilot_id);
      }
      $smarty->assign('title', 'Top Corp Killers');
      $award_img = '/awards/eagle.png';
    }

    $corptop->setPodsNoobShips(config::get('podnoobs'));
    $pHome->loadTime($corptop);
    $corptop->setLimit($limit);
    $corptop->generate();

    $row = $corptop->getRow();

    if(isset($row)) {
      $max_kl = $row['cnt'];
      $corporation = new Corporation($row['crp_id']);
      $bar = new BarGraph($row['cnt'], $max_kl);

      $smarty->assign('corp_portrait', $corporation->getPortraitURL(64));
      $smarty->assign('award_img', config::get('cfg_img') . $award_img);
      $smarty->assign('url', $corporation->getDetailsURL());
      $smarty->assign('name', $corporation->getName());
      $smarty->assign('bar', $bar->generate());
      $smarty->assign('cnt', $row['cnt']);

      $i = 2;

      while(($row = $corptop->getRow())) {
        $corporation = new Corporation($row['crp_id']);
        $bar = new BarGraph($row['cnt'], $max_kl);
        $top[$i] = array(
          'url' => $corporation->getDetailsURL(),
          'name' => $corporation->getName(),
          'bar' => $bar->generate(),
          'cnt' => $row['cnt'],
        );
        $i++;
      }

      $smarty->assign('top', $top);
      $smarty->assign('comment', 'kills in ' . self::getDateStr($pHome->getYear(), $pHome->getMonth(), $pHome->getWeek()));
      return $smarty->fetch(get_tpl('./mods/corp_top_killers/corp_top_killers'));
    }

  }

  private static function getDateStr($year, $month = 0, $week = 0) {

    if(!empty($month)) {
      $date = date_create("${year}-${month}");
      return date_format($date, 'F, Y');
    }
    if(!empty($week)) {
      return "Week ${week}, ${year}";
    }

  }

}
