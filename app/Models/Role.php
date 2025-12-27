<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Модель роли
 * 
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * 
 * @author ehosting.lv
 */
class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Права доступа роли
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }

    /**
     * Пользователи с этой ролью
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user');
    }
}
