<?php

declare (strict_types = 1);

use Phantom\Helper\Session;

if ($message = Session::getNextClear('success')) {
    echo '<div class="alert alert-success py-2 text-center message-alert">
        ' . $message . '
        <div class = "close close-button">X</div>
    </div>
  ';
}

if ($message = Session::getNextClear('error')) {
    echo '<div class="alert alert-danger py-2 text-center message-alert">
        ' . $message . '
        <div class = "close close-button">X</div>
    </div>
  ';
}

?>

<script> initCloseAlertButtons(); </script>
