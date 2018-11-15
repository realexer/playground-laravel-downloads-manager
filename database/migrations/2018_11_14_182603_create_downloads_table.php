<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\DownloadsManager\Models\Download\Status as DownloadStatus;
use App\DownloadsManager\Models\meta\TablesList;

class CreateDownloadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(TablesList::downloads, function (Blueprint $table) {
            $table->increments('id');
            $table->string('filename');
            $table->text('original_url');
            $table->enum('status', DownloadStatus::getAll());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(TablesList::downloads);
    }
}
