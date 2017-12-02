<?php
require_once __DIR__ . '/../required.php';

redirectifnotloggedin();

$pub = false;

$pubdata = [];

if (is_numeric($VARS['pubid'])) {
    if ($database->has('publications', ['pubid' => $VARS['pubid']])) {
        $pub = $VARS['pubid'];
        $pubdata = $database->get("publications", ["pubname", "pubdate", "styleid", "columns"], ["pubid" => $pub]);
    } else {
        header("Location: app.php?page=content&msg=invalid_pubid");
        die();
    }
}

if ($pub === false) {
    $pubs = $database->select("publications", ["pubid", "pubname"], ["uid" => $_SESSION['uid']]);
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
            <button type="submit" class="btn btn-success"><i class="fa fa-arrow-right"></i> <?php lang("open"); ?></button>
        </form>
    </div>
    <?php
} else {
    ?>

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
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php lang("close"); ?></button>
                    <button type="button" class="btn btn-primary" id="modal-save-btn" data-tile=""><?php lang("save"); ?></button>
                </div>
            </div>
        </div>
    </div>

    <div class="btn-group mgn-btm-10px">
        <div class="btn btn-success" id="new_tile_btn"><i class="fa fa-plus"></i> <?php lang("new tile"); ?></div>
        <a class="btn btn-primary" id="preview_btn" href="lib/gencontent.php?pubid=1" target="_BLANK"><i class="fa fa-search"></i> <?php lang("preview"); ?></a>
    </div>

    <div class="pages-box">
        <?php
        define("IN_NEWSPEN", true);
        define("EDIT_MODE", true);
        require_once __DIR__ . "/../lib/gencontent.php";
        echo $content;
        ?>
    </div>

    <script nonce="<?php echo $SECURE_NONCE; ?>">
        var pubid = <?php echo $pub; ?>;
    </script>
<?php } ?>
