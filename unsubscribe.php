<?php

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

require __DIR__ . "/required.php";

require __DIR__ . "/lib/iputils.php";

?>
<!DOCTYPE html>
<title>Unsubscribe</title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<?php

if (isset($VARS['a'])) {
    echo "<p>";
    $address = $VARS['a'];

    engageRateLimit();

    if (!filter_var($address, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address.");
    }

    $address = str_replace("%", '\%', $address);

    if ($database->has('addresses', ['email' => $address])) {
        $count = $database->count('addresses', ['email' => $address]);
        $database->delete('addresses', ['email' => $address]);
        die("$address has been removed from $count mailing " . ($count === 1 ? "list" : "lists") . ".");
    } else {
        die("$address has already been removed.");
    }
} else {
    ?>
<p>Enter your email address below to unsubscribe:</p>
<form action="" method="GET">
    <input type="email" name="a" placeholder="xxxxx@example.com" required />
    <br />
    <input type="submit" value="Unsubscribe" />
</form>
    <?php
}