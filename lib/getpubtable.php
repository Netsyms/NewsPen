<?php

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */


require_once __DIR__ . '/../required.php';
require_once __DIR__ . '/userinfo.php';

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
        $order = ["style" => $sortby];
        break;
    case 6:
        $order = ["columns" => $sortby];
        break;
    case 7:
        $order = ["sizename" => $sortby];
        break;
    case 8:
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
        "style[~]" => $VARS['search']['value'],
        "sizename[~]" => $VARS['search']['value'],
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

$where["OR #perms"] = [
    "uid" => $_SESSION['uid'],
    "permname #logg" => "LOGGEDIN",
    "permname #link" => "LINK"
];

//var_dump($where);

$pubs = $database->select('publications', [
    '[>]pub_permissions' => ['permid' => 'permid'],
    '[>]page_sizes' => ['page_size' => 'sizeid']
        ], [
    'pubid',
    'pubname',
    'uid',
    'pubdate',
    'style',
    'columns',
    'permname',
    'pwd',
    'publications.permid',
    "page_size",
    "sizename",
    "landscape"
        ], $where);


$out['status'] = "OK";
if ($filter) {
    $recordsFiltered = $database->count('publications', [
        '[>]pub_permissions' => ['permid' => 'permid']
            ], 'pubid', $wherenolimit);
} else {
    $recordsFiltered = $out['recordsTotal'];
}
$out['recordsFiltered'] = $recordsFiltered;

$usercache = [];
for ($i = 0; $i < count($pubs); $i++) {
    if ($pubs[$i]["uid"] == $_SESSION['uid']) {
        $pubs[$i]["editbtn"] = '<a class="btn btn-primary btn-sm" href="app.php?page=editpub&id=' . $pubs[$i]['pubid'] . '"><i class="fas fa-edit"></i> ' . lang("edit", false) . '</a>';
    } else {
        $pubs[$i]["editbtn"] = '<a class="btn btn-purple btn-sm" href="app.php?page=content&pubid=' . $pubs[$i]['pubid'] . '"><i class="fas fa-eye"></i> ' . lang("view", false) . '</a>';
    }
    $pubs[$i]["clonebtn"] = '<a class="btn btn-success btn-sm" href="app.php?page=editpub&id=' . $pubs[$i]['pubid'] . '&clone=1"><i class="fas fa-clone"></i> ' . lang("clone", false) . '</a>';
    $pubs[$i]["pubdate"] = date(DATETIME_FORMAT, strtotime($pubs[$i]["pubdate"]));
    if (is_null($pubs[$i]['uid'])) {
        $pubs[$i]["username"] = "";
    } else {
        if (!isset($usercache[$pubs[$i]['uid']])) {
            $usercache[$pubs[$i]['uid']] = getUserByID($pubs[$i]['uid']);
        }
        $pubs[$i]["username"] = $usercache[$pubs[$i]['uid']]['name'];
    }
    $pubs[$i]["pagesize"] = lang2("page size and orientation", [
        "size" => $pubs[$i]["sizename"],
        "orientation" => ( $pubs[$i]["landscape"] == 0 ? lang("portrait", false) : lang("landscape", false) )
            ], false);
    if (!is_empty($pubs[$i]["pwd"])) {
        $pubs[$i]["permname"] = "password";
    }
    $pubs[$i]["visibility"] = lang("visibility " . strtolower($pubs[$i]["permname"]), false);
}
$out['pubs'] = $pubs;

echo json_encode($out);
