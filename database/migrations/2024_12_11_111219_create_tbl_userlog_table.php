<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblUserlogTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_userlog', function (Blueprint $table) {
            $table->id('userlog_id'); // Primary key
            $table->unsignedBigInteger('user_id'); // Foreign key (unsigned)
            $table->string('userlog_subject'); // Text for the subject
            $table->timestamp('userlog_at'); // Date-time for the log
            $table->json('jsondata'); // JSON data field
            $table->timestamps(); // Adds created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_userlog');
    }
}

