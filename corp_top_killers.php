<?php

/**
 * @author Andy Snowden
 * @maintainer boris_t (boris@talovikov.ru)
 * @copyright 2013
 * @version 1.1p1
 */

$pilot_id = config::get('cfg_pilotid');
$corp_id = config::get('cfg_corpid');
$alliance_id = config::get('cfg_allianceid');
$limit = config::get('corp_top_killer_limit');

if (empty($limit)) {
  config::set('corp_top_killer_limit', 10);
  $limit = 10;
}

global $sourcePage;

if ($sourcePage->getView() == 'losses'){
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
$sourcePage->loadTime($corptop);
$corptop->setLimit($limit);
$corptop->generate();

$row = $corptop->getRow();
$max_kl = $row['cnt'];
$corporation = new Corporation($row['crp_id']);
$bar = new BarGraph($row['cnt'], $max_kl);

$smarty->assign('corp_portrait', $corporation->getPortraitURL(64));
$smarty->assign('award_img', config::get('cfg_img') . $award_img);
$smarty->assign('url', $corporation->getDetailsURL());
$smarty->assign('name', $corporation->getName());
$smarty->assign('bar', $bar->generate());
$smarty->assign('cnt', $row['cnt']);

$i = 1;

while(($row = $corptop->getRow()) && ($i < $limit)) {
  $corporation = new Corporation($row['crp_id']);
  $bar = new BarGraph($row['cnt'], $max_kl);
  $top[$i + 1] = array(
    'url' => $corporation->getDetailsURL(),
    'name' => $corporation->getName(),
    'bar' => $bar->generate(),
    'cnt' => $row['cnt'],
  );
  $i++;
}

$smarty->assign('top', $top);
$smarty->assign('comment', "kills in " .date('F, Y', mktime(0, 0, 0, getMonth_ctk(), 1, getYear_ctk())));

function getMonth_ctk() {
  $month = edkURI::getArg('m');
  $week = edkURI::getArg('w');
  if (!empty($month)) {
    return $month;
  }
  elseif(!empty($week)) {
    return date('m', mktime(0, 0, 0, 1, (($week - 1) * 7) + 1, getYear_atk()));
  }
  else {
    return kbdate('m');
  }
}

function getYear_ctk() {
  $year = edkURI::getArg('y');
  if(empty($year)) {
    $year = kbdate('Y');
  }
  return $year;
}
