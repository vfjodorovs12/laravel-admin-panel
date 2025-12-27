<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Модель пользователя
 * 
 * Расширенная модель пользователя с поддержкой ролей администратора
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property bool $is_admin
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * 
 * @author ehosting.lv
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }
    
    /**
     * Проверка, является ли пользователь администратором
     * 
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }
    
    /**
     * Scope для выборки только администраторов
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdmins($query)
    {
        return $query->where('is_admin', true);
    }
    
    /**
     * Scope для выборки только обычных пользователей
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRegularUsers($query)
    {
        return $query->where('is_admin', false);
    }
    
    /**
     * Проверка наличия роли у пользователя
     * 
     * @param string|array $roles Роль или массив ролей для проверки
     * @return bool
     */
    public function hasRole(string|array $roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];
        
        // Базовая реализация: проверяем роль 'admin' через is_admin
        foreach ($roles as $role) {
            if ($role === 'admin' && $this->is_admin) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Проверка наличия хотя бы одной из ролей
     * 
     * @param array $roles Массив ролей для проверки
     * @return bool
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->hasRole($roles);
    }
    
    /**
     * Проверка наличия всех указанных ролей
     * 
     * @param array $roles Массив ролей для проверки
     * @return bool
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
     * Проверка наличия прав доступа у пользователя
     * 
     * @param string|array $permissions Право или массив прав для проверки
     * @return bool
     */
    public function hasPermission(string|array $permissions): bool
    {
        $permissions = is_array($permissions) ? $permissions : [$permissions];
        
        // Базовая реализация: администраторы имеют все права
        if ($this->is_admin) {
            return true;
        }
        
        return false;
    }
}
