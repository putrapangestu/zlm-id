<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LaptopCatalogSpreadsheetService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaptopCatalogSpreadsheetController extends Controller
{
    public function template(LaptopCatalogSpreadsheetService $spreadsheetService): StreamedResponse
    {
        return $this->download($spreadsheetService, false, 'template-katalog-laptop.xlsx');
    }

    public function export(LaptopCatalogSpreadsheetService $spreadsheetService): StreamedResponse
    {
        return $this->download($spreadsheetService, true, 'katalog-laptop-'.now()->format('Ymd-His').'.xlsx');
    }

    public function import(Request $request, LaptopCatalogSpreadsheetService $spreadsheetService): RedirectResponse
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        $result = $spreadsheetService->import($validated['file']->getRealPath(), $request->user()?->id);

        return redirect()->route('admin.laptops.index')->with(
            'success',
            "Impor selesai: {$result['created']} unit baru dan {$result['updated']} unit diperbarui."
        );
    }

    private function download(
        LaptopCatalogSpreadsheetService $spreadsheetService,
        bool $withData,
        string $filename,
    ): StreamedResponse {
        return response()->streamDownload(function () use ($spreadsheetService, $withData): void {
            (new Xlsx($spreadsheetService->makeSpreadsheet($withData)))->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
