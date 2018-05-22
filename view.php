<?php

$id = $_GET['id'];
$id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

header("Location: lib/gencontent.php?pubid=" . $_GET['id']);
?>
<!DOCTYPE html>
<title>Redirect</title>
<a href="lib/gencontent.php?pubid=<?php echo $_GET['id']; ?>">View Document</a>