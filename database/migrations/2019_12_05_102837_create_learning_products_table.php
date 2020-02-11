<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLearningProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learning_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('entity_type');
            $table->string('name');
            $table->unsignedInteger('type_id');
            $table->unsignedInteger('sub_type_id')->nullable();
            $table->unsignedInteger('organizational_unit_id')->nullable();
            $table->unsignedInteger('project_id')->nullable();
            $table->unsignedInteger('state_id');
            $table->unsignedInteger('process_instance_id')->unique()->nullable();
            $table->unsignedInteger('primary_contact');
            $table->unsignedInteger('manager');

            // Form data entities.
            $table->unsignedInteger('design_id')->unique()->nullable();
            $table->unsignedInteger('design_assessment_id')->unique()->nullable();
            $table->unsignedInteger('operational_details_id')->unique()->nullable();
            $table->unsignedInteger('solution_development_id')->unique()->nullable();
            $table->unsignedInteger('offering_and_enrolment_estimates_id')->unique()->nullable();
            $table->unsignedInteger('development_assessment_id')->unique()->nullable();
            $table->unsignedInteger('communications_material_id')->unique()->nullable();
            $table->unsignedInteger('communications_material_assessment_id')->unique()->nullable();

            // Audit and timestamps.
            $table->auditable();
            $table->softDeletes();
            $table->timestamps();

            // Foreing keys.
            $table->referenceOn('organizational_unit_id');
            $table->referenceOn('state_id');
            $table->referenceOn('process_instance_id');
            $table->referenceOn('project_id')->onDelete('cascade');
            $table->referenceOn('type_id', 'learning_product_types');
            $table->referenceOn('sub_type_id', 'learning_product_types');
            $table->referenceOn('primary_contact', 'users');
            $table->referenceOn('manager', 'users');
            $table->referenceOn('design_id');
            $table->referenceOn('design_assessment_id');
            $table->referenceOn('operational_details_id');
            $table->referenceOn('solution_development_id');
            $table->referenceOn('offering_and_enrolment_estimates_id');
            $table->referenceOn('development_assessment_id');
            $table->referenceOn('communications_material_id');
            $table->referenceOn('communications_material_assessment_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('learning_products');
    }
}
