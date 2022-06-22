<?php

namespace App\Http\Controllers\Vender;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $this->authorize('view-products');

        // fetch loggedin user products with media library
        $products = auth()->user()->products()->with('media')->get();

        return response()->json([
            'data' => ProductResource::collection($products),
            'status' => 'success',
            'message' => 'Product list fetched successfully',
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $this->authorize('create-products');
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:products',
            'unit_price' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);

        $data['currency'] = "USD";

        DB::beginTransaction();

        try {
            $product = auth()->user()->products()->create($data);

            $product->addMediaFromRequest('image')->toMediaCollection();

            $count = auth()->user()->products->count();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error occured while createing product',
            ], 500);
        }

        return response()->json([
            'data' => new ProductResource($product),
            'count' => $count,
            'status' => 'success',
            'message' => 'Product created successfully',
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $this->authorize('view-products');

        $product = auth()->user()->products()->find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found',
            ], 404);
        }

        return response()->json([
            'data' => new ProductResource($product),
            'status' => 'success',
            'message' => 'Product fetched successfully',
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $this->authorize('edit-products');
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'unit_price' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            $product->update($data);

            if ($request->update_image != null) {
                $product->clearMediaCollection();
                $product->addMediaFromRequest('update_image')->toMediaCollection();
            }

            $count = auth()->user()->products->count();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error occured while updating product',
            ], 500);
        }

        return response()->json([
            'data' => new ProductResource($product),
            'count' => $count,
            'status' => 'success',
            'message' => 'Product updated successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $this->authorize('delete-products');

        $product = Product::findOrFail($id);

        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully',
        ], 200);
    }

    public function getProduct($id)
    {
        $product = Product::findOrFail($id);

        return response()->json([
            'data' => new ProductResource($product),
            'status' => 'success',
            'message' => 'Product fetched successfully',
        ], 200);
    }
}
