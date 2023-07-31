<?php

namespace App\Services;

use App\Models\ToDoList;
use Exception;

class ProfileService
{
    public function getCounts(): array
    {
        try {
            $data['total_lists'] = ToDoList::where('user_id', auth()->user()->id)->count();
            // Количество всех задач по всем вашим спискам
            $data['total_items'] = ToDoList::where('user_id', auth()->user()->id)->withCount('toDoItems')->get()->sum('to_do_items_count');
            // Количество доступных вам чужих списков
            $data['total_availableLists'] = ToDoList::whereHas('permissions', function ($query) {
                $query->where('user_id', auth()->user()->id);
            })->count();
            return $data;
        } catch (Exception $e) {
            // В случае возникновения ошибки, вернем пустой массив
            return [];
        }
    }

    public function getChartData(): array
    {
        try {
            $data = ToDoList::where('user_id', auth()->user()->id)
                ->withCount('toDoItems')
                ->get(['name', 'to_do_items_count'])
                ->toArray();

            $chartData = [['Task', 'Hours per Day']];
            foreach ($data as $item) {
                $chartData[] = [$item['name'], (int)$item['to_do_items_count']];
            }

            return $chartData;
        } catch (\Exception $e) {
            // В случае возникновения ошибки, вернем пустой массив
            return [];
        }
    }
}
