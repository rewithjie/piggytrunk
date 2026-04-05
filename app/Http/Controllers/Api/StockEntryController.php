<?php

namespace App\Http\Controllers\Api;

use App\Models\RetailProduct;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StockEntryController
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer',
            'movement_type' => 'required|in:add,deduct,distribute',
            'quantity' => 'required|integer|min:1',
            'raiser_id' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);

        try {
            $product = RetailProduct::find($request->product_id);
            
            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            $quantity = $request->integer('quantity');
            $notes = $request->string('notes')->value() ?: null;

            match($request->string('movement_type')->value()) {
                'add' => $product->addStock($quantity, $notes),
                'deduct' => $product->deductStock($quantity, $notes),
                'distribute' => $product->distributeToRaiser(
                    $quantity,
                    $request->integer('raiser_id'),
                    $notes
                ),
            };

            return response()->json([
                'success' => true,
                'message' => 'Stock entry recorded successfully',
                'stock' => $product->stock,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error recording stock entry: ' . $e->getMessage(),
            ], 500);
        }
    }
}
