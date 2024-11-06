<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAduansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aduans', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('lokasi')->nullable(); // Nama lokasi
            $table->decimal('latitude', 10, 7)->nullable(); // Latitude
            $table->decimal('longitude', 10, 7)->nullable(); // Longitude
            $table->enum('status', [
                'Pending',
                'In Review',
                'Verified',
                'Beritanggapan',
                'In Progress',
                'Selesai',
                'Rejected',
                'Closed',
                'Duplicate'
            ])->default('Pending');
            $table->string('file')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('kategori_id');
            $table->timestamps();

            // Foreign key relationship
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('kategori_id')->references('id')->on('kategoris')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aduans');
    }
};
