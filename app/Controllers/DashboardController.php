<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\ProductModel;
use App\Models\SaleModel;
use App\Models\UserModel;

class DashboardController extends BaseController
{
    public function index(): string
    {
        $saleModel = new SaleModel();
        $today = date('Y-m-d');

        return $this->render('dashboard', [
            'title' => 'Dashboard',
            'productCount' => (new ProductModel())->where('is_active', 1)->countAllResults(),
            'customerCount' => (new CustomerModel())->countAllResults(),
            'staffCount' => (new UserModel())->countAllResults(),
            'todaySales' => (int) $saleModel->where('created_at >=', $today . ' 00:00:00')->countAllResults(),
            'recentSales' => array_slice($saleModel->history(), 0, 5),
        ]);
    }
}
