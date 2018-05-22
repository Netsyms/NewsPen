<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

require_once __DIR__ . '/../required.php';

redirectifnotloggedin();

if (is_empty($VARS['pubid']) || !$database->has("publications", ['pubid' => $VARS['pubid']])) {
    header('Location: app.php?page=home');
    die();
}

$lists = $database->select("mail_lists", ['listid', 'listname']);
?>

<form role="form" action="action.php" method="POST">
    <div class="card border-deep-purple">
        <h3 class="card-header text-deep-purple">
            <i class="fas fa-paper-plane"></i> <?php lang("send publication"); ?>
        </h3>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <label for="subject"><i class="fas fa-envelope"></i> <?php lang("subject"); ?></label>
                        <input type="text" class="form-control" id="subject" name="subject" placeholder="<?php lang("placeholder subject"); ?>" required="required" />
                    </div>
                    <div class="form-group">
                        <label for="message"><i class="fas fa-edit"></i> <?php lang("message"); ?></label>
                        <textarea id="message" name="message" class="form-control" rows="5"><?php lang("default message"); ?></textarea>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <label for="preview"><i class="fas fa-search"></i> <?php lang("preview"); ?></label>
                    <div class="card border-deep-purple">
                        <div class="card-header">
                            <span id="subjectpreview" class="h5"><?php lang("subject"); ?></span>
                        </div>
                        <div class="card-body">
                            <span id="messagepreview">
                                <?php echo str_replace("\n", "<br>", lang("default message", false)); ?>
                            </span>
                            <br>
                            <a href="<?php echo URL; ?>/view.php?id=<?php echo $VARS['pubid']; ?>"><?php echo URL; ?>/view.php?id=<?php echo $VARS['pubid']; ?></a>
                            <hr />
                            Unsubscribe: <a href="<?php echo URL; ?>/unsubscribe.php?a=xxxxx@example.com"><?php echo URL; ?>/unsubscribe.php?a=xxxxx@example.com</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="list"><i class="fas fa-bars"></i> <?php lang("list"); ?></label>
                <select name="list" id="list" class="form-control">
                    <?php
                    foreach ($lists as $l) {
                        echo "<option value=\"" . $l['listid'] . "\">" . htmlspecialchars($l['listname']) . "</option>\n";
                    }
                    ?>
                </select>
            </div>
        </div>

        <input type="hidden" name="pubid" value="<?php
        echo htmlspecialchars($VARS['pubid']);
        ?>" />

        <input type="hidden" name="action" value="sendpub" />
        <input type="hidden" name="source" value="home" />

        <div class="card-footer d-flex">
            <button type="submit" class="btn btn-success mr-auto"><i class="fas fa-paper-plane"></i> <?php lang("send"); ?></button>

            <a href="./app.php?page=content&pubid=<?php echo htmlspecialchars($VARS['pubid']); ?>" class="btn btn-danger"><i class="fas fa-times"></i> <?php lang('cancel'); ?></a>
        </div>
    </div>
</form>