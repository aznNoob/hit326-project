<?php
/* 
* Session Helper Function
*/

session_start();

// Check if user is logged in by seeing session variable is set
function checkLoggedIn()
{
    return isset($_SESSION['user_id']) ? true : false;
}
