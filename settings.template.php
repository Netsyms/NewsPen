<?php

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

// Whether to show debugging data in output.
// DO NOT SET TO TRUE IN PRODUCTION!!!
define("DEBUG", false);

// Database connection settings
// See http://medoo.in/api/new for info
define("DB_TYPE", "mysql");
define("DB_NAME", "newspen");
define("DB_SERVER", "localhost");
define("DB_USER", "newspen");
define("DB_PASS", "");
define("DB_CHARSET", "utf8");

// Name of the app.
define("SITE_TITLE", "NewsPen");

define("SMTP_HOST", "");
define("SMTP_AUTH", true);
define("SMTP_SECURITY", "tls"); // tls, ssl, or none
define("SMTP_PORT", 25);
define("SMTP_USERNAME", "");
define("SMTP_PASSWORD", "");
define("SMTP_FROMADDRESS", "newspen@example.com");
define("SMTP_FROMNAME", "NewsPen");
define("SMTP_BATCH_SIZE", 20);

// URL of the AccountHub API endpoint
define("PORTAL_API", "http://localhost/accounthub/api.php");
// URL of the AccountHub home page
define("PORTAL_URL", "http://localhost/accounthub/home.php");
// AccountHub API Key
define("PORTAL_KEY", "123");

// For supported values, see http://php.net/manual/en/timezones.php
define("TIMEZONE", "America/Denver");

define("DATETIME_FORMAT", "M j Y g:i A"); // 12 hour time
#define("DATETIME_FORMAT", "M j Y G:i"); // 24 hour time

// Base URL for site links.
define('URL', '.');

// Use Captcheck on login screen
// https://captcheck.netsyms.com
define("CAPTCHA_ENABLED", FALSE);
define('CAPTCHA_SERVER', 'https://captcheck.netsyms.com');

// See lang folder for language options
define('LANGUAGE', "en_us");


define("FOOTER_TEXT", "");
define("COPYRIGHT_NAME", "Netsyms Technologies");
