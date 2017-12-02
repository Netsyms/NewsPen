<?php

/**
 * Make things happen when buttons are pressed and forms submitted.
 */
require_once __DIR__ . "/required.php";

if ($VARS['action'] !== "signout") {
    dieifnotloggedin();
}

/**
 * Redirects back to the page ID in $_POST/$_GET['source'] with the given message ID.
 * The message will be displayed by the app.
 * @param string $msg message ID (see lang/messages.php)
 * @param string $arg If set, replaces "{arg}" in the message string when displayed to the user.
 */
function returnToSender($msg, $arg = "") {
    global $VARS;
    if ($arg == "") {
        header("Location: app.php?page=" . urlencode($VARS['source']) . "&msg=" . $msg);
    } else {
        header("Location: app.php?page=" . urlencode($VARS['source']) . "&msg=$msg&arg=$arg");
    }
    die();
}

switch ($VARS['action']) {
    case "editpub":
        $insert = true;
        if (is_empty($VARS['pubid'])) {
            $insert = true;
        } else {
            if ($database->has('publications', ['pubid' => $VARS['pubid']])) {
                $insert = false;
            } else {
                returnToSender("invalid_pubid");
            }
        }
        if (is_empty($VARS['name'])) {
            returnToSender('invalid_parameters');
        }
        if (!is_numeric($VARS['columns'])) {
            returnToSender('invalid_parameters');
        }
        if (!$database->has('pub_styles', ["styleid" => $VARS['style']])) {
            returnToSender('invalid_parameters');
        }
        if (!$database->has('pub_permissions', ["permid" => $VARS['perm']])) {
            returnToSender('invalid_parameters');
        }
        if (!$database->has('page_sizes', ["sizeid" => $VARS['size']])) {
            returnToSender('invalid_parameters');
        }
        if (!is_numeric($VARS['landscape']) || ((int) $VARS['landscape'] !== 0 && (int) $VARS['landscape'] !== 1)) {
            returnToSender('invalid_parameters');
        }

        $data = [
            'pubname' => $VARS['name'],
            'pubdate' => date("Y-m-d H:i:s"),
            'styleid' => $VARS['style'],
            'columns' => $VARS['columns'],
            'permid' => $VARS['perm'],
            'page_size' => $VARS['size'],
            'landscape' => $VARS['landscape']
        ];

        if ($insert) {
            $data['uid'] = $_SESSION['uid'];
            $database->insert('publications', $data);
            $pubid = $database->id();
            if (is_empty($VARS['cloneid']) || !$database->has("publications", ['pubid' => $VARS['cloneid']])) {
                // Make a header to get started
                $database->insert('tiles', [
                    "pubid" => $pubid,
                    "page" => 1,
                    "styleid" => 1,
                    "content" => "<h1>" . $VARS['name'] . "</h1>",
                    "width" => $VARS['columns'],
                    "order" => 0]
                );
            } else {
                $tiles = $database->select("tiles", ["page", "styleid", "content", "width", "order"], ["pubid" => $VARS['cloneid']]);
                foreach ($tiles as $tile) {
                    $tile["pubid"] = $pubid;
                    $database->insert("tiles", $tile);
                }
            }
        } else {
            $database->update('publications', $data, ['pubid' => $VARS['pubid']]);
        }

        returnToSender("pub_saved");
    case "deletepub":
        if ($database->has('publications', ['pubid' => $VARS['pubid']])) {
            $database->delete('tiles', ['pubid' => $VARS['pubid']]);
            $database->delete('publications', ['pubid' => $VARS['pubid']]);
            returnToSender("pub_deleted");
        }
        returnToSender("invalid_parameters");
    case "savetile":
        header("Content-Type: application/json");
        if (!$database->has('publications', ['pubid' => $VARS['pubid']])) {
            die(json_encode(["status" => "ERROR", "msg" => lang("invalid pubid", false)]));
        }

        $data = [
            "pubid" => $VARS['pubid'],
            "page" => $VARS['page'],
            "styleid" => $VARS['styleid'],
            "content" => trim($VARS['content']),
            "width" => $VARS['width'],
            "order" => $VARS['order']
        ];

        if ($database->has('tiles', ["tileid" => $VARS['tileid']])) {
            $database->update('tiles', $data, ["tileid" => $VARS['tileid']]);
        } else {
            $database->insert('tiles', $data);
        }
        exit(json_encode(["status" => "OK"]));
    case "signout":
        session_destroy();
        header('Location: index.php');
        die("Logged out.");
}