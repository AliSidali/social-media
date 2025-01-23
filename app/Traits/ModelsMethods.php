<?php

namespace App\Traits;

trait ModelsMethods
{
    public function fromTimestampToTimeUnits($time)
    {
        if (floor($time->diffInMinutes(now())) == 0) {
            return "just now";
        } elseif ($time->diffInMinutes(now()) < 60) {
            return round($time->diffInMinutes(now())) . " mins ago";
        } elseif ($time->diffInHours(now()) < 24) {
            return round($time->diffInHours(now())) . " hours ago";
        } elseif ($time->diffInDays(now()) < 7) {
            return round($time->diffInDays(now())) . " days ago";
        } elseif ($time->diffIndays(now()) < 30) {
            return round($time->diffInWeeks(now())) . " weeks ago";
        } elseif ($time->diffInMonths(now()) < 12) {
            return round($time->diffInMonths(now())) . " months ago";
        } else {
            return round($time->diffInYears(now())) . " years ago";
        }
    }
}
