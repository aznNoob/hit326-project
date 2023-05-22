<?php

function displayDate($dateTime)
{
    date_default_timezone_set('Australia/Darwin');
    $now = new DateTime();
    $dateTime = new DateTime($dateTime);
    $interval = $now->diff($dateTime);

    if ($interval->y >= 1) {
        $suffix = $interval->y > 1 ? ' years ago' : ' year ago';
        return $interval->y . $suffix;
    } elseif ($interval->m >= 1) {
        $suffix = $interval->m > 1 ? ' months ago' : ' month ago';
        return $interval->m . $suffix;
    } elseif ($interval->d >= 1) {
        if ($interval->d == 1) {
            return 'Yesterday';
        } elseif ($interval->d == 7) {
            return '1 week ago';
        } else {
            return $interval->d . ' days ago';
        }
    } elseif ($interval->h >= 1) {
        $suffix = $interval->h > 1 ? ' hours ago' : ' hour ago';
        return $interval->h . $suffix;
    } elseif ($interval->i >= 1) {
        $suffix = $interval->i > 1 ? ' minutes ago' : ' minute ago';
        return $interval->i . $suffix;
    } else {
        $suffix = $interval->s > 1 ? ' seconds ago' : ' second ago';
        return $interval->s . $suffix;
    }
}

function displayStatus($status)
{
    $status = ucfirst($status);
    if ($status == 'Draft') {
        return '<span class="text-dark"><i class="fa-solid fa-compass-drafting mx-1"></i></i>Draft</span>';
    } elseif ($status == 'Pending_review') {
        return '<span class="text-warning"><i class="fa-regular fa-clock mx-1"></i>Pending Review</span>';
    } elseif ($status == 'Published') {
        return '<span class="text-success"><i class="fa-solid fa-check mx-1"></i>Published</span>';
    } elseif ($status == 'Rejected') {
        return '<span class="text-danger"><i class="fa-solid fa-xmark mx-1"></i>Rejected</span>';
    } else {
        return 'Error!';
    }
}
