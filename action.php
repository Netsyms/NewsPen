<?php

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

/**
 * Make things happen when buttons are pressed and forms submitted.
 */
require_once __DIR__ . "/required.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
    case "sendpub":
        try {
            ini_set('max_execution_time', 60 * 5);
            
            // Setup mailer
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = SMTP_AUTH;
            if (SMTP_AUTH) {
                $mail->Username = SMTP_USERNAME;
                $mail->Password = SMTP_PASSWORD;
            }
            if (SMTP_SECURITY != "none") {
                $mail->SMTPSecure = SMTP_SECURITY;
            }
            $mail->Port = SMTP_PORT;
            $mail->isHTML(false);
            $mail->setFrom(SMTP_FROMADDRESS, SMTP_FROMNAME);


            // Get addresses
            $addresses = [];
            if ($database->has('mail_lists', ['listid' => $VARS['list']])) {
                $addresses = $database->select("addresses", 'email', ['listid' => $VARS['list']]);
            } else {
                returnToSender("invalid_listid");
            }


            // Split address list into batches
            $segmented = [];
            $s = 0;
            for ($i = 0; $i < count($addresses); $i++) {
                $segmented[$s][] = $addresses[$i];
                if (count($segmented[$s]) >= SMTP_BATCH_SIZE) {
                    $s++;
                }
            }


            // Build message content
            if (empty($VARS['subject']) || trim($VARS['subject']) == "") {
                returnToSender("invalid_parameters");
            }
            if (empty($VARS['pubid']) || !$database->has("publications", ['pubid' => $VARS['pubid']])) {
                returnToSender("invalid_pubid");
            }

            $mail->Subject = $VARS['subject'];

            $parsedown = new Parsedown();
            $parsedown->setSafeMode(true);
            $html = $parsedown->text($VARS['message']);

            if (strpos(URL, "https://") === 0 || strpos(URL, "http://") === 0) {
                $url = URL;
            } else {
                // Don't trust the URL setting, it's not an absolute URL
                $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
                $path = explode("/", $_SERVER["REQUEST_URI"]);
                array_pop($path);
                $url .= implode("/", $path);
            }
            $url = $url . (substr($url, -1) == '/' ? '' : '/');
            $puburl = $url . "view.php?id=" . $VARS['pubid'];
            $unsuburl = $url . "unsubscribe.php";

            $link = "<a href=\"$puburl\">$puburl</a>\n";
            $footer = "<hr />\nUnsubscribe: <a href=\"$unsuburl\">$unsuburl</a>";

            $mail->Body = $html . "<br />\n" . $link . $footer;
            $mail->AltBody = $VARS['message'] . "\n" . $puburl . "\n\n-----\nUnsubscribe: $unsuburl";

            var_dump($mail->Body);
            var_dump($mail->AltBody);

            // Send the mail
            foreach ($segmented as $segment) {
                foreach ($segment as $s) {
                    $mail->addBCC($s);
                }
                $mail->send();
                $mail->clearAllRecipients();
            }
        } catch (Exception $ex) {
            returnToSender("mail_error", $mail->ErrorInfo);
        }

        $database->update("publications", ['mailedon' => date("Y-m-d H:i:s"), 'mailedto' => $VARS['list']], ['pubid' => $VARS['pubid']]);

        returnToSender("mail_sent");
        break;
    case "editpub":
        $insert = true;
        if (is_empty($VARS['pubid'])) {
            $insert = true;
        } else {
            if ($database->has('publications', ['pubid' => $VARS['pubid']])) {
                $insert = false;
                if ($database->get("publications", 'uid', ['pubid' => $VARS['pubid']]) != $_SESSION['uid']) {
                    returnToSender("no_permission");
                }
            } else {
                returnToSender("invalid_pubid");
            }
        }
        if (is_empty($VARS['name'])) {
            returnToSender('invalid_parameters');
        }
        $VARS['columns'] = 4;
        /* if (!is_numeric($VARS['columns'])) {
          returnToSender('invalid_parameters');
          } */
        if (!preg_match("/([A-Za-z0-9_])+/", $VARS['style'])) {
            $VARS['style'] = "";
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
        if ($VARS['password_protect'] == 1) {
            if (!is_empty($VARS['password'])) {
                $password = password_hash($VARS['password'], PASSWORD_BCRYPT);
            } else if (!$insert) {
                $password = $database->get("publications", 'pwd', ['pubid' => $VARS['pubid']]);
            }
            $permission = 3;
        } else {
            $password = null;
            $permission = $VARS['perm'];
        }

        $data = [
            'pubname' => $VARS['name'],
            'pubdate' => date("Y-m-d H:i:s"),
            'style' => $VARS['style'],
            'columns' => $VARS['columns'],
            'permid' => $permission,
            'pwd' => $password,
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

        if (isset($VARS["gotocontent"])) {
            header("Location: app.php?page=content&pubid=" . $VARS['pubid']);
            die();
        }
        returnToSender("pub_saved");
    case "deletepub":
        if ($database->has('publications', ['pubid' => $VARS['pubid']])) {
            if ($database->get("publications", 'uid', ['pubid' => $VARS['pubid']]) != $_SESSION['uid']) {
                returnToSender("no_permission");
            }
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

        if ($database->get("publications", 'uid', ['pubid' => $VARS['pubid']]) != $_SESSION['uid']) {
            die(json_encode(["status" => "ERROR", "msg" => lang("no permission", false)]));
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
    case "deltile":
        header("Content-Type: application/json");
        if (!$database->has('tiles', ['tileid' => $VARS['tileid']])) {
            die(json_encode(["status" => "ERROR", "msg" => lang("invalid tileid", false)]));
        }

        $pubid = $database->get("tiles", "pubid", ['tileid' => $VARS['tileid']]);

        if ($database->get("publications", 'uid', ['pubid' => $pubid]) != $_SESSION['uid']) {
            die(json_encode(["status" => "ERROR", "msg" => lang("no permission", false)]));
        }

        $database->delete('tiles', ["tileid" => $VARS['tileid']]);
        exit(json_encode(["status" => "OK"]));
    case "editlist":
        $insert = true;
        if (is_empty($VARS['listid'])) {
            $insert = true;
        } else {
            if ($database->has('mail_lists', ['listid' => $VARS['listid']])) {
                $insert = false;
                if ($database->get("mail_lists", 'uid', ['listid' => $VARS['listid']]) != $_SESSION['uid']) {
                    returnToSender("no_permission");
                }
            } else {
                returnToSender("invalid_listid");
            }
        }
        if (is_empty($VARS['name'])) {
            returnToSender('invalid_parameters');
        }

        $data = [
            'listname' => $VARS['name']
        ];

        if ($insert) {
            $data['uid'] = $_SESSION['uid'];
            $database->insert('mail_lists', $data);
            $listid = $database->id();
        } else {
            $database->update('mail_lists', $data, ['listid' => $VARS['listid']]);
            $listid = $VARS['listid'];
        }

        $emails = explode(",", $VARS['emails']);
        $dbemails = $database->select('addresses', 'email', ['listid' => $listid]);
        $todelete = $dbemails;
        $toadd = [];
        foreach ($emails as $m) {
            if (!in_array($m, $dbemails)) {
                $toadd[] = $m;
            }

            $todelete = array_diff($todelete, [$m]);
        }

        foreach ($todelete as $m) {
            $database->delete('addresses', ["AND" => ['listid' => $listid, "email" => $m]]);
        }
        foreach ($toadd as $m) {
            $database->insert('addresses', ['listid' => $listid, 'email' => $m, 'name' => '']);
        }
        returnToSender("list_saved");
    case "deletelist":
        if ($database->has('mail_lists', ['listid' => $VARS['listid']])) {
            if ($database->get("mail_lists", 'uid', ['listid' => $VARS['listid']]) != $_SESSION['uid']) {
                returnToSender("no_permission");
            }
            $database->delete('addresses', ['listid' => $VARS['listid']]);
            $database->delete('mail_lists', ['listid' => $VARS['listid']]);
            returnToSender("list_deleted");
        }
        returnToSender("invalid_parameters");
    case "signout":
        session_destroy();
        header('Location: index.php');
        die("Logged out.");
}