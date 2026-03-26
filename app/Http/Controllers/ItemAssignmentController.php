<?php

namespace App\Http\Controllers;

use App\Item;
use App\ItemParentAssignment;
use App\SmParent;
use App\User;
use Illuminate\Http\Request;

class ItemAssignmentController extends Controller
{
    // List all items
    public function items()
    {
        $items = Item::all();
        return response()->json($items);
    }

    // Search parents by email, phone, or name
    public function searchParents(Request $request)
    {
        $query = $request->input('query');
        $parents = SmParent::where('fathers_name', 'like', "%$query%")
            ->orWhere('mothers_name', 'like', "%$query%")
            ->orWhere('guardians_name', 'like', "%$query%")
            ->orWhere('guardians_email', 'like', "%$query%")
            ->orWhere('guardians_mobile', 'like', "%$query%")
            ->get();
        return response()->json($parents);
    }

    // Parent-facing shop page
    public function shop()
    {
        $items = Item::all();
        return view('backEnd.parentPanel.items_shop', compact('items'));
    }

    // Show checkout summary (accepts POST from shop form)
    public function checkout(Request $request)
    {
        $input = $request->input('items', []); // array of ['id' => qty]

        $cart = [];
        $total = 0;

        foreach ($input as $id => $qty) {
            $item = Item::find($id);
            if (! $item) continue;
            $qty = intval($qty);
            if ($qty <= 0) continue;
            $line = [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price ?? 0,
                'qty' => $qty,
                'subtotal' => ($item->price ?? 0) * $qty,
            ];
            $total += $line['subtotal'];
            $cart[] = $line;
        }

        return view('backEnd.parentPanel.items_checkout', compact('cart', 'total'));
    }

    // Show checkout page pre-filled with demo items (GET)
    public function checkoutPage()
    {
        $cart = [];
        $total = 0;

        // Try to load first 4 items from items table as demo cart
        $items = Item::take(4)->get();
        if ($items->count() > 0) {
            foreach ($items as $item) {
                $qty = 1;
                $line = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'price' => $item->price ?? 0,
                    'qty' => $qty,
                    'subtotal' => ($item->price ?? 0) * $qty,
                ];
                $total += $line['subtotal'];
                $cart[] = $line;
            }
        } else {
            // Fallback static demo lines
            $fallback = [
                ['id' => 0, 'name' => 'Set of Text Books (Primary)', 'price' => 2500.00, 'qty' => 1],
                ['id' => 0, 'name' => 'School Uniform (Shirt + Bottom)', 'price' => 1800.00, 'qty' => 1],
                ['id' => 0, 'name' => 'Writing Materials Kit', 'price' => 350.00, 'qty' => 1],
                ['id' => 0, 'name' => 'Art & Craft Kit', 'price' => 450.00, 'qty' => 1],
            ];
            foreach ($fallback as $item) {
                $line = [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'qty' => $item['qty'],
                    'subtotal' => $item['price'] * $item['qty'],
                ];
                $total += $line['subtotal'];
                $cart[] = $line;
            }
        }

        return view('backEnd.parentPanel.items_checkout', compact('cart', 'total'));
    }

    // Process (fake) checkout — simply returns a success page with summary
    public function processCheckout(Request $request)
    {
        $name = $request->input('payer_name', 'Parent');
        $method = $request->input('payment_method', 'Cash on Delivery');
        $rawCart = $request->input('cart', []);

        // Normalize cart: each element may be JSON string or array
        $cart = [];
        foreach ($rawCart as $raw) {
            if (is_array($raw)) {
                $cart[] = $raw;
                continue;
            }
            $decoded = is_string($raw) ? json_decode(html_entity_decode($raw, ENT_QUOTES | ENT_HTML5, 'UTF-8'), true) : null;
            if (is_array($decoded)) {
                $cart[] = $decoded;
            }
        }

        return view('backEnd.parentPanel.items_checkout_success', compact('name', 'method', 'cart'));
    }

    // Assign items to parents (individually or bulk)
    public function assign(Request $request)
    {
        $itemIds = $request->input('item_ids');
        $parentIds = $request->input('parent_ids');
        $assignedBy = auth()->id();
        $assignedByRole = auth()->user()->usertype ?? 'admin';

        foreach ($parentIds as $parentId) {
            foreach ($itemIds as $itemId) {
                ItemParentAssignment::firstOrCreate([
                    'item_id' => $itemId,
                    'parent_id' => $parentId,
                ], [
                    'assigned_by' => $assignedBy,
                    'assigned_by_role' => $assignedByRole,
                ]);
            }
        }
        return response()->json(['status' => 'success']);
    }

    // List assigned items for a parent
    public function assignedItems($parentId)
    {
        $assignments = ItemParentAssignment::with('item')->where('parent_id', $parentId)->get();
        return response()->json($assignments);
    }
}
