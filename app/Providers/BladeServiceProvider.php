<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*
         * Register alias for blade components.
         */

        // Generic re-usable components.
        Blade::component('information_sheets.components.base.cardbox', 'cardbox');
        Blade::component('information_sheets.components.base.table', 'table');
        Blade::component('information_sheets.components.base.table_cell', 'table_cell');
        Blade::component('information_sheets.components.base.table_row', 'table_row');
        Blade::component('information_sheets.components.base.link', 'link');
        Blade::component('information_sheets.components.base.type_boolean', 'type_boolean');
        Blade::component('information_sheets.components.base.form', 'form');
        Blade::component('information_sheets.components.base.form_info', 'form_info');
        Blade::component('information_sheets.components.base.form_section', 'form_section');
        Blade::component('information_sheets.components.base.form_assessment', 'form_assessment');
        Blade::component('information_sheets.components.base.field_group', 'field_group');
        Blade::component('information_sheets.components.base.field', 'field');
        Blade::component('information_sheets.components.base.field_audit', 'field_audit');
        Blade::component('information_sheets.components.base.field_boolean', 'field_boolean');
        Blade::component('information_sheets.components.base.field_date', 'field_date');
        Blade::component('information_sheets.components.base.field_duration', 'field_duration');
        Blade::component('information_sheets.components.base.field_language', 'field_language');
        Blade::component('information_sheets.components.base.field_learning_product_type', 'field_learning_product_type');
        Blade::component('information_sheets.components.base.field_list', 'field_list');
        Blade::component('information_sheets.components.base.field_localizable', 'field_localizable');
        Blade::component('information_sheets.components.base.field_lpa_number', 'field_lpa_number');

        // Project components.
        Blade::component('information_sheets.components.project.details', 'project_details');
        Blade::component('information_sheets.components.project.business_case', 'business_case');
        Blade::component('information_sheets.components.project.planned_product_list', 'planned_product_list');
        Blade::component('information_sheets.components.project.gate_one_approval', 'gate_one_approval');
        Blade::component('information_sheets.components.project.learning_products', 'learning_products');

        // Learning Product components.
        Blade::component('information_sheets.components.learning_product.details', 'learning_product_details');
        Blade::component('information_sheets.components.learning_product.design', 'design');
        Blade::component('information_sheets.components.learning_product.design_assessment', 'design_assessment');
        Blade::component('information_sheets.components.learning_product.operational_details', 'operational_details');
        Blade::component('information_sheets.components.learning_product.solution_development', 'solution_development');
        Blade::component('information_sheets.components.learning_product.offering_and_enrolment_estimates', 'offering_and_enrolment_estimates');
        Blade::component('information_sheets.components.learning_product.development_assessment', 'development_assessment');
        Blade::component('information_sheets.components.learning_product.communications_material', 'communications_material');
        Blade::component('information_sheets.components.learning_product.communications_material_assessment', 'communications_material_assessment');

        /*
         * Register blade directives.
         */

        // Add logger for debugging.
        Blade::directive('logger', function ($expression) {
            return "<?php logger($expression) ?>";
        });

        // Check whether a learning product is of a certain types.
        Blade::if('is_learning_product', function ($learningProduct, $types) {
            if (is_string($types)) {
                $types = [$types];
            }
            $learningProductType = $learningProduct['type']['name_key'];
            $learningProductType .= $learningProduct['sub_type'] ? '.' . $learningProduct['sub_type']['name_key'] : '';
            return in_array($learningProductType, $types);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
