<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\ModulesController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\PaymentTypesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\TenantsController;
use App\Http\Controllers\UsersController;

Route::prefix('v1')->group(function () {
    Route::get('/', function () {
        return response()->json([
            'message' => 'Welcome to the Laravel Base API',
            'status' => 'Connected'
        ]);
    });

    # Rotas gerais para o banco de dados do Sistema sem proteção
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [UsersController::class, 'store']);

    # Rotas gerais para os bancos do Sistema e Clientes/Empresas com proteção
    // Route::middleware(['AuthMiddleware'])->group(function () {

        Route::post('logout', [AuthController::class, 'logout']);

        Route::prefix('tenants')->group(function () {
            Route::get('', [TenantsController::class, 'index']);
            Route::get('{tenant}', [TenantsController::class, 'show']);
            Route::post('', [TenantsController::class, 'store']);
            Route::put('{tenant}', [TenantsController::class, 'update']);
            Route::delete('{module}', [TenantsController::class, 'destroy']);
            Route::post('{tenant}/restore', [TenantsController::class, 'restore']);
            Route::post('{tenant}/assign-modules', [TenantsController::class, 'assignModulesToCompany']);
            Route::post('{tenant}/revoke-modules', [TenantsController::class, 'revokeModulesToCompany']);
            Route::get('{tenant}/list-permissions', [TenantsController::class, 'listCompanyPermissions']);
        });

        Route::prefix('modules')->group(function () {
            Route::get('', [ModulesController::class, 'index']);
            Route::get('{module}', [ModulesController::class, 'show']);
            Route::post('', [ModulesController::class, 'store']);
            Route::put('{module}', [ModulesController::class, 'update']);
            Route::delete('{module}', [ModulesController::class, 'destroy']);
            Route::post('{module}/restore', [ModulesController::class, 'restore']);
            Route::post('{module}/create-permissions', [ModulesController::class, 'createNewPermissions']);
            Route::post('{module}/revoke-permissions', [ModulesController::class, 'revokePermissions']);
        });

        Route::prefix('users')->group(function () {
            Route::get('', [UsersController::class, 'index']);
            Route::get('{user}', [UsersController::class, 'show']);
            Route::post('', [UsersController::class, 'store']);
            Route::put('{user}', [UsersController::class, 'update']);
            Route::delete('{user}', [UsersController::class, 'destroy']);
            Route::post('{user}/restore', [UsersController::class, 'restore']);
            Route::post('{user}/assign-permissions', [UsersController::class, 'assignPermissions']);
        });

        Route::prefix('customers')->group(function () {
            Route::get('', [CustomersController::class, 'index']);
            Route::get('{customer}', [CustomersController::class, 'show']);
            Route::post('', [CustomersController::class, 'store']);
            Route::put('{customer}', [CustomersController::class, 'update']);
            Route::delete('{customer}', [CustomersController::class, 'destroy']);
            Route::post('{customer}/restore', [CustomersController::class, 'restore']);

            
            Route::get('{customer}/phones', [CustomersController::class, 'listPhones']);
            Route::get('{customer}/phones/{customerPhone}', [CustomersController::class, 'showPhone']);
            Route::post('{customer}/phones', [CustomersController::class, 'storePhone']);
            Route::put('{customer}/phones/{customerPhone}', [CustomersController::class, 'updatePhone']);
            Route::delete('{customer}/phones/{customerPhone}', [CustomersController::class, 'destroyPhone']);
            Route::post('{customer}/phones/{customerPhone}/restore', [CustomersController::class, 'restorePhone']);

            Route::get('{customer}/address', [CustomersController::class, 'listAddress']);
            Route::get('{customer}/address/{customerAddress}', [CustomersController::class, 'showAddress']);
            Route::post('{customer}/address', [CustomersController::class, 'storeAddress']);
            Route::put('{customer}/address/{customerAddress}', [CustomersController::class, 'updateAddress']);
            Route::delete('{customer}/address/{customerAddress}', [CustomersController::class, 'destroyAddress']);
            Route::post('{customer}/address/{customerAddress}/restore', [CustomersController::class, 'restoreAddress']);
        });

        Route::prefix('products')->group(function () {
            Route::get('', [ProductsController::class, 'index']);
            Route::get('{product}', [ProductsController::class, 'show']);
            Route::post('', [ProductsController::class, 'store']);
            Route::put('{product}', [ProductsController::class, 'update']);
            Route::delete('{product}', [ProductsController::class, 'destroy']);
            Route::post('{product}/restore', [ProductsController::class, 'restore']);
        });

        Route::prefix('product-categories')->group(function () {
            Route::get('', [CategoriesController::class, 'index']);
            Route::get('{category}', [CategoriesController::class, 'show']);
            Route::post('', [CategoriesController::class, 'store']);
            Route::put('{category}', [CategoriesController::class, 'update']);
            Route::delete('{category}', [CategoriesController::class, 'destroy']);
            Route::post('{category}/restore', [CategoriesController::class, 'restore']);
        });

        Route::prefix('payment-types')->group(function () {
            Route::get('', [PaymentTypesController::class, 'index']);
            Route::get('{paymentType}', [PaymentTypesController::class, 'show']);
            Route::post('', [PaymentTypesController::class, 'store']);
            Route::put('{paymentType}', [PaymentTypesController::class, 'update']);
            Route::delete('{paymentType}', [PaymentTypesController::class, 'destroy']);
            Route::post('{paymentType}/restore', [PaymentTypesController::class, 'restore']);
        });

        Route::prefix('orders')->group(function () {
            Route::get('', [OrdersController::class, 'index']);
            Route::get('{order}', [OrdersController::class, 'show']);
            Route::post('', [OrdersController::class, 'store']);
            Route::put('{order}', [OrdersController::class, 'update']);
            Route::delete('{order}', [OrdersController::class, 'destroy']);
            Route::post('{order}/restore', [OrdersController::class, 'restore']);
            Route::post('{order}/store-products', [OrdersController::class, 'storeOrderProducts']);
            Route::post('{order}/delete-products', [OrdersController::class, 'deleteOrderProducts']);
        });

        # Rotas de tenants para o banco de dados de Clientes/Empresas
        Route::middleware(['tenant'])->group(function () {
            
        });
    // });
});
