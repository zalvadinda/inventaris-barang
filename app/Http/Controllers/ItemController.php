
<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');

        $items = Item::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($category, function ($query, $category) {
                $query->where('category', $category);
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $categories = Item::query()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('items.index', compact(
            'items',
            'categories',
            'search',
            'category'
        ));
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        Item::create($validated);

        return redirect()
            ->route('items.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        $item->update($validated);

        return redirect()
            ->route('items.index')
            ->with('success', 'Data barang berhasil diperbarui.');
    }

    public function destroy(Item $item)
    {
        if ($item->loans()->exists()) {
            return redirect()
                ->route('items.index')
                ->with(
                    'error',
                    'Barang tidak dapat dihapus karena memiliki riwayat peminjaman.'
                );
        }

        $item->delete();

        return redirect()
            ->route('items.index')
            ->with('success', 'Barang berhasil dihapus.');
    }
}
