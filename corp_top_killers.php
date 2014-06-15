<?php

/**
 * @author Andy Snowden
 * @copyright 2013
 * @version 1.1
 */
#Settings
$allianceid = config::get('cfg_allianceid');
$limit = config::get('corp_top_killer_limit');
if (!isset($limit)) {
    config::set('corp_top_killer_limit', 10);
    $limit = 10;
}

global $sourcePage;

//Kill list
if ($sourcePage->getView() == 'losses'){
    $corptop = new TopList_CorpLosses();
    $smarty->assign('title', "Top Corp Losers");
    $corptop->addVictimAlliance($allianceid);
} else {
    $corptop = new TopList_CorpKills();
    $smarty->assign('title', "Top Corp Killers");
    $corptop->addInvolvedAlliance($allianceid);
}


$corptop->setPodsNoobShips(config::get('podnoobs'));
$sourcePage->loadTime($corptop);
$corptop->setLimit($limit);
$corptop->generate();

//awardbox (corp level)
$rows = array();
$max = 0;

for ($i = 1; $i <= $limit; $i++) {
    $row = $corptop->getRow();
    if ($row) {
        $rows[] = $row;
    }
    if ($row['cnt'] > $max) {
        $max = $row['cnt'];
    }
}

$corporation = new Corporation($rows[0]['crp_id']);
$smarty->assign('corp_portrait', $corporation->getPortraitURL(64));
$smarty->assign('award_img', config::get('cfg_img')."/awards/wing1.png");
$smarty->assign('url', edkURI::build(array('a', 'corp_detail', true), array('crp_id', $rows[0]['crp_id'], true)));
$smarty->assign('name', $corporation->getName());

$bar = new BarGraph($rows[0]['cnt'], $max);
$smarty->assign('bar', $bar->generate());
$smarty->assign('cnt', $rows[0]['cnt']);

for ($i = 2; $i < 11; $i++) {
    if (!$rows[$i - 1]['crp_id']) {
        break;
    } else if (!$rows[$i - 1]['crp_name']) {
        $corporation = new Corporation($rows[$i - 1]['crp_id']);
        $corporationname = $corporation->getName();
    } else {
        $corporationname = $rows[$i - 1]['crp_name'];
    }
    $bar = new BarGraph($rows[$i - 1]['cnt'], $max);
    $top[$i] = array(
        'url' => edkURI::build(array('a', 'corp_detail', true), array('crp_id', $rows[$i-1]['crp_id'], true)),
        'name' => $corporationname,
        'bar' => $bar->generate(),
        'cnt' => $rows[$i - 1]['cnt']);
}

$smarty->assign('top', $top);
$smarty->assign('comment', "kills in " .date('F, Y', mktime(0, 0, 0, getMonth_ctk(), 1, getYear_ctk())));

function getMonth_ctk(){
    if (isset($_GET['m'])){
        $month = $_GET['m'];
    } elseif($_GET['w']) {
        $month = date('m', mktime(0, 0, 0, 1, (($_GET['w']-1)*7)+1, getYear_ctk()));
    } else {
        $month = kbdate('m');
    }
    return $month;
}
function getYear_ctk(){
    if (isset($_GET['y'])){
        $year = $_GET['y'];
    } else {
        $year = kbdate('Y');
    }    
    return $year;
}

?>
