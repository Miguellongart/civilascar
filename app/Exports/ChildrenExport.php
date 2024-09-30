<?php

namespace App\Exports;

use App\Models\Children;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ChildrenExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    public function collection()
    {
        // Cargar al niño con su padre y guardianes
        return Children::with('parent', 'parent.guardians')
            ->get()
            ->map(function ($child) {
                return [
                    'child_name' => $child->name,
                    'child_age' => $child->age,
                    'child_document' => $child->document,
                    'uniform_size' => $child->uniform_size,
                    'birthdate' => $child->birthdate,
                    'parent_name' => optional($child->parent)->name,
                    'parent_dni' => optional($child->parent)->dni,
                    'parent_email' => optional($child->parent)->email,
                    'parent_phone' => optional($child->parent)->phone,
                    'parent_neighborhood' => optional($child->parent)->neighborhood,
                ];
            });
    }

    public function headings(): array
    {
        // Encabezados del archivo Excel
        return [
            'Nombre del Niño',
            'Edad del Niño',
            'Documento del Niño',
            'Talla',
            'fecha nacimiento',
            'Nombre del Padre/Madre',
            'Documento del Padre/Madre',
            'Email del Padre/Madre',
            'telefono',
            'Direccion',
        ];
    }


    public function columnWidths(): array
    {
        // Configurar el ancho de las columnas
        return [
            'A' => 35, // Nombre del Niño
            'B' => 20, // Edad del Niño
            'C' => 30, // Nombre del Padre/Madre
            'D' => 30, // Email del Padre/Madre
            'E' => 25, // Nombre del Guardián 1
            'F' => 30, // Email del Guardián 1
            'G' => 30, // Nombre del Guardián 2
            'H' => 25, // Nombre del Guardián 2
            'I' => 25, // Nombre del Guardián 2
            'J' => 25, // Nombre del Guardián 2
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Aplicar negrita a la primera fila (encabezados)
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);

        // Centrar el contenido en todas las celdas
        $sheet->getStyle('A1:J' . $sheet->getHighestRow())->getAlignment()->setHorizontal('center');

        return [];
    }
}
