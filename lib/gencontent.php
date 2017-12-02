<?php
require_once __DIR__ . "/../required.php";
dieifnotloggedin();

if (!defined("IN_NEWSPEN")) {
    if (is_numeric($VARS['pubid'])) {
        if ($database->has('publications', ['pubid' => $VARS['pubid']])) {
            $pub = $VARS['pubid'];
            $pubdata = $database->get("publications", ["pubname", "pubdate", "styleid", "columns"], ["pubid" => $pub]);
        } else {
            die(lang("invalid parameters", false));
        }
    } else {
        die(lang("invalid parameters", false));
    }
}
ob_end_flush();
ob_start();

if (defined("EDIT_MODE") && EDIT_MODE == true) {
    ?>
    <script nonce="<?php echo $SECURE_NONCE; ?>">
        var pubid = <?php echo $pub; ?>;
        var pubcolumns = <?php echo $pubdata["columns"]; ?>;
    </script>
    <?php
}
?>
<style nonce="<?php echo $SECURE_NONCE; ?>">
<?php $pubcss = $database->get("pub_styles", "css", ["styleid" => $pubdata["styleid"]]); ?>
    .pub-content {
<?php echo $pubcss; ?>
    }
</style>

<style nonce="<?php echo $SECURE_NONCE; ?>" media="all">
<?php
$styles = $database->select("tile_styles", ["styleid", "css"]);
$tiles = $database->select("tiles", ["tileid", "page", "styleid", "content", "width", "order"], ["pubid" => $pub, "ORDER" => "order"]);
foreach ($styles as $style) {
    ?> 
        .tile-style-<?php echo $style["styleid"]; ?> {
    <?php echo $style["css"] . "\n"; ?>
        }
    <?php
}

foreach ($tiles as $tile) {
    ?> 
        #tile-<?php echo $tile["tileid"]; ?> {
            order: <?php echo $tile["order"]; ?>;
            width: <?php echo round((($tile["width"] * 1.0) / ($pubdata["columns"] * 1.0) * 100)); ?>%;
            flex-basis: <?php echo round((($tile["width"] * 1.0) / ($pubdata["columns"] * 1.0) * 100)); ?>%;
        }
    <?php
}
?>
</style>

<div class="pub-content">
    <div class="tile-bin">
        <?php
        foreach ($tiles as $tile) {
            ?>
            <div class="tile" id="tile-<?php echo $tile["tileid"]; ?>" data-tileid="<?php echo $tile["tileid"]; ?>" data-page="<?php echo $tile["page"]; ?>" data-styleid="<?php echo $tile["styleid"]; ?>" data-width="<?php echo $tile["width"]; ?>" data-order="<?php echo $tile["order"]; ?>">
                <?php
                if (defined("EDIT_MODE") && EDIT_MODE == true) {
                    ?><div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-default edit-btn" data-tile="<?php echo $tile["tileid"]; ?>"><i class="fa fa-pencil"></i> <?php lang("edit"); ?></button>
                        <button type="button" class="btn btn-default save-btn" data-tile="<?php echo $tile["tileid"]; ?>"><i class="fa fa-save"></i> <?php lang("save"); ?></button>
                        <button type="button" class="btn btn-default opts-btn" data-tile="<?php echo $tile["tileid"]; ?>" data-toggle="modal" data-target="#tile-options-modal"><i class="fa fa-gear"></i> <?php lang("options"); ?></button>
                    </div>
                <?php } ?>
                <div id="tile-<?php echo $tile["tileid"]; ?>-content" class="tile-style-<?php echo $tile["styleid"]; ?>">
                    <div class="tile-html">
                        <?php echo $tile["content"]; ?>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<?php
$content = ob_get_clean();

if (defined("HTML_ME") || !defined("IN_NEWSPEN")) {
    $contentcss = file_get_contents(__DIR__ . "/../static/css/content.css");
    $content = "<!DOCTYPE html>\n"
            . "<meta charset=\"utf-8\">\n"
            . "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n"
            . "<title></title>\n"
            . "<style nonce=\"$SECURE_NONCE\">$contentcss</style>\n"
            . "$content";
    // Credit: https://stackoverflow.com/a/709684
    $content = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $content);
    // End credit
    $content = str_replace("\t", "  ", $content);
    // TODO: replace this loop with something less freshman
    while (strpos($content, "    ") !== FALSE) {
        $content = str_replace("    ", "  ", $content);
    }
}

if (!defined("IN_NEWSPEN")) {
    echo $content;
}
?>