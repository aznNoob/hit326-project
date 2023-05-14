<?php
/* 
* Session Helper Function
*/

session_start();

// Check if user is logged in by seeing session variable is set
function checkLoggedIn()
{
    return (isset($_SESSION['user_id']));
}

function userHasRole($role)
{
    return (isset($_SESSION['user_role']) && $_SESSION['user_role'] == $role);
}

function displayDate($date)
{
    $now = new DateTime();
    $inputDate = new DateTime($date);
    $interval = $now->diff($inputDate);

    $days = $interval->days;
    $months = $interval->m;
    $years = $interval->y;

    if ($years > 0) {
        return $years . ' year' . ($years > 1 ? 's' : '') . ' ago';
    } elseif ($months > 0) {
        return $months . ' month' . ($months > 1 ? 's' : '') . ' ago';
    } elseif ($days > 1) {
        return $days . ' days ago';
    } elseif ($days == 1) {
        return 'Yesterday';
    } else {
        return 'Today';
    }
}
