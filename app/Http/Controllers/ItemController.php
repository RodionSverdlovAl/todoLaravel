<?php

namespace App\Http\Controllers;


use App\Http\Filters\ItemFilter;
use App\Http\Requests\Item\FilterRequest;
use App\Http\Requests\Item\UpdatePhotoRequest;
use App\Http\Requests\Item\UpdateRequest;
use App\Models\ToDoItem;
use App\Models\ToDoList;
use App\Services\ItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Item\StoreRequest;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use \Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public $service;

    public function __construct(ItemService $service)
    {
        $this->service = $service;
    }

    /**
     * Создает новую задачу и обновляет таблицу задач для отображения.
     *
     * @param StoreRequest $request Запрос с данными для создания задачи.
     *
     * @return JsonResponse JSON-ответ с информацией о результате операции.
     *         Если задача успешно создана, то возвращается статус HTTP 200 OK и обновленная таблица задач.
     *         Если произошла ошибка на стороне сервера, то возвращается статус HTTP 500 Internal Server Error
     *         и сообщение об ошибке.
     */

    public function create(StoreRequest $request) : JsonResponse
    {
        $itemsTable = $this->service->updateItemsCollection($request);
        if(!isset($itemsTable)){
            return response()->json([
                'errors' => 'Ошибка на стороне сервера',
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        } else {
            return response()->json([
                'message' => 'Задача успешно создана',
                'itemsTable' => $itemsTable
            ], ResponseAlias::HTTP_OK);
        }
    }

    /**
     * Помечает элемент списка дел как завершенный (completed).
     *
     * @param int $id Идентификатор элемента списка дел.
     * @return RedirectResponse Редирект обратно на предыдущую страницу.
     */
    public function complete($id): RedirectResponse
    {
        $item = ToDoItem::find($id);
        $item->status = 'completed';
        $item->save();
        return redirect()->back();
    }

    public function edit(ToDoItem $item):View
    {
        return view('item.edit', compact('item'));
    }

    /**
     * Update the specified ToDoItem.
     *
     * @param UpdateRequest $request
     * @param ToDoItem $item
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, ToDoItem $item): RedirectResponse
    {
        $data = $request->validated();
        $updateMassage = $this->service->updateItem($data, $item);
        if($updateMassage != null) {
            return redirect()->route('list.show', $item->list_id)->with('success-item-update', $updateMassage);
        } else {
            return redirect()->route('list.show', $item->list_id)->with('error-item-update', "Ошибка на стороне сервера");
        }
    }

    public function editPhoto(ToDoItem $item):View
    {
        return view('item.edit-photo', compact('item'));
    }

    /**
     * Update the photo of the specified ToDoItem.
     *
     * @param UpdatePhotoRequest $request
     * @param ToDoItem $item
     * @return RedirectResponse
     */
    public function updatePhoto(UpdatePhotoRequest $request, ToDoItem $item) : RedirectResponse
    {
        $data = $request->validated();
        $updateMassage =  $this->service->updateItemPhoto($data, $item);
         if($updateMassage != null) {
             return redirect()->route('list.show', $item->list_id)->with('success-item-photo-update', $updateMassage);
         } else {
             return redirect()->route('list.show', $item->list_id)->with('error-item-photo-update', "Ошибка на стороне сервера");
         }
    }

    public function addPhoto(ToDoItem $item) :View
    {
        return view('item.add-photo', compact('item'));
    }

    /**
     * Filter ToDoItems in the specified ToDoList by tags.
     *
     * @param Request $request
     * @param ToDoList $list
     * @return View
     */
    public function filterByTags(Request $request, ToDoList $list) : View
    {
        $tags = $request->input('tags');
        $items = $this->service->getFilteredItems($tags, $list);
        $itemsCompleted = $items['completed'];
        $itemsNotCompleted  = $items['not completed'];
        return view('list.show' , compact('list', 'itemsCompleted', 'itemsNotCompleted'));
    }

    /**
     * Search items in the given ToDoList.
     *
     * @param FilterRequest $request
     * @param ToDoList $list
     * @return View|RedirectResponse
     */
    public function searchItems(FilterRequest $request, ToDoList $list): View | RedirectResponse
    {
        $data = $request->validated();
        $items = $this->service->getSearchedItems($data);
        if($items == null){
          return redirect()->back()->with('search-error', 'Ошибка на стороне сервера');
        } else {
            $itemsCompleted = $items['completed'];
            $itemsNotCompleted  = $items['not completed'];
            return view('list.show' , compact('list', 'itemsCompleted', 'itemsNotCompleted'));
        }
    }

    /**
     * Delete the specified ToDoItem.
     *
     * @param ToDoItem $item
     * @return RedirectResponse
     */
    public function destroy(ToDoItem $item): RedirectResponse
    {
        $item->delete();
        return redirect()->back();
    }

}
