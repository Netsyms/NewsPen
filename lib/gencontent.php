<?php
require_once __DIR__ . "/../required.php";
dieifnotloggedin();

if (!defined("IN_NEWSPEN")) {
    die("Please don't do that.");
}
ob_end_flush();
ob_start();
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
            <div class="tile" id="tile-<?php echo $tile["tileid"]; ?>" data-tileid="<?php echo $tile["tileid"]; ?>" data-styleid="<?php echo $tile["styleid"]; ?>" data-page="<?php echo $tile["page"]; ?>" data-styleid="<?php echo $tile["styleid"]; ?>" data-width="<?php echo $tile["width"]; ?>" data-order="<?php echo $tile["order"]; ?>">
                <?php
                if (defined("EDIT_MODE") && EDIT_MODE == true) {
                    ?><div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-default edit-btn" data-tile="<?php echo $tile["tileid"]; ?>"><i class="fa fa-pencil"></i> <?php lang("edit"); ?></button>
                        <button type="button" class="btn btn-default save-btn" data-tile="<?php echo $tile["tileid"]; ?>"><i class="fa fa-save"></i> <?php lang("save"); ?></button>
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

if (defined("HTML_ME")) {
    $content = "<!DOCTYPE html>\n"
            . "<meta charset=\"utf-8\">\n"
            . "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n"
            . "<title></title>\n"
            . "$content";
}
?>