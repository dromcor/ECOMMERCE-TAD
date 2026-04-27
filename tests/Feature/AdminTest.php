<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_routes_protected()
    {
        $this->seed();

        $user = \App\Models\User::first();
        // ensure not admin: remove role_user
        \Illuminate\Support\Facades\DB::table('role_user')->where('usuario_id', $user->id)->delete();

        $this->actingAs($user);
        $this->get(route('admin.products.index'))->assertStatus(403);
    }
}
