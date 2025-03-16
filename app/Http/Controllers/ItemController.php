<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemCreateRequest;
use App\Http\Requests\ItemUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\Item;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Http\Resources\ItemResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showrelated()
    {
        $user = Auth::user();
        $items = Item::where('id_user', $user->id)->get();
        if (count($items) == 0) {
            return response()->json([
                "message" => "User tidak memiliki Item"
            ]);
        }
        return response()->json([
            "message" => "User memiliki" . count($items) . "Item",
            "data" => ItemResource::collection($items)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createitem(ItemCreateRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();
        $data['price'] = (int)$data['price'];
        $data['stock'] = (int)$data['stock'];
        $data['total_price'] = $data['price'] * $data['stock'];
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $user->id . '_' .  str_replace(' ','_',$request->item_name)  . '.' . $request->file('image')->extension();
            $imagePath = $request->file('image')->storeAs("images/items/$user->id", $imageName, 'public');
            $data['image_url'] = $imagePath;
        }
        $data['id_user'] = $user->id;
        $item = Item::create($data);

        return response()->json([
            'message' => 'Item created successfully',
            'data' => new ItemResource($item)
        ], 201);
    }
    public function showDetail(Request $request, Item $item)
    {
        $user = Auth::user();
        return response()->json([
            'message' => "Berikut Isi Item dengan ID " . $item->id,
            'data' => new ItemResource($item)
        ]);
    }
    public function updateItem(ItemUpdateRequest $request, Item $item)
    {
        $user = Auth::user();
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $user->id . '_' . $item->id . '.' . $request->file('image')->extension();
            $imagePath = $request->file('image')->storeAs("images/items/$user->id", $imageName, 'public');
            $data['image_url'] = $imagePath;
        }
        $data['price'] = (int)$data['price'];
        $data['stock'] = (int)$data['stock'];
        $data['total_price'] = $data['price'] * $data['stock'];
        $v = $item->update($data);
        return response()->json([
            'message' => 'Telah berhasil update',
            'data' => new ItemResource($item)
        ]);
    }
    public function deleteItem(Request $request, Item $item)
    {
        $user = Auth::user();
        if (!$item) {
            return response()->json([
                'message' => 'Item not found.'
            ], 404);
        }
        $item->delete();
        return response()->json([
            'message' => 'Item deleted successfully.'
        ], 200);
    }
}
