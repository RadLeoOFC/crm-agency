<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Contracts\Permission as PermissionContract;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class Permission extends Model implements PermissionContract
{
    use HasFactory;

    protected $fillable = [
        'name',
        'guard_name'
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.role'),
            config('permission.table_names.role_has_permissions')
        );
    }

    public static function findByName(string $name, $guardName = null): PermissionContract
    {
        $guardName = $guardName ?? config('auth.defaults.guard');
        $permission = static::where('name', $name)->where('guard_name', $guardName)->first();
        
        if (! $permission) {
            throw new PermissionDoesNotExist("Permission `{$name}` not found for guard `{$guardName}`.");
        }

        return $permission;
    }

    public static function findById(string|int $id, ?string $guardName = null): PermissionContract
    {
        $guardName = $guardName ?? config('auth.defaults.guard');
        $permission = static::where('id', $id)->where('guard_name', $guardName)->first();

        if (! $permission) {
            throw new PermissionDoesNotExist("Permission with ID `{$id}` not found for guard `{$guardName}`.");
        }

        return $permission;
    }

    public static function findOrCreate(string $name, $guardName = null): PermissionContract
    {
        $guardName = $guardName ?? config('auth.defaults.guard');
        $permission = static::where('name', $name)->where('guard_name', $guardName)->first();

        if (! $permission) {
            return static::create(['name' => $name, 'guard_name' => $guardName]);
        }

        return $permission;
    }
}
