<?php

// List of pages and metadata
define("PAGES", [
    "home" => [
        "title" => "home",
        "navbar" => true,
        "icon" => "home",
        "styles" => [
            "static/css/datatables.min.css",
            "static/css/tables.css"
        ],
        "scripts" => [
            "static/js/datatables.min.js",
            "static/js/home.js"
        ],
    ],
    "editpub" => [
        "title" => "edit publication",
        "navbar" => false,
        "scripts" => [
            "static/js/editpub.js"
        ],
    ],
    "content" => [
        "title" => "content",
        "navbar" => true,
        "icon" => "paragraph",
    ],
    "maillist" => [
        "title" => "mailing lists",
        "navbar" => true,
        "icon" => "envelope",
        "styles" => [
            "static/css/datatables.min.css",
            "static/css/tables.css"
        ],
        "scripts" => [
            "static/js/datatables.min.js",
            "static/js/maillist.js"
        ],
    ],
    "404" => [
        "title" => "404 error"
    ]
]);
