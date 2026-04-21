<?php

namespace App\Http\Controllers;

use App\Models\QuickSaleSession;
use App\Models\QuickSaleItem;
use App\Models\RetailProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuickSaleController extends Controller
{
    /**
     * Get or create the current quick sale session
     */
    public function getSession(): JsonResponse
    {
        $sessionKey = session('quick_sale_session_key');
        
        if (!$sessionKey) {
            $sessionKey = Str::uuid()->toString();
            session(['quick_sale_session_key' => $sessionKey]);
        }

        $quickSale = QuickSaleSession::firstOrCreate(
            ['session_key' => $sessionKey],
            [
                'status' => 'draft',
                'created_by' => auth()->id(),
            ]
        );

        return response()->json([
            'session' => $quickSale,
            'items' => $quickSale->items()->with('product')->get(),
        ]);
    }

    /**
     * Add product to quick sale cart
     */
    public function addItem(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|integer|exists:retail_products,id',
                'quantity' => 'required|integer|min:1',
                'discount_amount' => 'nullable|numeric|min:0',
            ]);

            $sessionKey = session('quick_sale_session_key');
            if (!$sessionKey) {
                $sessionKey = Str::uuid()->toString();
                session(['quick_sale_session_key' => $sessionKey]);
            }

            $quickSale = QuickSaleSession::firstOrCreate(
                ['session_key' => $sessionKey],
                [
                    'status' => 'draft',
                    'created_by' => auth()->id(),
                ]
            );

            $product = RetailProduct::findOrFail($validated['product_id']);

            // Check if item already exists
            $existingItem = $quickSale->items()
                ->where('retail_product_id', $product->id)
                ->first();

            if ($existingItem) {
                // Update quantity
                $existingItem->updateQuantity($existingItem->quantity + $validated['quantity']);
            } else {
                // Create new item
                $totalPrice = $product->price * $validated['quantity'];
                $discountAmount = $validated['discount_amount'] ?? 0;
                $netPrice = $totalPrice - $discountAmount;

                QuickSaleItem::create([
                    'quick_sale_session_id' => $quickSale->id,
                    'retail_product_id' => $product->id,
                    'quantity' => $validated['quantity'],
                    'unit_price' => $product->price,
                    'total_price' => $totalPrice,
                    'discount_amount' => $discountAmount,
                    'net_price' => $netPrice,
                ]);
            }

            $quickSale->calculateTotals();

            return response()->json([
                'success' => true,
                'message' => "{$product->name} added to quick sale",
                'session' => $quickSale,
                'items' => $quickSale->items()->with('product')->get(),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed: ' . collect($e->errors())->flatten()->first(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update quick sale item quantity
     */
    public function updateItem(Request $request, int $itemId): JsonResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item = QuickSaleItem::findOrFail($itemId);
        $item->updateQuantity($validated['quantity']);
        $session = $item->session;

        return response()->json([
            'success' => true,
            'message' => 'Item quantity updated',
            'items' => $session?->items()->with('product')->get() ?? [],
            'session' => $session,
        ]);
    }

    /**
     * Remove item from quick sale cart
     */
    public function removeItem(int $itemId): JsonResponse
    {
        $item = QuickSaleItem::findOrFail($itemId);
        $session = $item->session;
        if (!$session) {
            $item->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item removed from quick sale',
                'items' => [],
                'session' => null,
            ]);
        }
        
        $item->delete();
        $session->calculateTotals();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from quick sale',
            'items' => $session->items()->with('product')->get(),
            'session' => $session,
        ]);
    }

    /**
     * Update discount for item
     */
    public function updateDiscount(Request $request, int $itemId): JsonResponse
    {
        $validated = $request->validate([
            'discount_amount' => 'required|numeric|min:0',
        ]);

        $item = QuickSaleItem::findOrFail($itemId);
        $item->updateDiscount($validated['discount_amount']);
        $session = $item->session;

        return response()->json([
            'success' => true,
            'message' => 'Discount updated',
            'items' => $session?->items()->with('product')->get() ?? [],
            'session' => $session,
        ]);
    }

    /**
     * Update session customer info
     */
    public function updateSession(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'raiser_id' => 'nullable|exists:raisers,id',
            'payment_method' => 'nullable|in:cash,check,card,online',
            'remarks' => 'nullable|string',
        ]);

        $sessionKey = session('quick_sale_session_key');
        if (!$sessionKey) {
            return response()->json(['error' => 'No active session'], 400);
        }

        $quickSale = QuickSaleSession::where('session_key', $sessionKey)->firstOrFail();
        $quickSale->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Session updated',
            'session' => $quickSale,
        ]);
    }

    /**
     * Confirm and process quick sale
     */
    public function confirm(Request $request): JsonResponse
    {
        $sessionKey = session('quick_sale_session_key');
        if (!$sessionKey) {
            return response()->json(['error' => 'No active session'], 400);
        }

        $quickSale = QuickSaleSession::where('session_key', $sessionKey)->firstOrFail();

        if ($quickSale->items()->count() === 0) {
            return response()->json(['error' => 'No items in quick sale'], 400);
        }

        try {
            $quickSale->confirm();
            session()->forget('quick_sale_session_key');

            return response()->json([
                'success' => true,
                'message' => 'Quick sale confirmed and transactions created',
                'session' => $quickSale,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Cancel quick sale
     */
    public function cancel(): JsonResponse
    {
        $sessionKey = session('quick_sale_session_key');
        if (!$sessionKey) {
            return response()->json(['error' => 'No active session'], 400);
        }

        $quickSale = QuickSaleSession::where('session_key', $sessionKey)->firstOrFail();
        $quickSale->cancel();
        session()->forget('quick_sale_session_key');

        return response()->json([
            'success' => true,
            'message' => 'Quick sale cancelled',
        ]);
    }

    /**
     * Clear current session
     */
    public function clear(): JsonResponse
    {
        $sessionKey = session('quick_sale_session_key');
        if ($sessionKey) {
            $quickSale = QuickSaleSession::where('session_key', $sessionKey)->first();
            if ($quickSale && $quickSale->status === 'draft') {
                $quickSale->items()->delete();
                $quickSale->delete();
            }
        }
        session()->forget('quick_sale_session_key');

        return response()->json([
            'success' => true,
            'message' => 'Quick sale cleared',
        ]);
    }
}
