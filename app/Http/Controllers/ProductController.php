<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = DB::select('SELECT * FROM products ORDER BY created_at DESC');
            return response()->json(['status' => 200, 'data' => $products], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 100, 'message' => 'Error al listar productos'], 200);
        }
    }

    public function show($id)
    {
        try {
            $products = DB::select('SELECT * FROM products WHERE id = ?', [$id]);
            if (empty($products)) {
                return response()->json(['status' => 100, 'message' => 'Producto no encontrado'], 200);
            }
            return response()->json(['status' => 200, 'data' => $products[0]], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 100, 'message' => 'Error al buscar producto'], 200);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'price' => 'required|numeric',
                'description' => 'required|string'
            ]);

            DB::insert('INSERT INTO products (name, price, description, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())', [
                $request->name,
                $request->price,
                $request->description
            ]);

            $product = DB::select('SELECT * FROM products ORDER BY id DESC LIMIT 1')[0];
            return response()->json(['status' => 200, 'data' => $product], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 100, 'message' => 'Error al crear producto'], 200);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'price' => 'required|numeric',
                'description' => 'required|string'
            ]);

            $products = DB::select('SELECT * FROM products WHERE id = ?', [$id]);
            if (empty($products)) {
                return response()->json(['status' => 100, 'message' => 'Producto no encontrado'], 200);
            }

            DB::update('UPDATE products SET name = ?, price = ?, description = ?, updated_at = NOW() WHERE id = ?', [
                $request->name,
                $request->price,
                $request->description,
                $id
            ]);

            $product = DB::select('SELECT * FROM products WHERE id = ?', [$id])[0];
            return response()->json(['status' => 200, 'data' => $product], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 100, 'message' => 'Error al actualizar producto'], 200);
        }
    }

    public function destroy($id)
    {
        try {
            $products = DB::select('SELECT * FROM products WHERE id = ?', [$id]);
            if (empty($products)) {
                return response()->json(['status' => 100, 'message' => 'Producto no encontrado'], 200);
            }

            DB::delete('DELETE FROM products WHERE id = ?', [$id]);
            return response()->json(['status' => 200, 'message' => 'Producto eliminado correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 100, 'message' => 'Error al eliminar producto'], 200);
        }
    }
}
