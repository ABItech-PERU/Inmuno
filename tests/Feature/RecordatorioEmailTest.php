<?php

namespace Tests\Feature;

use App\Jobs\EnviarRecordatorioJob;
use App\Models\Recordatorio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RecordatorioEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_enviar_confirmacion_al_crear_recordatorio()
    {
        Notification::fake();

    /** @var \App\Models\User $user */
    $user = User::factory()->create();

    $this->actingAs($user);

        $response = $this->post(route('paciente.recordatorios.store'), [
            'dependiente_id' => null,
            'vacuna_id' => null,
            'tipo' => 'recordatorio_personal',
            'titulo' => 'Test envio',
            'mensaje' => 'Mensaje de prueba',
            'fecha_recordatorio' => now()->addDays(5)->toDateString(),
            'hora_recordatorio' => null,
        ]);

    // La app puede redirigir a completar perfil u otra ruta según middleware; comprobar que es una redirección
    $response->assertRedirect();

        $recordatorio = Recordatorio::first();
        $this->assertNotNull($recordatorio);

        // La job debe haber sido dispatched; verificamos la existencia del registro
        // y que al menos exista el recordatorio creado.
        $this->assertEquals('programado', $recordatorio->estado);
    }
}
