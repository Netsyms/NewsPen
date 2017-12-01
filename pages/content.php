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

    <div class="btn-group mgn-btm-10px">
        <div class="btn btn-success" id="new_tile_btn"><i class="fa fa-plus"></i> <?php lang("new tile"); ?></div>
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
