<?php
if (isset($_SESSION['email'])) {
    session_destroy();
}
?>