<?php

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */


// List of pages and metadata
define("PAGES", [
    "home" => [
        "title" => "home",
        "navbar" => true,
        "icon" => "fas fa-home",
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
        "styles" => [
            "static/css/themeselector.css"
        ],
        "scripts" => [
            "static/js/editpub.js"
        ],
    ],
    "content" => [
        "title" => "editor",
        "navbar" => true,
        "icon" => "fas fa-edit",
        "styles" => [
            "static/css/summernote-lite.css",
            "static/css/content.css",
        ],
        "scripts" => [
            "static/js/summernote-lite.js",
            "static/js/content.js"
        ]
    ],
    "maillist" => [
        "title" => "mailing lists",
        "navbar" => true,
        "icon" => "fas fa-envelope",
        "styles" => [
            "static/css/datatables.min.css",
            "static/css/tables.css"
        ],
        "scripts" => [
            "static/js/datatables.min.js",
            "static/js/maillist.js"
        ],
    ],
    "editlist" => [
        "title" => "edit list",
        "navbar" => false,
        "styles" => [
            "static/css/tagsinput.css"
        ],
        "scripts" => [
            "static/js/jquery.tagsinput.min.js",
            "static/js/editlist.js"
        ],
    ],
    "404" => [
        "title" => "404 error"
    ]
]);
