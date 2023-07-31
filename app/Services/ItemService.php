<?php

namespace App\Services;
use App\Http\Filters\ItemFilter;
use App\Models\Tag;
use App\Models\ToDoItem;
use \App\Http\Requests\Item\StoreRequest;
use App\Models\ToDoList;
use Exception;
use Illuminate\Database\QueryException;

class ItemService
{
    /**
     * Обновляет коллекцию элементов списка дел.
     *
     * @param StoreRequest $request Запрос с валидированными данными.
     * @return string|null Строка с обновленной таблицей элементов списка дел или null в случае ошибки.
     */
    public function updateItemsCollection(StoreRequest $request) : ?string
    {
        $data = $request->validated();
        $photoPath = $this->loadPhoto($data);
        $status = $request->input('status');
        try{
            $toDoItem = ToDoItem::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'status' => $status,
                'photo_path' => $photoPath,
                'list_id' => $data['list_id'],
            ]);
            $tags = array_map('trim', explode(' ', $request->input('tags'))); // Разделяем строку по запятой
            foreach ($tags as $tagName) {
                $tag = Tag::firstOrCreate(['name' => $tagName]);
                $toDoItem->tags()->attach($tag);
            }
            return $this->getUpdatedListItemsByStatus($data['list_id']);
        } catch (QueryException $e) {
            return null;
        }
    }


    /**
     * Загружает фотографию и возвращает путь к ней.
     *
     * @param array $data Массив данных с загруженной фотографией.
     * @return string|null Путь к загруженной фотографии или null, если фотография не была предоставлена.
     */
    private function loadPhoto(array $data) : ?string
    {
        $photo = $data['photo'] ?? null;// Получите загруженный файл фотографии (если он был предоставлен)
        $photoName = $photo ? $photo->getClientOriginalName() : null; // Генерируйте уникальное имя файла
        $photoPath = null;
        if ($photo) {
            $photo->move(public_path('photos'), $photoName);
            $photoPath = 'photos/' . $photoName;
        }
        return $photoPath;
    }

    /**
     * Получает обновленные элементы списка по их статусу.
     *
     * @param mixed $list_id Идентификатор списка.
     * @return string HTML-код новой обновленной таблицы списка.
     */
    private function getUpdatedListItemsByStatus(mixed $list_id) : string
    {
        $itemsCompleted =  ToDoList::find($list_id)->toDoItems()->where('status', 'completed')->get();
        $itemsNotCompleted = ToDoList::find($list_id)->toDoItems()->where('status', 'not completed')->get();
        return view('list.lists_items_table', compact('itemsCompleted', 'itemsNotCompleted'))->render(); // рендерим новую обновленную таблицу таблицу
    }

    public function updateItem(array $data, ToDoItem $item): ?string
    {
        try {
            $item->update($data);
            return "Задача ". $item->title . " успешно изменена!";
        } catch(QueryException $e) {
            // место для лога
            return null;
        }
    }

    public function updateItemPhoto(array $data, ToDoItem $item): ?string
    {
        $photoPath = $this->loadPhoto($data);
        try {
            $item->update([ 'photo_path' => $photoPath,]);
            return "Фотография для задачи ". $item->title . " успешно добавленна/изменена!";
        } catch(QueryException $e) {
            // место для лога
            return null;
        }
    }

    /**
     * Get filtered ToDoItems based on tags for the given ToDoList.
     *
     * @param mixed $tags
     * @param ToDoList $list
     * @return array
     */
    public function getFilteredItems(mixed $tags, ToDoList $list): array
    {
        if (!empty($tags)) {
            $tagsArray = explode(' ', $tags);
            $items['completed'] =  ToDoList::find($list->id)->toDoItems()
                ->where('status', 'completed')
                ->whereHas('tags', function ($query) use ($tagsArray) {
                    $query->whereIn('name', $tagsArray);
                })
                ->get();
            $items['not completed'] =  ToDoList::find($list->id)->toDoItems()
                ->where('status', 'not completed')
                ->whereHas('tags', function ($query) use ($tagsArray) {
                    $query->whereIn('name', $tagsArray);
                })
                ->get();
        } else {
            $items['completed'] =  ToDoList::find($list->id)->toDoItems()->where('status', 'completed')->get();
            $items['not completed'] = ToDoList::find($list->id)->toDoItems()->where('status', 'not completed')->get();
        }
        return $items;
    }

    /**
     * Get searched ToDoItems based on the provided data.
     *
     * @param array $data
     * @return array|null
     */
    public function getSearchedItems(array $data): ?array
    {
        try{
            $filter = app()->make(ItemFilter::class, ['queryParams' => array_filter($data)]);
            $items['completed'] = ToDoItem::filter($filter)->where('status', 'completed')->get();
            $items['not completed'] = ToDoItem::filter($filter)->where('status', 'not completed')->get();
            return $items;
        } catch (Exception $e)
        {
            return null;
        }
    }

}
