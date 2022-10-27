<?php

declare (strict_types = 1);

use Phantom\Helper\Session;

if ($message = Session::get('success', true)) {
    echo '<div class="alert alert-success py-2 text-center message-alert">
        ' . $message . '
        <div class = "close close-button">X</div>
    </div>
  ';
}

if ($message = Session::get('error', true)) {
    echo '<div class="alert alert-danger py-2 text-center message-alert">
        ' . $message . '
        <div class = "close close-button">X</div>
    </div>
  ';
}

?>

<script> initCloseAlertButtons(); </script>
