<?php

namespace App\DownloadsManager\Models;

use Illuminate\Database\Eloquent\Model;
use App\DownloadsManager\Models\meta\TablesList;

class Download extends Model
{
    //
    protected $table = TablesList::downloads;
}
