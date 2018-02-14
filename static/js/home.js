if ($("#grid").length) {
    window.shuffleInstance = new window.Shuffle(document.getElementById('grid'), {
        itemSelector: '.pub__brick',
        sizer: '.sizer-element',
    });

    $("#search").on("keyup", function () {
        var q = $("#search").val().toLowerCase();
        shuffleInstance.filter(function (element) {
            return element.getAttribute('data-keywords').toLowerCase().includes(q);
        });
    });
} else {
    var pubtable = $('#pubtable').DataTable({
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal({
                    header: function (row) {
                        var data = row.data();
                        return "<i class=\"fa fa-cube fa-fw\"></i> " + data[2];
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
            {
                targets: 4,
                orderable: false
            }
        ],
        order: [
            [2, 'asc']
        ],
        serverSide: true,
        ajax: {
            url: "lib/getpubtable.php",
            dataFilter: function (data) {
                var json = jQuery.parseJSON(data);
                json.data = [];
                json.pubs.forEach(function (row) {
                    json.data.push([
                        "",
                        row.editbtn + " " + row.clonebtn,
                        row.pubname,
                        row.pubdate,
                        row.username,
                        row.style,
                        row.columns,
                        row.pagesize,
                        row.visibility
                    ]);
                });
                return JSON.stringify(json);
            }
        }
    });
}