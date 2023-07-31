<?php

namespace App\Services;

use App\Http\Requests\List\StoreRequest;
use App\Models\ToDoList;
use App\Models\User;
use Illuminate\Database\QueryException;

class ListService
{
    /**
     * Обновляет список дел и возвращает HTML-код новой таблицы списков.
     *
     * @param StoreRequest $request Запрос с данными для создания нового списка дел.
     * @return string|null HTML-код новой таблицы списков или null, если произошла ошибка.
     */
    public function updateListsCollection(StoreRequest $request) : ?string
    {
        try {
            ToDoList::create([
                'name' => $request->input('name'),
                'user_id' => $request->input('user_id'),
            ]);
            $lists = User::find($request->input('user_id'))->toDoLists;
            foreach ($lists as $list) {
                $list->sharedUsers = $list->getSharedUsersWithPermissions();
            }
            return view('list.lists_table', compact('lists'))->render();
        } catch (QueryException $e) {
            // место для лога
            return null;
        }

    }

    /**
     * Отображает список элементов заданного списка по их статусу.
     *
     * @param ToDoList $list Список дел, для которого нужно отобразить элементы.
     * @return string HTML-код представления "list.show" с переданными данными о списках и их элементах.
     */
    public function showListItemsByStatus(ToDoList $list) : string
    {
        $itemsCompleted = $list->toDoItems()->where('status', 'completed')->get();
        $itemsNotCompleted = $list->toDoItems()->where('status', 'not completed')->get();
        return view('list.show' , compact('list', 'itemsCompleted', 'itemsNotCompleted'));
    }

    /**
     * Удаление списка дел и всех его элементов.
     *
     * @param ToDoList $list Экземпляр модели ToDoList, представляющий список дел для удаления.
     * @return string|null Сообщение об успешном удалении списка или null в случае ошибки.
     */
    public function deleteList(ToDoList $list) : ?string
    {
        try {
            //throw new QueryException('Это искусственная ошибка!', [], new \Exception());
            $list->toDoItems()->forceDelete();
            $list->delete();
            return 'Список '. $list->name . ' успешно удален!';
        } catch (QueryException $e) {
            // место для лога
            return null;
        }
    }

    public function editList(array $data, ToDoList $list): ?string
    {
        try {
            //throw new QueryException('Это искусственная ошибка!', [], new \Exception());
            $oldListName = $list->name;
            $list->update($data);
            return 'Список '. $oldListName . ' успешно отредактирован!';
        } catch (QueryException $e) {
            // место для лога
            return null;
        }
    }
}
