<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Batch;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles using the existing seeder or manual creation
        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'Student']);
    }

    public function test_the_application_redirects_to_login(): void
    {
        $response = $this->get('/');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_can_view_login_page(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Login');
    }

    public function test_admin_can_access_dashboard(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin_test@test.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        
        $response->assertStatus(200);
        $response->assertSee('Total Students');
    }

    public function test_student_can_access_dashboard(): void
    {
        $studentRole = Role::where('name', 'Student')->first();
        $batch = Batch::create(['name' => 'Test Batch', 'token' => 'xyz123']);
        
        $student = User::create([
            'name' => 'Student User',
            'email' => 'student_test@test.com',
            'password' => Hash::make('password'),
            'role_id' => $studentRole->id,
            'batch_id' => $batch->id,
        ]);

        $response = $this->actingAs($student)->get('/student/dashboard');
        
        $response->assertStatus(200);
        $response->assertSee('Course Videos');
    }

    public function test_admin_can_create_batch(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();
        $admin = User::create([
            'name' => 'Admin User 2',
            'email' => 'admin_test2@test.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
        ]);

        $response = $this->actingAs($admin)->post('/admin/batch', [
            'name' => 'New Test Batch',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('batches', ['name' => 'New Test Batch']);
    }

    public function test_student_cannot_access_admin_dashboard(): void
    {
        $studentRole = Role::where('name', 'Student')->first();
        $student = User::create([
            'name' => 'Student 2',
            'email' => 'student_test2@test.com',
            'password' => Hash::make('password'),
            'role_id' => $studentRole->id,
        ]);

        $response = $this->actingAs($student)->get('/admin/dashboard');
        
        $response->assertStatus(403);
    }
}
