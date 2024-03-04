<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Employee;
use App\Models\EmployeeType;
use Tests\TestCase;
use App\Models\User;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    public function test_delete_user(): void
    {
        $user = User::all()->random();
        $response = $this->actingAs($user)->delete("/api/user");
        $response->dump();

        $response->assertStatus(200); 
        $this->assertDatabaseMissing('users', $user->only(['first_name', 'email']));
    }

}
