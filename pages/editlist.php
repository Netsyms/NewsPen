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
    } else {
        // item id is invalid, redirect to a page that won't cause an error when pressing Save
        header('Location: app.php?page=editlist');
        die();
    }
}
?>

<form role="form" action="action.php" method="POST">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">
                <?php
                if ($cloning) {
                    ?>
                    <i class="fa fa-pencil-square-o"></i> <?php lang2("cloning list", ['olist' => htmlspecialchars($data['name']), 'nlist' => "<span id=\"name_title\">" . htmlspecialchars($data['name']) . "</span>"]); ?>
                    <?php
                } else if ($editing) {
                    ?>
                    <i class="fa fa-pencil-square-o"></i> <?php lang2("editing list", ['list' => "<span id=\"name_title\">" . htmlspecialchars($data['name']) . "</span>"]); ?>
                    <?php
                } else {
                    ?>
                    <i class="fa fa-pencil-square-o"></i> <?php lang("adding list"); ?>
                    <?php
                }
                ?>
            </h3>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="name"><i class="fa fa-font"></i> <?php lang("name"); ?></label>
                <input type="text" class="form-control" id="name" name="name" placeholder="<?php lang("placeholder name"); ?>" required="required" value="<?php echo htmlspecialchars($data['name']); ?>" />
            </div>

            <div class="row">

            </div>
        </div>

        <input type="hidden" name="listid" value="<?php
        if ($editing && !$cloning) {
            echo htmlspecialchars($VARS['id']);
        }
        ?>" />

        <?php if ($editing && $cloning) { ?>
            <input type="hidden" name="cloneid" value="<?php echo htmlspecialchars($VARS['id']); ?>" />
        <?php } ?>
        <input type="hidden" name="action" value="editlist" />
        <input type="hidden" name="source" value="maillist" />

        <div class="panel-footer">
            <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> <?php lang("save"); ?></button>
            <?php
            if ($editing && !$cloning) {
                ?>
                <a href="action.php?action=deletelist&source=maillist&listid=<?php echo htmlspecialchars($VARS['id']); ?>" class="btn btn-danger btn-xs pull-right mgn-top-8px"><i class="fa fa-times"></i> <?php lang('delete'); ?></a>
                <?php
            }
            ?>
        </div>
    </div>
</form>