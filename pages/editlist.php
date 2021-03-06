<?php

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

require_once __DIR__ . '/../required.php';

redirectifnotloggedin();

$data = [
    'name' => '',
    'id' => ''
];
$emails = [];

$editing = false;
$cloning = false;

if (!is_empty($VARS['id'])) {
    if ($database->has('mail_lists', ['listid' => $VARS['id']])) {
        $editing = true;
        if ($VARS['clone'] == 1) {
            $cloning = true;
        }
        $data = $database->select(
                        'mail_lists', [
                    'listid (id)',
                    'listname (name)',
                    'uid'
                        ], [
                    'listid' => $VARS['id']
                ])[0];
        $emails = $database->select('addresses', 'email', ['listid' => $VARS['id']]);
    } else {
        // item id is invalid, redirect to a page that won't cause an error when pressing Save
        header('Location: app.php?page=editlist');
        die();
    }
}
?>

<form role="form" action="action.php" method="POST">
    <div class="card border-deep-purple">
            <h3 class="card-header text-deep-purple">
                <?php
                if ($cloning) {
                    ?>
                    <i class="fas fa-edit"></i> <?php lang2("cloning list", ['olist' => htmlspecialchars($data['name']), 'nlist' => "<span id=\"name_title\">" . htmlspecialchars($data['name']) . "</span>"]); ?>
                    <?php
                } else if ($editing) {
                    ?>
                    <i class="fas fa-edit"></i> <?php lang2("editing list", ['list' => "<span id=\"name_title\">" . htmlspecialchars($data['name']) . "</span>"]); ?>
                    <?php
                } else {
                    ?>
                    <i class="fas fa-edit"></i> <?php lang("adding list"); ?>
                    <?php
                }
                ?>
            </h3>
        <div class="card-body">
            <div class="form-group">
                <label for="name"><i class="fas fa-font"></i> <?php lang("name"); ?></label>
                <input type="text" class="form-control" id="name" name="name" placeholder="<?php lang("placeholder name"); ?>" required="required" value="<?php echo htmlspecialchars($data['name']); ?>" />
            </div>

            <label for="emails"><i class="far fa-envelope"></i> <?php lang("addresses comma separated"); ?></label>
            <div class="card">
                <input class="form-control" name="emails" id="emails" type="text" value="<?php
                foreach ($emails as $m) {
                    echo "$m,";
                }
                ?>" />
            </div>
        </div>

        <input type="hidden" name="listid" value="<?php
        if ($editing && !$cloning) {
            echo htmlspecialchars($VARS['id']);
        }
        ?>" />

        <input type="hidden" name="action" value="editlist" />
        <input type="hidden" name="source" value="maillist" />

        <div class="card-footer d-flex">
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> <?php lang("save"); ?></button>
            <a class="btn btn-info ml-1 mr-auto" href="app.php?page=maillist"><i class="fas fa-arrow-left"></i> <?php lang("cancel"); ?></a>
            <?php
            if ($editing && !$cloning) {
                ?>
                <a href="action.php?action=deletelist&source=maillist&listid=<?php echo htmlspecialchars($VARS['id']); ?>" class="btn btn-danger"><i class="fas fa-times"></i> <?php lang('delete'); ?></a>
                <?php
            }
            ?>
        </div>
    </div>
</form>