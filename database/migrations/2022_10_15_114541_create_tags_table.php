<?php declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->string('name')->unique();
        });

        Schema::create('job_tag', function (Blueprint $table) {
            $table->unsignedInteger('job_id');

            $table->foreign('job_id')
                ->references('id')
                ->on('job_vacancies')
                ->onDelete('cascade')
            ;
            $table->unsignedInteger('tag_id');

            $table->foreign('tag_id')
                ->references('id')
                ->on('tags')
                ->onDelete('cascade')
            ;

            $table->primary(['job_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_tag');
        Schema::dropIfExists('tags');
    }
};
