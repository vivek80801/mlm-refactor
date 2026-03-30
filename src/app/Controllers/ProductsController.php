<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Services\ProductService;
use Throwable;

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
                (int) $productId,
                (int) $request->session()->get("id"),
            );

        return view("product_detail",  $productDetail );
    }

    public function productRental
    (
        Request $request
    ): mixed
    {
        try{
            $productId = $request
                ->input("product_id");

            $this->productService
                ->productRental(
                    (int) $productId,
                    (int) $request->session()->get("id"),
                    $request->session()->get("invite_code"),
                );
        }catch(Throwable $e) {
            $productDetail = $this->productService
                ->productDetail(
                    (int) $productId,
                    (int) $request->session()->get("id"),
                );

            $productDetailWithErrors = array_merge(
                ["errors" => $e->getMessage()],
                $productDetail
            );

            return view(
                "product_detail",
                $productDetailWithErrors
            );
        }

        return redirect(
            "/product_detail?id=" . $productId
        );
    }
}
