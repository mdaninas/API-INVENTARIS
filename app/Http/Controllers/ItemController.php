<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemCreateRequest;
use App\Http\Resources\UserResource;
use App\Models\Item;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Http\Resources\ItemResource;
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
        $data['total_price'] = $data['price'] * $data['stock'];
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store("images/items/$user->id", 'public');
            $data['image_url'] = $imagePath;
        }
        $data['id_user'] = $user->id;
        $item = Item::create($data);

        return response()->json([
            'message' => 'Item created successfully',
            'data' => new ItemResource($item)
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        //
    }
}
