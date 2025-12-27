<?php

namespace Tests\Unit;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_roles_relationship(): void
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'admin']);
        
        $user->roles()->attach($role);

        $this->assertTrue($user->roles->contains($role));
    }

    public function test_user_can_have_multiple_roles(): void
    {
        $user = User::factory()->create();
        $adminRole = Role::create(['name' => 'admin']);
        $editorRole = Role::create(['name' => 'editor']);
        
        $user->roles()->attach([$adminRole->id, $editorRole->id]);

        $this->assertEquals(2, $user->roles()->count());
        $this->assertTrue($user->roles->contains($adminRole));
        $this->assertTrue($user->roles->contains($editorRole));
    }

    public function test_deleting_user_removes_role_associations(): void
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'admin']);
        
        $user->roles()->attach($role);
        
        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);
        
        $user->delete();
        
        $this->assertDatabaseMissing('role_user', [
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);
    }
}
