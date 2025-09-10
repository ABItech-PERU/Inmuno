<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vacuna;

class VacunasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vacunas = [
            [
                'nombre' => 'BCG',
                'descripcion' => 'Vacuna contra la tuberculosis',
                'fabricante' => 'Instituto Serum',
                'tipo' => 'Bacteria atenuada',
                'dosis_requeridas' => 1,
                'edad_minima' => 0, // recién nacido
                'edad_maxima' => 12, // hasta 1 año
                'contraindicaciones' => 'Inmunodeficiencia, VIH sintomático',
                'efectos_secundarios' => 'Eritema local, adenopatía regional',
                'activa' => true
            ],
            [
                'nombre' => 'Hepatitis B',
                'descripcion' => 'Vacuna contra la hepatitis B',
                'fabricante' => 'GSK',
                'tipo' => 'Subunidad viral',
                'dosis_requeridas' => 3,
                'intervalo_entre_dosis' => 30, // días
                'edad_minima' => 0,
                'edad_maxima' => null,
                'contraindicaciones' => 'Alergia severa a componentes de la vacuna',
                'efectos_secundarios' => 'Dolor local, febrícula',
                'activa' => true
            ],
            [
                'nombre' => 'Polio (IPV)',
                'descripcion' => 'Vacuna inactivada contra la poliomielitis',
                'fabricante' => 'Sanofi Pasteur',
                'tipo' => 'Virus inactivado',
                'dosis_requeridas' => 4,
                'intervalo_entre_dosis' => 60,
                'edad_minima' => 2,
                'edad_maxima' => 72, // hasta 6 años
                'contraindicaciones' => 'Alergia a neomicina, estreptomicina o polimixina B',
                'efectos_secundarios' => 'Dolor local, irritabilidad',
                'activa' => true
            ],
            [
                'nombre' => 'DPT (Pentavalente)',
                'descripcion' => 'Vacuna contra difteria, pertussis, tétanos, hepatitis B y haemophilus influenzae tipo b',
                'fabricante' => 'Instituto Butantan',
                'tipo' => 'Combinada',
                'dosis_requeridas' => 3,
                'intervalo_entre_dosis' => 60,
                'edad_minima' => 2,
                'edad_maxima' => 24,
                'contraindicaciones' => 'Encefalopatía previa por pertussis',
                'efectos_secundarios' => 'Fiebre, dolor local, irritabilidad',
                'activa' => true
            ],
            [
                'nombre' => 'Neumococo',
                'descripcion' => 'Vacuna conjugada contra neumococo 13-valente',
                'fabricante' => 'Pfizer',
                'tipo' => 'Conjugada',
                'dosis_requeridas' => 4,
                'intervalo_entre_dosis' => 60,
                'edad_minima' => 2,
                'edad_maxima' => 24,
                'contraindicaciones' => 'Alergia severa a componentes',
                'efectos_secundarios' => 'Fiebre, dolor local, somnolencia',
                'activa' => true
            ],
            [
                'nombre' => 'Rotavirus',
                'descripcion' => 'Vacuna oral contra rotavirus',
                'fabricante' => 'GSK',
                'tipo' => 'Virus atenuado',
                'dosis_requeridas' => 2,
                'intervalo_entre_dosis' => 60,
                'edad_minima' => 2,
                'edad_maxima' => 8,
                'contraindicaciones' => 'Inmunodeficiencia, enfermedad gastrointestinal aguda',
                'efectos_secundarios' => 'Irritabilidad, diarrea leve',
                'activa' => true
            ],
            [
                'nombre' => 'SPR (Triple Viral)',
                'descripcion' => 'Vacuna contra sarampión, paperas y rubéola',
                'fabricante' => 'Instituto Serum',
                'tipo' => 'Virus atenuado',
                'dosis_requeridas' => 2,
                'intervalo_entre_dosis' => 365, // 1 año
                'edad_minima' => 12,
                'edad_maxima' => 72,
                'contraindicaciones' => 'Embarazo, inmunodeficiencia, alergia a neomicina',
                'efectos_secundarios' => 'Fiebre, rash, dolor articular',
                'activa' => true
            ],
            [
                'nombre' => 'Varicela',
                'descripcion' => 'Vacuna contra la varicela',
                'fabricante' => 'GSK',
                'tipo' => 'Virus atenuado',
                'dosis_requeridas' => 2,
                'intervalo_entre_dosis' => 90,
                'edad_minima' => 12,
                'edad_maxima' => null,
                'contraindicaciones' => 'Embarazo, inmunodeficiencia, terapia con salicilatos',
                'efectos_secundarios' => 'Dolor local, fiebre leve, rash varicela-like',
                'activa' => true
            ],
            [
                'nombre' => 'Influenza',
                'descripcion' => 'Vacuna anual contra la influenza',
                'fabricante' => 'Sanofi Pasteur',
                'tipo' => 'Virus fraccionado',
                'dosis_requeridas' => 1,
                'edad_minima' => 6,
                'edad_maxima' => null,
                'contraindicaciones' => 'Alergia severa al huevo, enfermedad febril aguda',
                'efectos_secundarios' => 'Dolor local, mialgia, febrícula',
                'activa' => true
            ],
            [
                'nombre' => 'COVID-19 (Pfizer)',
                'descripcion' => 'Vacuna contra SARS-CoV-2',
                'fabricante' => 'Pfizer-BioNTech',
                'tipo' => 'ARNm',
                'dosis_requeridas' => 2,
                'intervalo_entre_dosis' => 21,
                'edad_minima' => 144, // 12 años
                'edad_maxima' => null,
                'contraindicaciones' => 'Alergia severa a componentes de la vacuna',
                'efectos_secundarios' => 'Dolor local, fatiga, cefalea, fiebre',
                'activa' => true
            ]
        ];

        foreach ($vacunas as $vacuna) {
            Vacuna::create($vacuna);
        }
    }
}
