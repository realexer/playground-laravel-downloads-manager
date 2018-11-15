<?php

namespace App\DownloadsManager\Models\Download;

class Status 
{
    const SUBMITTED = "SUBMITTED";
    const PROCESSING = "PROCESSING";
    const COMPLETED = "COMPLETED";
    const FAILED = "FAILED";

    public static function getAll() 
    {
        return [
            self::SUBMITTED,
            self::PROCESSING,
            self::COMPLETED,
            self::FAILED
        ];
    }
}