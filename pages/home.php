<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

require_once __DIR__ . '/../required.php';
require_once __DIR__ . "/../lib/userinfo.php";

redirectifnotloggedin();
?>

<div class="btn-group mb-4">
    <a href="app.php?page=editpub" class="btn btn-success"><i class="fas fa-plus"></i> <?php lang("new publication"); ?></a>
</div>

<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link <?php echo $_GET['view'] != "list" ? "active" : "" ?>" href="./app.php?page=home&view=grid" role="tab"><i class="fas fa-th-large"></i> <?php lang("grid"); ?></a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo $_GET['view'] == "list" ? "active" : "" ?>" href="./app.php?page=home&view=list" role="tab"><i class="fas fa-list-ul"></i> <?php lang("list"); ?></a>
    </li>
</ul>

<?php if ($_GET['view'] != "list") { ?>
    <div class="row ml-1">
        <div class="input-group">
            <input type="text" id="search" placeholder="<?php lang("search"); ?>" class="form-control col-12 col-sm-6 col-md-4 col-lg-3" />
        </div>
    </div>
    <div class="p-1"></div>
    <div class="row" id="grid">
        <div class="col-1 sizer-element"></div>
        <?php
        $where = [];
        $where["OR #perms"] = [
            "uid" => $_SESSION['uid'],
            "permname #logg" => "LOGGEDIN",
            "permname #link" => "LINK"
        ];
        $pubs = $database->select('publications', [
            '[>]pub_permissions' => ['permid' => 'permid'],
            '[>]page_sizes' => ['page_size' => 'sizeid']
                ], [
            'pubid',
            'pubname',
            'uid',
            'pubdate',
            'style',
            'columns',
            'permname',
            'pwd',
            'publications.permid',
            "page_size",
            "sizename",
            "landscape"
                ], $where);

        $usercache = [];

        foreach ($pubs as $p) {
            if (is_null($p['uid'])) {
                $p["username"] = "";
            } else {
                if (!isset($usercache[$p['uid']])) {
                    $usercache[$p['uid']] = getUserByID($p['uid']);
                }
                $p["username"] = $usercache[$p['uid']]['name'];
            }

            $p["pagesize"] = lang2("page size and orientation", [
                "size" => $p["sizename"],
                "orientation" => ( $p["landscape"] == 0 ? lang("portrait", false) : lang("landscape", false) )
                    ], false);
            $p["visibility"] = lang("visibility " . strtolower($p["permname"]), false);
            $p['date'] = date(DATETIME_FORMAT, strtotime($p["pubdate"]));
            $p['longdate'] = date("l F j Y", strtotime($p["pubdate"]));

            if ($p["uid"] == $_SESSION['uid']) {
                $p["editbtn"] = '<a class="btn btn-primary btn-sm" href="app.php?page=content&pubid=' . $p['pubid'] . '"><i class="fas fa-edit"></i> ' . lang("edit", false) . '</a>';
                $p["editbtn"] .= ' <a class="btn btn-secondary btn-sm" href="app.php?page=editpub&id=' . $p['pubid'] . '"><i class="fas fa-paint-brush"></i> ' . lang("format", false) . '</a>';
            } else {
                $p["editbtn"] = '<a class="btn btn-info btn-sm" href="app.php?page=content&pubid=' . $p['pubid'] . '"><i class="fas fa-eye"></i> ' . lang("view", false) . '</a>';
            }


            $themedir = __DIR__ . "/../themes/";
            $s = $p['style'];
            $info = json_decode(file_get_contents($themedir . "$s/info.json"), TRUE);
            $colorvars = json_decode(file_get_contents($themedir . "$s/vars.json"), TRUE);
            ?>
            <div class="pub__brick col-12 col-sm-6 col-md-4 col-lg-3 mb-4" data-groups="[]" data-keywords="<?php echo htmlspecialchars($p['pubname']) . " " . $p['sizename'] . " " . $p['style'] . " " . $p['longdate']; ?>">
                <style nonce="<?php echo $SECURE_NONCE; ?>">
                    #pub_card_<?php echo $p['pubid']; ?> {
                        background: <?php echo $colorvars['background']; ?>;
                        color: <?php echo $colorvars['text']; ?>;
                        border-color: <?php echo $colorvars['primary']; ?>;
                        border-width: 1px;
                        <?php
                        if (file_exists($themedir . "$s/background.png")) {
                            echo "background-image: url(themes/$s/background.png);";
                        } else if (file_exists($themedir . "$s/background.jpg")) {
                            echo "background-image: url(themes/$s/background.jpg);";
                        }
                        ?>
                    }

                    #pub_card_<?php echo $p['pubid']; ?> .card-header {
                        color: <?php echo $colorvars['headings']; ?>;
                    }

                    #pub_card_<?php echo $p['pubid']; ?> .list-group-item {
                        background: <?php echo $colorvars['background']; ?>;
                        color: <?php echo $colorvars['text']; ?>;
                        border-color: <?php echo $colorvars['secondary']; ?>;
                    }
                </style>
                <div class="card mt-1" id="pub_card_<?php echo $p['pubid']; ?>">
                    <a href="app.php?page=content&pubid=<?php echo $p['pubid']; ?>" class="no-underline">
                        <h5 class="card-header">
                            <?php echo htmlspecialchars($p['pubname']); ?>
                        </h5>
                    </a>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <i class="fas fa-fw fa-user"></i> <?php echo $p["username"]; ?>
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-fw fa-calendar"></i> <?php echo $p['date']; ?>
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-fw fa-file"></i> <?php echo $p["pagesize"]; ?>
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-fw fa-eye"></i> <?php echo $p["visibility"]; ?>
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <?php echo $p['editbtn']; ?>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
<?php } ?>

<?php if ($_GET['view'] == "list") { ?>
    <table id="pubtable" class="table table-bordered table-hover table-sm">
        <thead>
            <tr>
                <th data-priority="0"></th>
                <th data-priority="1"><?php lang('actions'); ?></th>
                <th data-priority="1"><i class="fas fa-fw fa-font d-none d-md-inline"></i> <?php lang('name'); ?></th>
                <th data-priority="2"><i class="fas fa-fw fa-calendar d-none d-md-inline"></i> <?php lang('date'); ?></th>
                <th data-priority="2"><i class="fas fa-fw fa-user d-none d-md-inline"></i> <?php lang('author'); ?></th>
                <th data-priority="4"><i class="fas fa-fw fa-star d-none d-md-inline"></i> <?php lang('theme'); ?></th>
                <th data-priority="4"><i class="fas fa-fw fa-columns d-none d-md-inline"></i> <?php lang('columns'); ?></th>
                <th data-priority="3"><i class="fas fa-fw fa-file d-none d-md-inline"></i> <?php lang('page size'); ?></th>
                <th data-priority="2"><i class="fas fa-fw fa-eye d-none d-md-inline"></i> <?php lang('visibility'); ?></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                <th data-priority="0"></th>
                <th data-priority="1"><?php lang('actions'); ?></th>
                <th data-priority="1"><i class="fas fa-fw fa-font d-none d-md-inline"></i> <?php lang('name'); ?></th>
                <th data-priority="2"><i class="fas fa-fw fa-calendar d-none d-md-inline"></i> <?php lang('date'); ?></th>
                <th data-priority="2"><i class="fas fa-fw fa-user d-none d-md-inline"></i> <?php lang('author'); ?></th>
                <th data-priority="4"><i class="fas fa-fw fa-star d-none d-md-inline"></i> <?php lang('theme'); ?></th>
                <th data-priority="4"><i class="fas fa-fw fa-columns d-none d-md-inline"></i> <?php lang('columns'); ?></th>
                <th data-priority="3"><i class="fas fa-fw fa-file d-none d-md-inline"></i> <?php lang('page size'); ?></th>
                <th data-priority="2"><i class="fas fa-fw fa-eye d-none d-md-inline"></i> <?php lang('visibility'); ?></th>
            </tr>
        </tfoot>
    </table>
<?php } ?>