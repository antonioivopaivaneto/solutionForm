<?php

namespace Tests\Feature;

use App\Models\Condominio;
use App\Models\Unidade;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnidadeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_cria_unidades_sem_prefixo_no_banco()
    {
        // Arrange
        $condominio = Condominio::factory()->create();

        // Act
        $response = $this->post('/unidades', [
            'condominio_id' => $condominio->id,
            'unidades' => '01-04',
            'qtd_andares' => 2,
        ]);

        // Assert HTTP
        $response->assertRedirect();

        // Assert banco
        $this->assertDatabaseCount('unidades', 8);

        $this->assertDatabaseHas('unidades', [
            'nome' => 'UND.01',
            'andar' => 0,
            'condominio_id' => $condominio->id,
        ]);

        $this->assertDatabaseHas('unidades', [
            'nome' => 'UND.14',
            'andar' => 1,
            'condominio_id' => $condominio->id,
        ]);
    }
}
