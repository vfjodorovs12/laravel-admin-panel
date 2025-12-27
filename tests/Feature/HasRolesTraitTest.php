<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HasRolesTraitTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Role $adminRole;
    protected Role $editorRole;
    protected Permission $createPermission;
    protected Permission $editPermission;

    protected function setUp(): void
    {
        parent::setUp();

        // Создаем тестового пользователя
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'is_admin' => false,
        ]);

        // Создаем роли
        $this->adminRole = Role::create([
            'name' => 'admin',
            'description' => 'Administrator role',
        ]);

        $this->editorRole = Role::create([
            'name' => 'editor',
            'description' => 'Editor role',
        ]);

        // Создаем права доступа
        $this->createPermission = Permission::create([
            'name' => 'create-posts',
            'description' => 'Can create posts',
        ]);

        $this->editPermission = Permission::create([
            'name' => 'edit-posts',
            'description' => 'Can edit posts',
        ]);

        // Назначаем права доступа ролям
        $this->adminRole->permissions()->attach($this->createPermission);
        $this->adminRole->permissions()->attach($this->editPermission);
        $this->editorRole->permissions()->attach($this->editPermission);
    }

    public function test_user_can_have_roles_relation(): void
    {
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class, $this->user->roles());
    }

    public function test_user_can_assign_role_by_name(): void
    {
        $this->user->assignRole('admin');
        
        $this->assertTrue($this->user->hasRole('admin'));
        $this->assertCount(1, $this->user->roles);
    }

    public function test_user_can_assign_role_by_model(): void
    {
        $this->user->assignRole($this->adminRole);
        
        $this->assertTrue($this->user->hasRole('admin'));
    }

    public function test_user_can_assign_multiple_roles(): void
    {
        $this->user->assignRole(['admin', 'editor']);
        
        $this->assertTrue($this->user->hasRole('admin'));
        $this->assertTrue($this->user->hasRole('editor'));
        $this->assertCount(2, $this->user->fresh()->roles);
    }

    public function test_assign_role_returns_self(): void
    {
        $result = $this->user->assignRole('admin');
        
        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($this->user->id, $result->id);
    }

    public function test_user_can_remove_role_by_name(): void
    {
        $this->user->assignRole('admin');
        $this->user->removeRole('admin');
        
        $this->assertFalse($this->user->hasRole('admin'));
    }

    public function test_user_can_remove_role_by_model(): void
    {
        $this->user->assignRole($this->adminRole);
        $this->user->removeRole($this->adminRole);
        
        $this->assertFalse($this->user->hasRole('admin'));
    }

    public function test_user_can_remove_multiple_roles(): void
    {
        $this->user->assignRole(['admin', 'editor']);
        $this->user->removeRole(['admin', 'editor']);
        
        $this->assertFalse($this->user->hasRole('admin'));
        $this->assertFalse($this->user->hasRole('editor'));
    }

    public function test_remove_role_returns_self(): void
    {
        $this->user->assignRole('admin');
        $result = $this->user->removeRole('admin');
        
        $this->assertInstanceOf(User::class, $result);
    }

    public function test_user_can_sync_roles(): void
    {
        $this->user->assignRole(['admin', 'editor']);
        $this->user->syncRoles(['editor']);
        
        $this->assertFalse($this->user->fresh()->hasRole('admin'));
        $this->assertTrue($this->user->fresh()->hasRole('editor'));
        $this->assertCount(1, $this->user->fresh()->roles);
    }

    public function test_sync_roles_with_model_instances(): void
    {
        $this->user->syncRoles([$this->adminRole, $this->editorRole]);
        
        $this->assertTrue($this->user->fresh()->hasRole('admin'));
        $this->assertTrue($this->user->fresh()->hasRole('editor'));
    }

    public function test_sync_roles_returns_self(): void
    {
        $result = $this->user->syncRoles(['admin']);
        
        $this->assertInstanceOf(User::class, $result);
    }

    public function test_has_role_with_single_role(): void
    {
        $this->user->assignRole('admin');
        
        $this->assertTrue($this->user->hasRole('admin'));
        $this->assertFalse($this->user->hasRole('editor'));
    }

    public function test_has_role_with_array(): void
    {
        $this->user->assignRole('admin');
        
        $this->assertTrue($this->user->hasRole(['admin', 'editor']));
    }

    public function test_has_any_role(): void
    {
        $this->user->assignRole('editor');
        
        $this->assertTrue($this->user->hasAnyRole(['admin', 'editor']));
        $this->assertFalse($this->user->hasAnyRole(['admin', 'moderator']));
    }

    public function test_has_all_roles(): void
    {
        $this->user->assignRole(['admin', 'editor']);
        
        $this->assertTrue($this->user->hasAllRoles(['admin', 'editor']));
        $this->assertFalse($this->user->hasAllRoles(['admin', 'editor', 'moderator']));
    }

    public function test_user_can_get_permissions_through_roles(): void
    {
        $this->user->assignRole('admin');
        
        $permissions = $this->user->permissions();
        
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $permissions);
        $this->assertCount(2, $permissions);
        $this->assertTrue($permissions->contains('name', 'create-posts'));
        $this->assertTrue($permissions->contains('name', 'edit-posts'));
    }

    public function test_permissions_are_unique_across_roles(): void
    {
        $this->user->assignRole(['admin', 'editor']);
        
        $permissions = $this->user->permissions();
        
        // Оба роли имеют 'edit-posts', но должна быть только одна запись
        $this->assertCount(2, $permissions);
    }

    public function test_has_permission_with_single_permission(): void
    {
        $this->user->assignRole('admin');
        
        $this->assertTrue($this->user->hasPermission('create-posts'));
        $this->assertTrue($this->user->hasPermission('edit-posts'));
    }

    public function test_has_permission_with_array(): void
    {
        $this->user->assignRole('admin');
        
        $this->assertTrue($this->user->hasPermission(['create-posts', 'edit-posts']));
        $this->assertTrue($this->user->hasPermission(['create-posts', 'non-existent']));
    }

    public function test_has_permission_returns_false_when_no_permission(): void
    {
        $this->user->assignRole('editor');
        
        $this->assertFalse($this->user->hasPermission('create-posts'));
    }

    public function test_has_all_permissions(): void
    {
        $this->user->assignRole('admin');
        
        $this->assertTrue($this->user->hasAllPermissions(['create-posts', 'edit-posts']));
        $this->assertFalse($this->user->hasAllPermissions(['create-posts', 'delete-posts']));
    }

    public function test_is_admin_returns_true_when_is_admin_field_is_true(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);
        
        $this->assertTrue($admin->isAdmin());
    }

    public function test_is_admin_returns_true_when_has_admin_role(): void
    {
        $this->user->assignRole('admin');
        
        $this->assertTrue($this->user->fresh()->isAdmin());
    }

    public function test_is_admin_returns_false_when_neither_condition_met(): void
    {
        $this->assertFalse($this->user->isAdmin());
    }

    public function test_assign_role_does_not_duplicate_roles(): void
    {
        $this->user->assignRole('admin');
        $this->user->assignRole('admin');
        
        $this->assertCount(1, $this->user->fresh()->roles);
    }
}
