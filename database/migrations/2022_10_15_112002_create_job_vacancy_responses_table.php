<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_vacancy_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('job_id')->index();
            $table->unsignedInteger('user_id')->index();
            $table->boolean('notified')->default(0)->index();
            //$table->timestamp('notified_at')->nullable();
            $table->timestamps();

            $table->softDeletes();
            $table->foreign('job_id')
                ->references('id')
                ->on('job_vacancies')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_vacancy_responses');
    }
};
