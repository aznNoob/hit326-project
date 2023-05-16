<?php
/* 
* Session Helper Function
*/

// Starts a session on every page
session_start();

// Check if user is logged in by seeing session variable is set
function checkLoggedIn()
{
    return (isset($_SESSION['user_id']));
}

// Checks first if the user role is set and if true, it will check if they are the given input role
function userHasRole($role)
{
    return (isset($_SESSION['user_role']) && $_SESSION['user_role'] == $role);
}
