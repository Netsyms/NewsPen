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

$out['recordsTotal'] = $database->count('mail_lists');

$filter = false;

// sort
$order = null;
$sortby = "DESC";
if ($VARS['order'][0]['dir'] == 'asc') {
    $sortby = "ASC";
}
switch ($VARS['order'][0]['column']) {
    case 2:
        $order = ["listname" => $sortby];
        break;
}

// search
if (!is_empty($VARS['search']['value'])) {
    $filter = true;
    $wherenolimit = [];
    $wherenolimit["AND"]["OR"] = [
        "listname[~]" => $VARS['search']['value']
    ];
    $where = $wherenolimit;
    $where["LIMIT"] = [$VARS['start'], $VARS['length']];
} else {
    $where = ["LIMIT" => [$VARS['start'], $VARS['length']]];
}
if (!is_null($order)) {
    $where["ORDER"] = $order;
}

/*$where["OR #perms"] = [
    "uid" => $_SESSION['uid'],
    "permname #logg" => "LOGGEDIN",
    "permname #link" => "LINK"
];*/

//var_dump($where);

$lists = $database->select('mail_lists',
        [
    'listid',
    'listname',
    'uid'
        ], $where);


$out['status'] = "OK";
if ($filter) {
    $recordsFiltered = $database->count('mail_lists', $wherenolimit);
} else {
    $recordsFiltered = $out['recordsTotal'];
}
$out['recordsFiltered'] = $recordsFiltered;

$usercache = [];
for ($i = 0; $i < count($lists); $i++) {
    if ($lists[$i]["uid"] == $_SESSION['uid']) {
        $lists[$i]["editbtn"] = '<a class="btn btn-primary btn-sm" href="app.php?page=editlist&id=' . $lists[$i]['listid'] . '"><i class="fas fa-edit"></i> ' . lang("edit", false) . '</a>';
    } else {
        $lists[$i]["editbtn"] = '<a class="btn btn-purple btn-sm" href="app.php?page=viewlist&id=' . $lists[$i]['listid'] . '"><i class="fas fa-eye"></i> ' . lang("view", false) . '</a>';
    }
    $lists[$i]["clonebtn"] = '<a class="btn btn-success btn-sm" href="app.php?page=editlist&id=' . $lists[$i]['listid'] . '&clone=1"><i class="fas fa-clone"></i> ' . lang("clone", false) . '</a>';
    if (is_null($lists[$i]['uid'])) {
        $lists[$i]["username"] = "";
    } else {
        if (!isset($usercache[$lists[$i]['uid']])) {
            $usercache[$lists[$i]['uid']] = getUserByID($lists[$i]['uid']);
        }
        $lists[$i]["username"] = $usercache[$lists[$i]['uid']]['name'];
    }
    $lists[$i]['count'] = $database->count('addresses', ['listid' => $lists[$i]["listid"]]);
}
$out['lists'] = $lists;

echo json_encode($out);
