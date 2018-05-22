<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

$id = $_GET['id'];
$id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

header("Location: lib/gencontent.php?pubid=" . $_GET['id']);
?>
<!DOCTYPE html>
<title>Redirect</title>
<a href="lib/gencontent.php?pubid=<?php echo $_GET['id']; ?>">View Document</a>