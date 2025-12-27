<?php

namespace App\Traits;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasRoles
{
    /**
     * Роли пользователя
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * Назначить роль пользователю
     */
    public function assignRole(Role|string|array $roles): self
    {
        $roles = is_array($roles) ? $roles : [$roles];

        foreach ($roles as $role) {
            if (is_string($role)) {
                $role = Role::where('name', $role)->firstOrFail();
            }

            $this->roles()->syncWithoutDetaching($role->id);
        }

        return $this;
    }

    /**
     * Удалить роль у пользователя
     */
    public function removeRole(Role|string|array $roles): self
    {
        $roles = is_array($roles) ? $roles : [$roles];

        foreach ($roles as $role) {
            if (is_string($role)) {
                $role = Role::where('name', $role)->firstOrFail();
            }

            $this->roles()->detach($role->id);
        }

        return $this;
    }

    /**
     * Синхронизировать роли пользователя
     */
    public function syncRoles(array $roles): self
    {
        $roleIds = collect($roles)->map(function ($role) {
            if (is_string($role)) {
                return Role::where('name', $role)->firstOrFail()->id;
            }
            return $role instanceof Role ? $role->id : $role;
        });

        $this->roles()->sync($roleIds);

        return $this;
    }

    /**
     * Проверка наличия роли у пользователя
     */
    public function hasRole(string|array $roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];

        return $this->roles()->whereIn('name', $roles)->exists();
    }

    /**
     * Проверка наличия любой из указанных ролей
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->hasRole($roles);
    }

    /**
     * Проверка наличия всех указанных ролей
     */
    public function hasAllRoles(array $roles): bool
    {
        foreach ($roles as $role) {
            if (!$this->hasRole($role)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Получить все права доступа пользователя через роли
     */
    public function permissions(): \Illuminate\Support\Collection
    {
        return $this->roles->flatMap(function ($role) {
            return $role->permissions;
        })->unique('id');
    }

    /**
     * Проверка наличия права доступа у пользователя
     */
    public function hasPermission(string|array $permissions): bool
    {
        $permissions = is_array($permissions) ? $permissions : [$permissions];

        $userPermissions = $this->permissions()->pluck('name')->toArray();

        foreach ($permissions as $permission) {
            if (in_array($permission, $userPermissions)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Проверка наличия всех указанных прав доступа
     */
    public function hasAllPermissions(array $permissions): bool
    {
        $userPermissions = $this->permissions()->pluck('name')->toArray();

        foreach ($permissions as $permission) {
            if (!in_array($permission, $userPermissions)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Проверка, является ли пользователь администратором
     * (имеет роль admin или is_admin = true)
     */
    public function isAdmin(): bool
    {
        return $this->is_admin === true || $this->hasRole('admin');
    }
}
