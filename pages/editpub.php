<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

require_once __DIR__ . '/../required.php';

redirectifnotloggedin();

$pubdata = [
    'name' => '',
    'pubdate' => '',
    'style' => '',
    'columns' => '',
    'permid' => '',
    'page_size' => 1,
    'landscape' => 0
];

$editing = false;
$cloning = false;

if (!is_empty($VARS['id'])) {
    if ($database->has('publications', ['pubid' => $VARS['id']])) {
        $editing = true;
        if ($VARS['clone'] == 1) {
            $cloning = true;
        }
        $pubdata = $database->select(
                        'publications', [
                    'pubname (name)',
                    'pubdate',
                    'style',
                    'columns',
                    'permid',
                    'page_size',
                    'landscape',
                    'pwd'
                        ], [
                    'pubid' => $VARS['id']
                ])[0];
    } else {
        // item id is invalid, redirect to a page that won't cause an error when pressing Save
        header('Location: app.php?page=editpub');
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
                <i class="fas fa-edit"></i> <?php lang2("cloning publication", ['opub' => htmlspecialchars($pubdata['name']), 'npub' => "<span id=\"name_title\">" . htmlspecialchars($pubdata['name']) . "</span>"]); ?>
                <?php
            } else if ($editing) {
                ?>
                <i class="fas fa-edit"></i> <?php lang2("editing publication", ['pub' => "<span id=\"name_title\">" . htmlspecialchars($pubdata['name']) . "</span>"]); ?>
                <?php
            } else {
                ?>
                <i class="fas fa-edit"></i> <?php lang("adding publication"); ?>
                <?php
            }
            ?>
        </h3>
        <div class="card-body">
            <div class="form-group">
                <label for="name"><i class="fa fa-font"></i> <?php lang("name"); ?></label>
                <input type="text" class="form-control" id="name" name="name" placeholder="<?php lang("placeholder name"); ?>" required="required" value="<?php echo htmlspecialchars($pubdata['name']); ?>" />
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="style"><i class="fas fa-star"></i> <?php lang('theme'); ?></label>
                        <div class="theme_bin">
                            <?php
                            $themedir = __DIR__ . "/../themes/";
                            $styles = array_diff(scandir($themedir), array('..', '.'));
                            foreach ($styles as $s) {
                                if (!file_exists($themedir . "$s/info.json")) {
                                    continue;
                                }
                                $info = json_decode(file_get_contents($themedir . "$s/info.json"), TRUE);
                                $ss = $pubdata["style"] == $s ? " checked" : "";

                                $colorvars = json_decode(file_get_contents($themedir . "$s/vars.json"), TRUE);
                                $themecsscolors = "";
                                $totalcolors = count($info['colors']);
                                for ($i = 0; $i < $totalcolors; $i++) {
                                    if ($i == 0) {
                                        $themecsscolors = $info['colors'][$i] . ",\n";
                                    }
                                    $themecsscolors .= $info['colors'][$i] . " " . (($i) / $totalcolors * 100) . "%,\n";
                                    $themecsscolors .= $info['colors'][$i] . " " . (($i + 1) / $totalcolors * 100) . "%";
                                    if ($i != $totalcolors - 1) {
                                        $themecsscolors .= ",\n";
                                    }
                                }
                                ?>
                                <label>
                                    <style nonce="<?php echo $SECURE_NONCE; ?>">
                                        #theme_<?php echo $s; ?> {
                                            background: <?php echo $colorvars['background']; ?>;
                                            color: <?php echo $colorvars['text']; ?>;
                                        }

                                        #themecolors_<?php echo $s; ?> {
                                            background-image: linear-gradient(
                                                90deg,
                                                <?php echo $themecsscolors; ?>
                                                );
                                        }
                                    </style>
                                    <input type="radio" name="style" value="<?php echo $s; ?>" <?php echo $ss; ?> />
                                    <div class="card theme" id="theme_<?php echo $s; ?>">
                                        <?php if (file_exists($themedir . "$s/background.png")) { ?>
                                            <img src="./themes/<?php echo $s; ?>/background.png" alt="" class="theme_img" />
                                        <?php } ?>
                                        <div class="theme_colors" id="themecolors_<?php echo $s; ?>">
                                        </div>
                                        <div class="card-body m-0 p-1">
                                            <?php echo $info['name']; ?>
                                        </div>
                                    </div>
                                </label>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <!--
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="columns"><i class="fas fa-columns"></i> <?php lang('columns'); ?></label>
                        <input type="number" class="form-control" id="columns" name="columns" placeholder="2" value="<?php echo $pubdata['columns']; ?>" required />
                    </div>
                </div>
                -->
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="size"><i class="fas fa-file"></i> <?php lang('page size'); ?></label>
                        <select name="size" class="form-control" required>
                            <?php
                            $sizes = $database->select("page_sizes", ['sizeid', 'sizename', 'sizewidth', 'sizeheight']);
                            foreach ($sizes as $s) {
                                $si = $s['sizeid'];
                                $sn = $s['sizename'];
                                $ss = $pubdata["page_size"] == $si ? " selected" : "";
                                echo "<option value=\"$si\"$ss>$sn</option>\n";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="landscape"><i class="fas fa-sync"></i> <?php lang('page orientation'); ?></label>
                        <select name="landscape" class="form-control" required>
                            <option value="0"<?php echo $pubdata["landscape"] == 0 ? " selected" : "" ?>><?php lang("portrait"); ?></option>
                            <option value="1"<?php echo $pubdata["landscape"] == 1 ? " selected" : "" ?>><?php lang("landscape"); ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="perm"><i class="fas fa-eye"></i> <?php lang('visibility'); ?></label>
                        <select name="perm" class="form-control" required>
                            <?php
                            $perms = $database->select("pub_permissions", ['permid', 'permname']);
                            foreach ($perms as $p) {
                                if ($p['permname'] == "PASSWORD") {
                                    continue;
                                }
                                $pi = $p['permid'];
                                $pn = lang("visibility " . strtolower($p['permname']), false);
                                $ps = $pubdata["permid"] == $pi ? " selected" : "";
                                echo "<option value=\"$pi\"$ps>$pn</option>\n";
                            }
                            ?>
                        </select>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="password_protect" name="password_protect" value="1" <?php echo is_empty($pubdata['pwd']) ? "" : "checked" ?>> <?php lang("password protect"); ?>
                            </label>
                        </div>
                        <?php if (is_empty($pubdata['pwd'])) { ?>
                            <style nonce="<?php echo $SECURE_NONCE; ?>">
                                #password {
                                    display: none;
                                }
                            </style>
                        <?php } ?>
                        <div id="password">
                            <input type="password" name="password" value="" placeholder="<?php lang("password"); ?>" class="form-control" />
                            <i class="fa fa-info-circle"></i> <?php lang("anyone with link and password can view"); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="pubid" value="<?php
        if ($editing && !$cloning) {
            echo htmlspecialchars($VARS['id']);
        }
        ?>" />

        <?php if ($editing && $cloning) { ?>
            <input type="hidden" name="cloneid" value="<?php echo htmlspecialchars($VARS['id']); ?>" />
        <?php } ?>
        <input type="hidden" name="action" value="editpub" />
        <input type="hidden" name="source" value="home" />

        <div class="card-footer d-flex">
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> <?php lang("save"); ?></button>
            <a class="btn btn-info ml-1" href="app.php?page=home"><i class="fas fa-arrow-left"></i> <?php lang("cancel"); ?></a>
            <?php
            if ($editing && !$cloning) {
                ?>
                &nbsp; <button type="submit" name="gotocontent" value="1" class="btn btn-primary mr-auto"><i class="fas fa-edit"></i> <?php lang('edit content'); ?></button>
                <a href="action.php?action=deletepub&source=home&pubid=<?php echo htmlspecialchars($VARS['id']); ?>" class="btn btn-danger"><i class="fas fa-times"></i> <?php lang('delete'); ?></a>
                <?php
            }
            ?>
        </div>
    </div>
</form>