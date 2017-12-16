<?php

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

require_once __DIR__ . '/../required.php';

redirectifnotloggedin();

$pub = false;

$pubdata = [];

$edit = false;

if (is_numeric($VARS['pubid'])) {
    if ($database->has('publications', ['pubid' => $VARS['pubid']])) {
        $pub = $VARS['pubid'];
        $pubdata = $database->get("publications", ["[>]pub_permissions" => ["permid" => "permid"]], ["pubname", "uid", "pubdate", "styleid", "columns", "page_size", "landscape", "publications.permid", "permname"], ["pubid" => $pub]);
        if ($pubdata["uid"] == $_SESSION['uid']) {
            $edit = true;
        } else {
            if ($pubdata["permname"] == "OWNER") {
                header("Location: app.php?page=content&msg=no_permission");
                die();
            }
        }
    } else {
        header("Location: app.php?page=content&msg=invalid_pubid");
        die();
    }
}

if ($pub === false) {
    $pubs = $database->select("publications", ["pubid", "pubname"], ["uid" => $_SESSION['uid'], "ORDER" => "pubname"]);
    ?>
    <div class="container">
        <label for="pubid"><?php lang("choose publication"); ?></label>
        <form action="app.php" method="get" class="form-inline">
            <select name="pubid" class="form-control">
                <?php
                foreach ($pubs as $p) {
                    $pi = $p['pubid'];
                    $pn = htmlspecialchars($p['pubname']);
                    echo "<option value=\"$pi\">$pn</option>\n";
                }
                ?>
            </select>
            <input type="hidden" name="page" value="content" />
            <button type="submit" class="btn btn-success"><i class="fa fa-folder-open-o"></i> <?php lang("open"); ?></button>
        </form>
    </div>
    <?php
} else {
    ?>
    <?php if ($edit) { ?>
        <div class="modal fade" id="tile-options-modal" tabindex="-1" role="dialog" aria-labelledby="tile-options-title">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="tile-options-title"><?php lang("edit tile"); ?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="width" class="control-label"><i class="fa fa-text-width"></i> <?php lang("width"); ?></label>
                            <input type="number" class="form-control" id="width">
                        </div>
                        <div class="form-group">
                            <label for="order" class="control-label"><i class="fa fa-sort"></i> <?php lang("order"); ?></label>
                            <input type="number" class="form-control" id="order">
                        </div>
                        <div class="form-group">
                            <label for="page" class="control-label"><i class="fa fa-file-o"></i> <?php lang("page"); ?></label>
                            <input type="number" class="form-control" id="page">
                        </div>
                        <div class="form-group">
                            <label for="style" class="control-label"><i class="fa fa-star"></i> <?php lang("style"); ?></label>
                            <select id="style" class="form-control">
                                <?php
                                $styles = $database->select("tile_styles", ['styleid', 'stylename']);
                                foreach ($styles as $s) {
                                    $si = $s['styleid'];
                                    $sn = $s['stylename'];
                                    echo "<option value=\"$si\">$sn</option>\n";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-xs" id="edit-tile-del-btn"><?php lang("delete"); ?></button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php lang("close"); ?></button>
                        <button type="button" class="btn btn-primary" id="edit-tile-save-btn" data-tile=""><?php lang("save"); ?></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="new-tile-modal" tabindex="-1" role="dialog" aria-labelledby="new-tile-title">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="new-tile-title"><?php lang("new tile"); ?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="width" class="control-label"><i class="fa fa-text-width"></i> <?php lang("width"); ?></label>
                            <input type="number" class="form-control" id="newwidth" value="1">
                        </div>
                        <div class="form-group">
                            <label for="order" class="control-label"><i class="fa fa-sort"></i> <?php lang("order"); ?></label>
                            <input type="number" class="form-control" id="neworder" value="1">
                        </div>
                        <div class="form-group">
                            <label for="page" class="control-label"><i class="fa fa-file-o"></i> <?php lang("page"); ?></label>
                            <input type="number" class="form-control" id="newpage" value="1">
                        </div>
                        <div class="form-group">
                            <label for="style" class="control-label"><i class="fa fa-star"></i> <?php lang("style"); ?></label>
                            <select id="newstyle" class="form-control">
                                <?php
                                $styles = $database->select("tile_styles", ['styleid', 'stylename']);
                                foreach ($styles as $s) {
                                    $si = $s['styleid'];
                                    $sn = $s['stylename'];
                                    echo "<option value=\"$si\">$sn</option>\n";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php lang("close"); ?></button>
                        <button type="button" class="btn btn-primary" id="new-tile-save-btn" data-tile=""><?php lang("new tile"); ?></button>
                    </div>
                </div>
            </div>
        </div>

    <?php } ?>

    <div class="btn-group mgn-btm-10px">
        <?php if ($edit) { ?>
            <div class="btn btn-success" id="new_tile_btn" data-toggle="modal" data-target="#new-tile-modal"><i class="fa fa-plus"></i> <?php lang("new tile"); ?></div>
        <?php } ?>
        <a class="btn btn-primary" id="preview_btn" href="lib/gencontent.php?pubid=<?php echo $pub; ?>" target="_BLANK"><i class="fa fa-search"></i> <?php lang("preview"); ?></a>
    </div>

    <div class="pages-box">
        <?php
        define("IN_NEWSPEN", true);
        define("EDIT_MODE", $edit);
        require_once __DIR__ . "/../lib/gencontent.php";
        echo $content;
        ?>
    </div>

    <script nonce="<?php echo $SECURE_NONCE; ?>">
        var pubid = <?php echo $pub; ?>;
    </script>
<?php } ?>
