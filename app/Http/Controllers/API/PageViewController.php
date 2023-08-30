<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PageView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageViewController extends Controller
{
    public function create(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $data = $request->all();
            $objectPage = PageView::where('parent_menu_id', $data['parent_menu_id'])->where('id', $data['stand_behind']);
            if ($data['stand_behind'] === 0) {
                $data['order'] = $data['stand_behind'];
            } elseif ($data['stand_behind'] > 0) {
                if ($objectPage->count() === 0) {
                    $data['order'] = 0;
                } else {
                    $detailPage = $objectPage->first();
                    $data['order'] = ($detailPage->order + 0.5);
                }
            } elseif (is_null($data['stand_behind'])) {
                $data['order'] = PageView::where('parent_menu_id', $data['parent_menu_id'])->max('order') + 0.5;
            }
            unset($data['stand_behind']);
            PageView::create($data);

            $newGet = PageView::where('parent_menu_id', $data['parent_menu_id'])->orderBy('order', 'asc')->get();
            foreach ($newGet as $key => $value) {
                PageView::where('id', $value->id)->update(['order' => $key + 1]);
            }
            return response()->json([
                'status' => true,
                'data' => PageView::where('parent_menu_id', $data['parent_menu_id'])->orderBy('order', 'asc')->get()
            ]);
        });
    }
}
