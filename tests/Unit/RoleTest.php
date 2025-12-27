<?php

namespace Tests\Unit;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_can_be_created(): void
    {
        $role = Role::create([
            'name' => 'admin',
            'display_name' => 'Администратор',
            'description' => 'Полный доступ к системе',
        ]);

        $this->assertDatabaseHas('roles', [
            'name' => 'admin',
            'display_name' => 'Администратор',
        ]);
    }

    public function test_role_has_users_relationship(): void
    {
        $role = Role::create(['name' => 'admin']);
        $user = User::factory()->create();
        
        $role->users()->attach($user);

        $this->assertTrue($role->users->contains($user));
        $this->assertTrue($user->roles->contains($role));
    }

    public function test_role_has_permissions_relationship(): void
    {
        $role = Role::create(['name' => 'admin']);
        $permission = Permission::create(['name' => 'create_posts']);
        
        $role->permissions()->attach($permission);

        $this->assertTrue($role->permissions->contains($permission));
    }

    public function test_role_can_give_permission_by_name(): void
    {
        $role = Role::create(['name' => 'editor']);
        $permission = Permission::create(['name' => 'edit_posts']);
        
        $role->givePermissionTo('edit_posts');

        $this->assertTrue($role->hasPermission('edit_posts'));
    }

    public function test_role_can_give_permission_by_model(): void
    {
        $role = Role::create(['name' => 'editor']);
        $permission = Permission::create(['name' => 'edit_posts']);
        
        $role->givePermissionTo($permission);

        $this->assertTrue($role->hasPermission('edit_posts'));
    }

    public function test_role_can_revoke_permission_by_name(): void
    {
        $role = Role::create(['name' => 'editor']);
        $permission = Permission::create(['name' => 'edit_posts']);
        
        $role->givePermissionTo('edit_posts');
        $this->assertTrue($role->hasPermission('edit_posts'));
        
        $role->revokePermissionTo('edit_posts');
        $this->assertFalse($role->hasPermission('edit_posts'));
    }

    public function test_role_can_revoke_permission_by_model(): void
    {
        $role = Role::create(['name' => 'editor']);
        $permission = Permission::create(['name' => 'edit_posts']);
        
        $role->givePermissionTo($permission);
        $this->assertTrue($role->hasPermission('edit_posts'));
        
        $role->revokePermissionTo($permission);
        $this->assertFalse($role->hasPermission('edit_posts'));
    }

    public function test_role_can_check_permission(): void
    {
        $role = Role::create(['name' => 'editor']);
        $permission = Permission::create(['name' => 'edit_posts']);
        
        $this->assertFalse($role->hasPermission('edit_posts'));
        
        $role->givePermissionTo($permission);
        $this->assertTrue($role->hasPermission('edit_posts'));
    }

    public function test_give_permission_does_not_create_duplicates(): void
    {
        $role = Role::create(['name' => 'editor']);
        $permission = Permission::create(['name' => 'edit_posts']);
        
        $role->givePermissionTo($permission);
        $role->givePermissionTo($permission);

        $this->assertEquals(1, $role->permissions()->count());
    }

    public function test_deleting_role_removes_pivot_entries(): void
    {
        $role = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $permission = Permission::create(['name' => 'edit_posts']);
        
        $role->users()->attach($user);
        $role->permissions()->attach($permission);
        
        $this->assertDatabaseHas('role_user', [
            'role_id' => $role->id,
            'user_id' => $user->id,
        ]);
        
        $this->assertDatabaseHas('permission_role', [
            'role_id' => $role->id,
            'permission_id' => $permission->id,
        ]);
        
        $role->delete();
        
        $this->assertDatabaseMissing('role_user', [
            'role_id' => $role->id,
            'user_id' => $user->id,
        ]);
        
        $this->assertDatabaseMissing('permission_role', [
            'role_id' => $role->id,
            'permission_id' => $permission->id,
        ]);
    }
}
