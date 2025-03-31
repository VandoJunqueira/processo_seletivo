<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UnidadeTest extends TestCase
{
    # php artisan test --filter=UnidadeTest::test_store
    public function test_store(): void
    {
        $user = User::find(1);
        $response = $this->actingAs($user);

        $response = $this->post(route('unidade.store'), [
            'unid_nome' => 'Unidade de Teste',
            'unid_sigla' => 'UT'
        ]);

        dd($response->dump());

        $response->assertStatus(200);
    }
}
