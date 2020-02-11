<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpectedAnnualParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expected_annual_participants', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('design_id');
            $table->unsignedInteger('region_id')->nullable();
            $table->unsignedInteger('expected_annual_participant_number')->nullable();
            // Add foreing keys.
            $table->referenceOn('design_id');
            $table->referenceOn('region_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expected_annual_participants');
    }
}
