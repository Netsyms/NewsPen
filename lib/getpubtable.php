<?php

require_once __DIR__ . '/../required.php';

dieifnotloggedin();

header("Content-Type: application/json");

$out = [];

$out['draw'] = intval($VARS['draw']);

$out['recordsTotal'] = $database->count('publications');

$filter = false;

// sort
$order = null;
$sortby = "DESC";
if ($VARS['order'][0]['dir'] == 'asc') {
    $sortby = "ASC";
}
switch ($VARS['order'][0]['column']) {
    case 2:
        $order = ["pubname" => $sortby];
        break;
    case 3:
        $order = ["pubdate" => $sortby];
        break;
    case 5:
        $order = ["stylename" => $sortby];
        break;
    case 6:
        $order = ["columns" => $sortby];
        break;
    case 7:
        $order = ["permname" => $sortby];
        break;
}

// search
if (!is_empty($VARS['search']['value'])) {
    $filter = true;
    $wherenolimit = [];
    $wherenolimit["AND"]["OR"] = [
        "pubname[~]" => $VARS['search']['value'],
        "pubdate[~]" => $VARS['search']['value'],
        "stylename[~]" => $VARS['search']['value'],
        "permname[~]" => $VARS['search']['value']
    ];
    $where = $wherenolimit;
    $where["LIMIT"] = [$VARS['start'], $VARS['length']];
} else {
    $where = ["LIMIT" => [$VARS['start'], $VARS['length']]];
}
if (!is_null($order)) {
    $where["ORDER"] = $order;
}


$pubs = $database->select('publications', [
    '[>]pub_styles' => ['styleid' => 'styleid'],
    '[>]pub_permissions' => ['permid' => 'permid']
        ], [
    'pubid',
    'pubname',
    'uid',
    'pubdate',
    'stylename',
    'columns',
    'permname',
    'permid'
        ], $where);


$out['status'] = "OK";
if ($filter) {
    $recordsFiltered = $database->count('publications', [
        '[>]pub_styles' => ['styleid' => 'styleid'],
        '[>]pub_permissions' => ['permid' => 'permid']
            ], 'pubid', $wherenolimit);
} else {
    $recordsFiltered = $out['recordsTotal'];
}
$out['recordsFiltered'] = $recordsFiltered;

$usercache = [];
for ($i = 0; $i < count($pubs); $i++) {
    $pubs[$i]["editbtn"] = '<a class="btn btn-blue btn-xs" href="app.php?page=editpub&id=' . $pubs[$i]['pubid'] . '"><i class="fa fa-pencil-square-o"></i> ' . lang("edit", false) . '</a>';
    $pubs[$i]["clonebtn"] = '<a class="btn btn-green btn-xs" href="app.php?page=editpub&id=' . $pubs[$i]['pubid'] . '&clone=1"><i class="fa fa-clone"></i> ' . lang("clone", false) . '</a>';
    if (is_null($pubs[$i]['uid'])) {
        $pubs[$i]["username"] = "";
    } else {
        if (!isset($usercache[$pubs[$i]['uid']])) {
            $usercache[$pubs[$i]['uid']] = getUserByID($pubs[$i]['uid']);
        }
        $pubs[$i]["username"] = $usercache[$pubs[$i]['uid']]['name'];
    }
}
$out['pubs'] = $pubs;

echo json_encode($out);
