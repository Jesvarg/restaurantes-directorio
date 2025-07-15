<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Test que la aplicación funciona siguiendo redirecciones.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->followingRedirects()->get('/');

        $response->assertStatus(200);
        $response->assertSee('Restaurantes'); // Verificar que contiene contenido esperado
    }
}