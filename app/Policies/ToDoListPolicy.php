<?php

namespace App\Policies;

use App\Models\ToDoList;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ToDoListPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function viewAny(User $user): Response|bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param ToDoList $list
     * @return bool
     */
    public function view(User $user, ToDoList $list): bool
    {
        // Проверяем, имеет ли текущий пользователь право на просмотр списка
        return $list->permissions()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function create(User $user): Response|bool
    {
        return true;
    }

    public function createItem(User $user, ToDoList $list): bool
    {
        // Проверяем, имеет ли текущий пользователь право добавлять пункты списка
        $hasPermission = $list->permissions()
            ->where('user_id', $user->id)
            ->whereIn('permission', ['edit', 'create'])
            ->exists();

        // Проверяем, является ли текущий пользователь создателем списка
        $isOwner = $list->getOwner->id === $user->id;

        // Возвращаем true, если у пользователя есть право или он является создателем списка
        return $hasPermission || $isOwner;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param ToDoList $list
     * @return bool
     */

    public function update(User $user, ToDoList $list): bool
    {
        // Проверяем, является ли пользователь создателем списка
        if ($user->id === $list->user_id) {
            return true;
        }

        // Проверяем, имеет ли текущий пользователь право на редактирование списка
        return $list->permissions()->where('user_id', $user->id)->where('permission', 'edit')->exists();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param ToDoList $toDoList
     * @return int
     */
    public function delete(User $user, ToDoList $toDoList): int
    {
        return 0;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param ToDoList $toDoList
     * @return int
     */
    public function restore(User $user, ToDoList $toDoList): int
    {
        return 0;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param ToDoList $toDoList
     * @return int
     */
    public function forceDelete(User $user, ToDoList $toDoList): int
    {
        return 0;
    }

    public function grantAccess(User $user, ToDoList $list): bool
    {
        // Проверяем, является ли пользователь создателем списка
        return $user->id === $list->user_id;
    }
}
