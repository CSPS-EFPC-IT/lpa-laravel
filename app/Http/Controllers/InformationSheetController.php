<?php

namespace App\Http\Controllers;

use App\Models\InformationSheet\InformationSheet;
use Illuminate\Support\Facades\View;

class InformationSheetController extends APIController
{
    /**
     * Retrieve information sheet data.
     *
     * @param  InformationSheet $informationSheet
     * @return array
     */
    protected function getInformationSheetData($informationSheet)
    {
        return [
            'definition' => $informationSheet->definition->toArray(),
            'data'       => $informationSheet->getData(),
        ];
    }

    /**
     * Show all available information sheets for a specific entity.
     *
     * @param  App\Models\BaseModel $entityType
     * @param  int $entityId
     * @return \Illuminate\Http\Response
     */
    public function show($entityType, $entityId)
    {
        $informationSheets = $entityType->findOrFail($entityId)->informationSheets;

        return $this->respond([
            'information_sheets' => $informationSheets,
        ]);
    }

    /**
     * Render an information sheet for display and/or print.
     *
     * @param  InformationSheet $informationSheet
     * @return view
     */
    public function render(InformationSheet $informationSheet)
    {
        // Share language variable accross all views and components.
        View::share('lang', app()->getLocale());

        return view("information_sheets.sheets.{$informationSheet->viewFilePath}", $this->getInformationSheetData($informationSheet));
    }

    /**
     * Return raw data provided for a specific information sheet.
     * Useful when debugging or creating new information sheets.
     *
     * @param  InformationSheet $informationSheet
     * @return \Illuminate\Http\Response
     */
    public function data(InformationSheet $informationSheet)
    {
        return $this->getInformationSheetData($informationSheet);
    }
}
