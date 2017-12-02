<?php
require_once __DIR__ . '/../required.php';

redirectifnotloggedin();

$pubdata = [
    'name' => '',
    'pubdate' => '',
    'styleid' => '',
    'columns' => '',
    'permid' => ''
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
                    'styleid',
                    'columns',
                    'permid'
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
    <div class="panel panel-blue">
        <div class="panel-heading">
            <h3 class="panel-title">
                <?php
                if ($cloning) {
                    ?>
                    <i class="fa fa-pencil-square-o"></i> <?php lang2("cloning publication", ['opub' => htmlspecialchars($pubdata['name']), 'npub' => "<span id=\"name_title\">" . htmlspecialchars($pubdata['name']) . "</span>"]); ?>
                    <?php
                } else if ($editing) {
                    ?>
                    <i class="fa fa-pencil-square-o"></i> <?php lang2("editing publication", ['pub' => "<span id=\"name_title\">" . htmlspecialchars($pubdata['name']) . "</span>"]); ?>
                    <?php
                } else {
                    ?>
                    <i class="fa fa-pencil-square-o"></i> <?php lang("adding publication"); ?>
                    <?php
                }
                ?>
            </h3>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="name"><i class="fa fa-font"></i> <?php lang("name"); ?></label>
                <input type="text" class="form-control" id="name" name="name" placeholder="<?php lang("placeholder name"); ?>" required="required" value="<?php echo htmlspecialchars($pubdata['name']); ?>" />
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-3">
                    <div class="form-group">
                        <label for="style"><i class="fa fa-star"></i> <?php lang('style'); ?></label>
                        <select name="style" class="form-control" required>
                            <?php
                            $styles = $database->select("pub_styles", ['styleid', 'stylename']);
                            foreach ($styles as $s) {
                                $si = $s['styleid'];
                                $sn = $s['stylename'];
                                $ss = $pubdata["styleid"] == $si ? " selected" : "";
                                echo "<option value=\"$si\"$ss>$sn</option>\n";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3">
                    <div class="form-group">
                        <label for="columns"><i class="fa fa-columns"></i> <?php lang('columns'); ?></label>
                        <input type="number" class="form-control" id="columns" name="columns" placeholder="2" value="<?php echo $pubdata['columns']; ?>" required />
                    </div>
                </div>
                <div class="col-xs-12 col-md-3">
                    <div class="form-group">
                        <label for="perm"><i class="fa fa-eye"></i> <?php lang('visibility'); ?></label>
                        <select name="perm" class="form-control" required>
                            <?php
                            $perms = $database->select("pub_permissions", ['permid', 'permname']);
                            foreach ($perms as $p) {
                                $pi = $p['permid'];
                                $pn = $p['permname'];
                                $ps = $pubdata["permid"] == $pi ? " selected" : "";
                                echo "<option value=\"$pi\"$ps>$pn</option>\n";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="pubid" value="<?php
        if ($editing && !$cloning) {
            echo htmlspecialchars($VARS['id']);
        }
        ?>" />
        <input type="hidden" name="action" value="editpub" />
        <input type="hidden" name="source" value="home" />

        <div class="panel-footer">
            <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> <?php lang("save"); ?></button>
            &nbsp; <a href="app.php?page=content&pubid=<?php echo htmlspecialchars($VARS['id']); ?>" class="btn btn-primary"><i class="fa fa-pencil"></i> <?php lang('edit content'); ?></a>
            <?php
            if ($editing && !$cloning) {
                ?>
                <a href="action.php?action=deletepub&source=home&pubid=<?php echo htmlspecialchars($VARS['id']); ?>" class="btn btn-danger btn-xs pull-right mgn-top-8px"><i class="fa fa-times"></i> <?php lang('delete'); ?></a>
                <?php
            }
            ?>
        </div>
    </div>
</form>