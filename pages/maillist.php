<?php

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

require_once __DIR__ . '/../required.php';

redirectifnotloggedin();
?>

<div class="btn-group mb-4">
    <a href="app.php?page=editlist" class="btn btn-success"><i class="fas fa-plus"></i> <?php lang("new list"); ?></a>
</div>
<table id="listtable" class="table table-bordered table-hover table-sm">
    <thead>
        <tr>
            <th data-priority="0"></th>
            <th data-priority="1"><?php lang('actions'); ?></th>
            <th data-priority="1"><i class="fas fa-fw fa-font d-none d-md-inline"></i> <?php lang('name'); ?></th>
            <th data-priority="2"><i class="fas fa-fw fa-envelope d-none d-md-inline"></i> <?php lang('addresses'); ?></th>
        </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
        <tr>
            <th data-priority="0"></th>
            <th data-priority="1"><?php lang('actions'); ?></th>
            <th data-priority="1"><i class="fas fa-fw fa-font d-none d-md-inline"></i> <?php lang('name'); ?></th>
            <th data-priority="2"><i class="fas fa-fw fa-envelope d-none d-md-inline"></i> <?php lang('addresses'); ?></th>
        </tr>
    </tfoot>
</table>