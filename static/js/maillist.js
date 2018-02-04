/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

var table = $('#listtable').DataTable({
    responsive: {
        details: {
            display: $.fn.dataTable.Responsive.display.modal({
                header: function (row) {
                    var data = row.data();
                    return "<i class=\"fa fa-envelope fa-fw\"></i> " + data[2];
                }
            }),
            renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                tableClass: 'table'
            }),
            type: "column"
        }
    },
    columnDefs: [
        {
            targets: 0,
            className: 'control',
            orderable: false
        },
        {
            targets: 1,
            orderable: false
        },
    ],
    order: [
        [1, 'asc']
    ],
    serverSide: true,
    ajax: {
        url: "lib/getlisttable.php",
        dataFilter: function (data) {
            var json = jQuery.parseJSON(data);
            json.data = [];
            json.lists.forEach(function (row) {
                json.data.push([
                    "",
                    row.editbtn + " " + row.clonebtn,
                    row.listname,
                    row.count
                ]);
            });
            return JSON.stringify(json);
        }
    }
});