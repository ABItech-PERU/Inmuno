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
            // Vacunas del Esquema Nacional de Vacunación del Perú
            [
                'nombre' => 'BCG',
                'descripcion' => 'Vacuna contra el bacilo de Calmette-Guérin, protege contra las formas graves de tuberculosis',
                'fabricante' => 'Instituto Serum',
                'tipo' => 'Bacteria atenuada',
                'dosis_requeridas' => 1,
                'edad_minima' => 0,
                'edad_maxima' => 12,
                'contraindicaciones' => 'Inmunodeficiencia, VIH sintomático',
                'efectos_secundarios' => 'Eritema local, adenopatía regional',
                'activa' => true
            ],
            [
                'nombre' => 'Hepatitis B',
                'descripcion' => 'Vacuna contra el virus de Hepatitis B',
                'fabricante' => 'GSK',
                'tipo' => 'Subunidad viral',
                'dosis_requeridas' => 3,
                'intervalo_entre_dosis' => 60,
                'edad_minima' => 0,
                'edad_maxima' => null,
                'contraindicaciones' => 'Alergia severa a componentes de la vacuna',
                'efectos_secundarios' => 'Dolor local, febrícula',
                'activa' => true
            ],
            [
                'nombre' => 'Pentavalente (DPT-HvB-Hib)',
                'descripcion' => 'Vacuna combinada de 5 componentes que previene difteria, tétanos, tos ferina, neumonía, meningitis por haemophilus tipo b y hepatitis B',
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
                'nombre' => 'Antipolio IPV',
                'descripcion' => 'Vacuna antipolio inactivada inyectable contra la parálisis flácida aguda',
                'fabricante' => 'Sanofi Pasteur',
                'tipo' => 'Virus inactivado',
                'dosis_requeridas' => 4,
                'intervalo_entre_dosis' => 60,
                'edad_minima' => 2,
                'edad_maxima' => 72,
                'contraindicaciones' => 'Alergia a neomicina, estreptomicina o polimixina B',
                'efectos_secundarios' => 'Dolor local, irritabilidad',
                'activa' => true
            ],
            [
                'nombre' => 'Antipolio APO',
                'descripcion' => 'Vacuna antipolio oral contra la parálisis flácida aguda',
                'fabricante' => 'Instituto Butantan',
                'tipo' => 'Virus atenuado oral',
                'dosis_requeridas' => 3,
                'intervalo_entre_dosis' => 60,
                'edad_minima' => 6,
                'edad_maxima' => 72,
                'contraindicaciones' => 'Inmunodeficiencia, enfermedad gastrointestinal',
                'efectos_secundarios' => 'Raramente diarrea leve',
                'activa' => true
            ],
            [
                'nombre' => 'Rotavirus',
                'descripcion' => 'Vacuna oral contra rotavirus que previene las diarreas por rotavirus',
                'fabricante' => 'GSK',
                'tipo' => 'Virus atenuado oral',
                'dosis_requeridas' => 2,
                'intervalo_entre_dosis' => 60,
                'edad_minima' => 2,
                'edad_maxima' => 8,
                'contraindicaciones' => 'Inmunodeficiencia, enfermedad gastrointestinal aguda',
                'efectos_secundarios' => 'Irritabilidad, diarrea leve',
                'activa' => true
            ],
            [
                'nombre' => 'Antineumocócica',
                'descripcion' => 'Vacuna que protege contra la neumonía, meningitis y otitis media',
                'fabricante' => 'Pfizer',
                'tipo' => 'Conjugada',
                'dosis_requeridas' => 3,
                'intervalo_entre_dosis' => 60,
                'edad_minima' => 2,
                'edad_maxima' => 24,
                'contraindicaciones' => 'Alergia severa a componentes',
                'efectos_secundarios' => 'Fiebre, dolor local, somnolencia',
                'activa' => true
            ],
            [
                'nombre' => 'Influenza Pediátrica',
                'descripcion' => 'Vacuna contra la gripe y bronquitis para niños',
                'fabricante' => 'Sanofi Pasteur',
                'tipo' => 'Virus fraccionado',
                'dosis_requeridas' => 2,
                'intervalo_entre_dosis' => 30,
                'edad_minima' => 6,
                'edad_maxima' => 60,
                'contraindicaciones' => 'Alergia severa al huevo, enfermedad febril aguda',
                'efectos_secundarios' => 'Dolor local, mialgia, febrícula',
                'activa' => true
            ],
            [
                'nombre' => 'SPR (Triple Viral)',
                'descripcion' => 'Vacuna contra sarampión, paperas y rubéola',
                'fabricante' => 'Instituto Serum',
                'tipo' => 'Virus atenuado',
                'dosis_requeridas' => 2,
                'intervalo_entre_dosis' => 180,
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
                'dosis_requeridas' => 1,
                'edad_minima' => 12,
                'edad_maxima' => null,
                'contraindicaciones' => 'Embarazo, inmunodeficiencia, terapia con salicilatos',
                'efectos_secundarios' => 'Dolor local, fiebre leve, rash varicela-like',
                'activa' => true
            ],
            [
                'nombre' => 'Antiamarílica (AMA)',
                'descripcion' => 'Vacuna contra la fiebre amarilla',
                'fabricante' => 'Instituto Butantan',
                'tipo' => 'Virus atenuado',
                'dosis_requeridas' => 1,
                'edad_minima' => 15,
                'edad_maxima' => 708, // 59 años
                'contraindicaciones' => 'Embarazo, inmunodeficiencia, alergia al huevo',
                'efectos_secundarios' => 'Dolor local, fiebre leve, mialgia',
                'activa' => true
            ],
            [
                'nombre' => 'Hepatitis A',
                'descripcion' => 'Vacuna contra el virus de la hepatitis A',
                'fabricante' => 'GSK',
                'tipo' => 'Virus inactivado',
                'dosis_requeridas' => 1,
                'edad_minima' => 15,
                'edad_maxima' => null,
                'contraindicaciones' => 'Alergia severa a componentes',
                'efectos_secundarios' => 'Dolor local, fatiga leve',
                'activa' => true
            ],
            [
                'nombre' => 'DPT',
                'descripcion' => 'Vacuna de refuerzo que previene difteria, tétanos y tos ferina',
                'fabricante' => 'Instituto Serum',
                'tipo' => 'Toxoide',
                'dosis_requeridas' => 2,
                'intervalo_entre_dosis' => 780, // 65 meses entre refuerzos
                'edad_minima' => 18,
                'edad_maxima' => 72,
                'contraindicaciones' => 'Encefalopatía previa por pertussis',
                'efectos_secundarios' => 'Dolor local, fiebre, malestar',
                'activa' => true
            ],
            [
                'nombre' => 'Influenza Anual',
                'descripcion' => 'Vacuna anual contra la influenza para todas las edades',
                'fabricante' => 'Sanofi Pasteur',
                'tipo' => 'Virus fraccionado',
                'dosis_requeridas' => 1,
                'edad_minima' => 24,
                'edad_maxima' => null,
                'contraindicaciones' => 'Alergia severa al huevo',
                'efectos_secundarios' => 'Dolor local, mialgia, febrícula',
                'activa' => true
            ],
            [
                'nombre' => 'VPH',
                'descripcion' => 'Vacuna contra el virus del papiloma humano para prevención del cáncer de cuello uterino y verrugas genitales',
                'fabricante' => 'Merck',
                'tipo' => 'Subunidad viral',
                'dosis_requeridas' => 1,
                'edad_minima' => 108, // 9 años
                'edad_maxima' => 216, // 18 años
                'contraindicaciones' => 'Embarazo, alergia severa a componentes',
                'efectos_secundarios' => 'Dolor local, mareo, cefalea',
                'activa' => true
            ],
            [
                'nombre' => 'DT',
                'descripcion' => 'Vacuna contra difteria y tétanos para gestantes',
                'fabricante' => 'Instituto Serum',
                'tipo' => 'Toxoide',
                'dosis_requeridas' => 1,
                'edad_minima' => null,
                'edad_maxima' => null,
                'contraindicaciones' => 'Alergia severa a componentes',
                'efectos_secundarios' => 'Dolor local, febrícula',
                'activa' => true
            ],
            [
                'nombre' => 'Tdap',
                'descripcion' => 'Vacuna contra difteria, tos ferina y tétanos para gestantes (20-36 semanas)',
                'fabricante' => 'GSK',
                'tipo' => 'Toxoide acelular',
                'dosis_requeridas' => 1,
                'edad_minima' => null,
                'edad_maxima' => null,
                'contraindicaciones' => 'Encefalopatía previa por pertussis',
                'efectos_secundarios' => 'Dolor local, fiebre leve, fatiga',
                'activa' => true
            ],
            [
                'nombre' => 'Influenza Gestante',
                'descripcion' => 'Vacuna contra la influenza para gestantes en cualquier trimestre',
                'fabricante' => 'Sanofi Pasteur',
                'tipo' => 'Virus fraccionado',
                'dosis_requeridas' => 1,
                'edad_minima' => null,
                'edad_maxima' => null,
                'contraindicaciones' => 'Alergia severa al huevo',
                'efectos_secundarios' => 'Dolor local, mialgia leve',
                'activa' => true
            ],
            [
                'nombre' => 'Antineumocócica Adulto',
                'descripcion' => 'Vacuna antineumocócica para adultos mayores - protección contra neumonía, meningitis y otitis media',
                'fabricante' => 'Pfizer',
                'tipo' => 'Polisacárida',
                'dosis_requeridas' => 1,
                'edad_minima' => 720, // 60 años
                'edad_maxima' => null,
                'contraindicaciones' => 'Alergia severa a componentes',
                'efectos_secundarios' => 'Dolor local, fatiga leve',
                'activa' => true
            ]
        ];

        foreach ($vacunas as $vacuna) {
            Vacuna::updateOrCreate(
                ['nombre' => $vacuna['nombre']],
                $vacuna
            );
        }
    }
}
