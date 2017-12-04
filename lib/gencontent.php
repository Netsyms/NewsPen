<?php
require_once __DIR__ . "/../required.php";

if (!defined("IN_NEWSPEN")) {
    if (is_numeric($VARS['pubid'])) {
        if ($database->has('publications', ['pubid' => $VARS['pubid']])) {
            $pub = $VARS['pubid'];
            $pubdata = $database->get("publications", ["[>]pub_permissions" => ["permid" => "permid"]], ["pubname", "uid", "pubdate", "styleid", "columns", "page_size", "landscape", "publications.permid", "permname"], ["pubid" => $pub]);
            if ($pubdata["permname"] != "LINK") {
                dieifnotloggedin();
            }
            if ($pubdata["uid"] != $_SESSION['uid']) {
                if ($pubdata["permname"] == "OWNER") {
                    die(lang("no permission"));
                }
            }
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
<?php $pubcss = $database->get("pub_styles", ["css", "cssvars", "cssextra", "background"], ["styleid" => $pubdata["styleid"]]); ?>
    .pub-content {
        <?php
        $pubvars = json_decode($pubcss["cssvars"], TRUE);
        foreach ($pubvars as $name => $val) {
            echo "--$name: $val;\n";
        }
        ?>
    }

    .pub-content {
<?php echo $pubcss["css"]; ?>
    }
    
<?php echo $pubcss["cssextra"]; ?>
    
    .pub-content {
        background-image: url('data:image/png;base64,<?php echo $pubcss["background"]; ?>');
    }
    
<?php $pagesize = $database->get("page_sizes", ["sizewidth (width)", "sizeheight (height)"], ["sizeid" => $pubdata["page_size"]]); ?>
    .pub-content {
        max-width: <?php echo ($pubdata["landscape"] == 0 ? $pagesize["width"] : $pagesize["height"]); ?>;
        height: <?php echo ($pubdata["landscape"] == 0 ? $pagesize["height"] : $pagesize["width"]); ?>;
    }
    
    @media (max-width: 900px) {
        .pub-content {
            height: auto;
            min-height: <?php echo ($pubdata["landscape"] == 0 ? $pagesize["height"] : $pagesize["width"]); ?>;
        }
    }
    
    .page-safe-line .bottom {
        top: calc(<?php echo ($pubdata["landscape"] == 0 ? $pagesize["height"] : $pagesize["width"]); ?> - 5mm);
    }
</style>

<style nonce="<?php echo $SECURE_NONCE; ?>" media="all">
<?php
$styles = $database->select("tile_styles", ["styleid", "css"]);
$tiles = $database->select("tiles", ["tileid", "page", "styleid", "content", "width", "order"], ["pubid" => $pub, "ORDER" => ["page", "order"]]);
foreach ($styles as $style) {
    ?> 
        .tile-style-<?php echo $style["styleid"]; ?> {
    <?php echo $style["css"] . "\n"; ?>
        }
    <?php
}

foreach ($tiles as $tile) {
    if ($tile["width"] > $pubdata["columns"]) {
        $tile["width"] = $pubdata["columns"];
    }
    ?> 
        #tile-<?php echo $tile["tileid"]; ?> {
            order: <?php echo $tile["order"]; ?>;
            width: <?php echo round((($tile["width"] * 1.0) / ($pubdata["columns"] * 1.0) * 100)); ?>%;
            flex-basis: <?php echo round((($tile["width"] * 1.0) / ($pubdata["columns"] * 1.0) * 100)); ?>%;
            flex: 0 0 calc(<?php echo round((($tile["width"] * 1.0) / ($pubdata["columns"] * 1.0) * 100)); ?>% - 10px);
        }
    <?php
}
?>
</style>

<?php
// Get a list of pages
$pages = [];
foreach ($tiles as $tile) {
    if (!in_array($tile["page"], $pages)) {
        $pages[] = $tile["page"];
    }
}

foreach ($pages as $page) {
    ?>
    <div class="pub-content">
        <div class="page-safe-line">
            <div class="bottom"></div>
        </div>
        <div class="tile-bin">
            <?php
            foreach ($tiles as $tile) {
                if ($tile["page"] == $page) {
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
                            <div class="tile-html"><?php echo $tile["content"]; ?></div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
    <?php
}

$content = ob_get_clean();

if (defined("HTML_ME") || !defined("IN_NEWSPEN")) {
    $contentcss = file_get_contents(__DIR__ . "/../static/css/content.css");
    $title = htmlspecialchars($pubdata["pubname"] . " | " . date("Y-m-d", strtotime($pubdata["pubdate"])));
    $content = "<!DOCTYPE html>\n"
            . "<meta charset=\"utf-8\">\n"
            . "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n"
            . "<title>$title</title>\n"
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