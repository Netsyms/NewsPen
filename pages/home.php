<?php

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

require_once __DIR__ . '/../required.php';
require_once __DIR__ . "/../lib/userinfo.php";

redirectifnotloggedin();
?>

<div class="btn-group mgn-btm-10px">
    <a href="app.php?page=editpub" class="btn btn-success"><i class="fa fa-plus"></i> <?php lang("new publication"); ?></a>
</div>
<table id="pubtable" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th data-priority="0"></th>
            <th data-priority="1"><?php lang('actions'); ?></th>
            <th data-priority="1"><i class="fa fa-fw fa-font hidden-xs"></i> <?php lang('name'); ?></th>
            <th data-priority="2"><i class="fa fa-fw fa-calendar hidden-xs"></i> <?php lang('date'); ?></th>
            <th data-priority="2"><i class="fa fa-fw fa-user hidden-xs"></i> <?php lang('author'); ?></th>
            <th data-priority="4"><i class="fa fa-fw fa-star hidden-xs"></i> <?php lang('style'); ?></th>
            <th data-priority="4"><i class="fa fa-fw fa-columns hidden-xs"></i> <?php lang('columns'); ?></th>
            <th data-priority="3"><i class="fa fa-fw fa-file-o hidden-xs"></i> <?php lang('page size'); ?></th>
            <th data-priority="2"><i class="fa fa-fw fa-eye hidden-xs"></i> <?php lang('visibility'); ?></th>
        </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
        <tr>
            <th data-priority="0"></th>
            <th data-priority="1"><?php lang('actions'); ?></th>
            <th data-priority="1"><i class="fa fa-fw fa-font hidden-xs"></i> <?php lang('name'); ?></th>
            <th data-priority="2"><i class="fa fa-fw fa-calendar hidden-xs"></i> <?php lang('date'); ?></th>
            <th data-priority="2"><i class="fa fa-fw fa-user hidden-xs"></i> <?php lang('author'); ?></th>
            <th data-priority="4"><i class="fa fa-fw fa-star hidden-xs"></i> <?php lang('style'); ?></th>
            <th data-priority="4"><i class="fa fa-fw fa-columns hidden-xs"></i> <?php lang('columns'); ?></th>
            <th data-priority="3"><i class="fa fa-fw fa-file-o hidden-xs"></i> <?php lang('page size'); ?></th>
            <th data-priority="2"><i class="fa fa-fw fa-eye hidden-xs"></i> <?php lang('visibility'); ?></th>
        </tr>
    </tfoot>
</table>