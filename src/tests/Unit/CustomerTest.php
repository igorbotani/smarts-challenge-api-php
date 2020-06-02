<?php

namespace Tests\Unit;

use Tests\TestCase;

class CustomerTest extends TestCase {

    /**
     * Teste básico para verificar se o JSON está sendo carregado corretamente
     */
    public function testGet() {
        $response = $this->json('GET', '/api/Customers', [
            'page' => '1',
            'limit' => '3',
            ]);

        $response
            ->assertStatus(200)
            ->assertJsonCount(3, ['customers'])
            ->assertSeeText('5e74f6d7ca97659df8d8b301');
    }
}
