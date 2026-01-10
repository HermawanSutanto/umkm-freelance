<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MitraProfile;
use App\Models\User;
use Illuminate\Http\Request;

class ShopApiController extends Controller
{
    public function index(Request $request)
    {
        $query = MitraProfile::with('user'); 

        if ($request->has('search') && $request->search != '') {
            $query->where('shop_name', 'LIKE', '%' . $request->search . '%');
        }

        $shops = $query->paginate(12);

        $data = $shops->getCollection()->transform(function ($shop) {
            return [
                'id'           => $shop->id,
                'user_id'      => $shop->user_id,
                'shop_name'    => $shop->shop_name,
                'shop_address' => $shop->shop_address,
                'shop_image' => $shop->shop_image_url,

                'total_products' => \App\Models\Product::where('user_id', $shop->user_id)
                                    ->where('is_active', true)
                                    ->count(),
            ];
        });

        $shops->setCollection($data);

        return response()->json([
            'success' => true,
            'message' => 'Daftar toko berhasil diambil',
            'data'    => $shops
        ], 200);
    }

    public function show($id)
    {
        $shop = MitraProfile::where('id', $id)->first();

        if (!$shop) {
            return response()->json(['success' => false, 'message' => 'Toko tidak ditemukan'], 404);
        }

        $products = \App\Models\Product::where('user_id', $shop->user_id)
            ->where('is_active', true)
            ->latest()
            ->get()
            ->map(function ($product) {
                return [
                    'id'           => $product->id,
                    'name'         => $product->name,
                    'slug'         => $product->slug,
                    'price_format' => $product->price_format,
                    'cover_url'    => $product->cover_url,
                ];
            });

        $detail = [
            'id'                => $shop->id,
            'shop_name'         => $shop->shop_name,
            'shop_description'  => $shop->shop_description,
            'shop_address'      => $shop->shop_address,
            'operational_hours' => $shop->operational_hours,
            'gmaps_link'        => $shop->gmaps_link,
            'whatsapp_number'   => $shop->phone_number,
            'shop_image'        => $shop->shop_image_url,
            'products'          => $products,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Detail toko berhasil diambil',
            'data'    => $detail
        ], 200);
    }
}