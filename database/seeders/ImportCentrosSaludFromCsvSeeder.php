<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CentroSalud;

class ImportCentrosSaludFromCsvSeeder extends Seeder
{
    /**
     * Este seeder importa el CSV oficial descargado desde Datos Abiertos
     * Debes colocar el archivo en: database/seeders/data/hospitalesopendata.csv
     * y luego ejecutar: php artisan db:seed --class=ImportCentrosSaludFromCsvSeeder
     */
    public function run()
    {
        $file = database_path('seeders/data/hospitalesopendata.csv');

        if (!file_exists($file)) {
            $this->command->error("Archivo no encontrado: {$file}. Descárgalo desde la Plataforma Nacional de Datos Abiertos y colócalo allí.");
            return;
        }

        $handle = fopen($file, 'r');
        if (!$handle) {
            $this->command->error("No se pudo abrir el archivo: {$file}");
            return;
        }

        // Leer encabezados
        $headers = fgetcsv($handle);
        if (!$headers) {
            $this->command->error("CSV vacío o encabezados no válidos.");
            fclose($handle);
            return;
        }

        $map = array_map(function ($h) { return trim(mb_strtoupper($h)); }, $headers);

        $count = 0;
        $row = 0;

        while (($data = fgetcsv($handle)) !== false) {
            $row++;
            $record = [];
            foreach ($map as $i => $col) {
                $record[$col] = isset($data[$i]) ? trim($data[$i]) : null;
            }

            $ubigeo = $record['UBIGEO'] ?? $record['CODIGO_UBIGEO'] ?? null;
            if (!$ubigeo) continue;
            if (substr($ubigeo, 0, 2) !== '10') continue; // Huánuco

            $codigo = 'CS' . $ubigeo . '-' . $row;

            $payload = [
                'codigo' => $codigo,
                'nombre' => $record['NOMBRE'] ?? 'Centro de Salud',
                'direccion' => $record['DIRECCION'] ?? null,
                'telefono' => $record['TELEFONO'] ?? null,
                'email' => $record['EMAIL'] ?? null,
                'tipo' => $record['TIPO'] ?? null,
                'nivel_atencion' => $record['NIVEL'] ?? null,
                'distrito_codigo' => $ubigeo,
                'latitud' => $record['LATITUD'] ?? null,
                'longitud' => $record['LONGITUD'] ?? null,
                'capacidad' => null,
                'servicios' => json_encode([]),
                'director_medico' => null,
                'horario_inicio' => null,
                'horario_fin' => null,
                'activo' => true,
            ];

            CentroSalud::updateOrCreate(['codigo' => $payload['codigo']], $payload);
            $count++;
        }

        fclose($handle);

        $this->command->info("Importados: {$count} establecimientos del departamento Huánuco (filtrados por UBIGEO '10')");
    }
}
