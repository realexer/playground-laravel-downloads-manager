<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\DownloadsManager\Models\Download\Status as DownloadStatus;
use App\DownloadsManager\Models\meta\TablesList;

class CreateDownloadLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(TablesList::downloads_log, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('download_id');
            $table->enum('download_status', DownloadStatus::getAll());
            $table->text('message');
            $table->timestamps();

            $table->foreign('download_id')->references('id')->on(TablesList::downloads);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(TablesList::downloads_log);
    }
}
