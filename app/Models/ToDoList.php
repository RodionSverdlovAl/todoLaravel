<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToDoList extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function getOwner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function toDoItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ToDoItem::class, 'list_id');
    }

    public function shareWithUser(User $user, string $permission = 'read'): void
    {
        $this->permissions()->syncWithoutDetaching([$user->id => ['permission' => $permission]]);
    }

    public function removeShareWithUser(User $user): void
    {
        $this->permissions()->detach($user->id);
    }

    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'list_permissions')->withPivot('permission');
    }

    public function getSharedUsersWithPermissions(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->permissions()->get();
    }

}
