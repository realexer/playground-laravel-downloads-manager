<?php

namespace App\DownloadsManager\Models;

use Illuminate\Database\Eloquent\Model;
use App\DownloadsManager\Models\meta\TablesList;

class DownloadsLog extends Model
{
    //
    protected $table = TablesList::downloads_log;
}
