<?php
// Page Redirect Function
function redirectURL($page)
{
    header('Location: ' . URLROOT . '/' . $page);
}
