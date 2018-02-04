<?php

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */


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
        "styles" => [
            "static/css/summernote.css",
            "static/css/content.css",
        ],
        "scripts" => [
            "static/js/summernote.min.js",
            "static/js/content.js"
        ]
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
    "editlist" => [
        "title" => "edit list",
        "navbar" => false,
        "scripts" => [
            "static/js/editlist.js"
        ],
    ],
    "404" => [
        "title" => "404 error"
    ]
]);
