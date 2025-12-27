<?php

namespace Tests\Unit;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_permission_can_be_created(): void
    {
        $permission = Permission::create([
            'name' => 'create_posts',
            'display_name' => 'Создание постов',
            'description' => 'Право на создание новых постов',
        ]);

        $this->assertDatabaseHas('permissions', [
            'name' => 'create_posts',
            'display_name' => 'Создание постов',
        ]);
    }

    public function test_permission_has_roles_relationship(): void
    {
        $permission = Permission::create(['name' => 'create_posts']);
        $role = Role::create(['name' => 'editor']);
        
        $permission->roles()->attach($role);

        $this->assertTrue($permission->roles->contains($role));
        $this->assertTrue($role->permissions->contains($permission));
    }

    public function test_deleting_permission_removes_pivot_entries(): void
    {
        $permission = Permission::create(['name' => 'create_posts']);
        $role = Role::create(['name' => 'editor']);
        
        $permission->roles()->attach($role);
        
        $this->assertDatabaseHas('permission_role', [
            'permission_id' => $permission->id,
            'role_id' => $role->id,
        ]);
        
        $permission->delete();
        
        $this->assertDatabaseMissing('permission_role', [
            'permission_id' => $permission->id,
            'role_id' => $role->id,
        ]);
    }
}
