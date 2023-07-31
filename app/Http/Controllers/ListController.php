<?php

namespace App\Http\Controllers;
use App\Http\Requests\List\StoreRequest;
use App\Http\Requests\List\UpdateRequest;
use App\Models\ToDoList;
use App\Models\User;
use App\Services\ListService;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use \Illuminate\Http\JsonResponse;
use \Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Support\Facades\Gate;

class ListController extends Controller
{
    private ListService $service;

    public function __construct(ListService $service)
    {
        $this->service = $service;
    }

    /**
     * Отображает список всех списков дел текущего пользователя.
     *
     * @return View HTML-код представления со списками дел.
     */
    public function index() : View
    {
        $lists = auth()->user()->toDoLists;
        foreach ($lists as $list) {
            $list->sharedUsers = $list->getSharedUsersWithPermissions();
        }
        return view('list.index', compact('lists'));
    }

    /**
     * Создает новый список дел и обновляет таблицу списков для отображения.
     *
     * @param StoreRequest $request Запрос с данными для создания списка.
     *
     * @return JsonResponse JSON-ответ с информацией о результате операции.
     *         Если список дел успешно создан, то возвращается статус HTTP 200 OK и обновленная таблица списков.
     *         Если произошла ошибка на стороне сервера, то возвращается статус HTTP 500 Internal Server Error
     *         и сообщение об ошибке.
     */
    public function create(StoreRequest $request) : JsonResponse
    {
        $listsTable = $this->service->updateListsCollection($request);
        if(!isset($listsTable)) {
            return response()->json([
                'errors' => 'Ошибка на стороне сервера',
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json([
            'message' => 'Список дел '. $request->input('name') .' успешно создан.',
            'listsTable' => $listsTable,
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Отображает список дел для указанного списка дел по статусу.
     *
     * @param ToDoList $list Список дел, для которого отображается список дел.
     * @return string HTML-код представления со списком дел для указанного списка.
     */
    public function show(ToDoList $list) : string
    {
        return $this->service->showListItemsByStatus($list);
    }

    /**
     * Удаление элемента списка дел.
     *
     * @param ToDoList $list Экземпляр модели ToDoList, представляющий элемент списка дел для удаления.
     * @return RedirectResponse Редирект назад на предыдущую страницу.
     */
    public function destroy(ToDoList $list): RedirectResponse
    {
        $resultOfDelete = $this->service->deleteList($list);
        if(!isset($resultOfDelete)){
           return redirect()->back()->with('error', 'Не удалось удалить элемент, ошибка на стороне сервера!');
        } else
        {
            return redirect()->back()->with('success', $resultOfDelete);
        }
    }

    /**
     * @param ToDoList $list
     * @return RedirectResponse|View
     */
    public function edit(ToDoList $list): RedirectResponse | View
    {
        $list->sharedUsers = $list->getSharedUsersWithPermissions();
        if (Gate::denies('update', $list)) {
            return redirect()->route('list.show', $list->id)->with('error', 'У вас нет прав на редактирование этого списка!');
        }
        $users = User::where('id', '!=', auth()->user()->id)->get();
        return view('list.edit', compact('list', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param ToDoList $list
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, ToDoList $list): RedirectResponse
    {
        $data = $request->validated();
        $resultOfUpdate = $this->service->editList($data, $list);
        if (!isset($resultOfUpdate)) {
            return redirect()->route('list.show', $list->id)->with('error-update', 'Не удалось изменить список, ошибка на стороне сервера!');
        } else {
            return redirect()->route('list.show', $list->id)->with('success-update', $resultOfUpdate);
        }
    }

    /**
     * Display a listing of available lists.
     *
     * @return \Illuminate\View\View
     */
    public function availableLists() : View
    {
        $availableLists = ToDoList::whereHas('permissions', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->get();
        return view('list.available', compact('availableLists'));
    }

    /**
     * Share the ToDoList with a user.
     *
     * @param ToDoList $list
     * @param Request $request
     * @return RedirectResponse
     */
    public function shareListWithUser(ToDoList $list, Request $request): RedirectResponse
    {
        $user = User::find($request->input('user_id'));
        $permission = $request->input('permission');
        $list->shareWithUser($user, $permission);
        return redirect()->route('list.show', $list->id);
    }

}
