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

function userHasRole($role)
{
    return (isset($_SESSION['user_role']) && $_SESSION['user_role'] == $role);
}
