<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Product;
use App\Services\ProductService;

class  ProductsController extends Controller
{

    public function __construct
    (
        private ProductService $productService,
    )
    { }

    public function index(): mixed
    {
        $products = $this->productService->all();
        return view("products", [
            "products" => $products
        ]);
    }

    public function productDetail
    (
        Request $request
    ): mixed
    {
        $productId = $request->input("id");
        $productDetail = $this->productService
            ->productDetail(
                $productId,
                $request->session()->get("id"),
            );

        return view("product_detail",  $productDetail );
    }

    public function productRental
    (
        Request $request
    ): mixed
    {
        dd($request);
    }
}
