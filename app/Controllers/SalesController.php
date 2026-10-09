<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\ProductModel;
use App\Models\SaleModel;

class SalesController extends BaseController
{
    public function index(): string
    {
        return $this->render('sales/index', [
            'title' => 'Sales History',
            'sales' => (new SaleModel())->history(),
        ]);
    }

    public function new(): string
    {
        return $this->render('sales/form', [
            'title' => 'Record Sale',
            'products' => (new ProductModel())
                ->where('is_active', 1)
                ->where('stock_quantity >', 0)
                ->orderBy('name')
                ->findAll(),
            'customers' => (new CustomerModel())->orderBy('full_name')->findAll(),
        ]);
    }

    public function create()
    {
        $data = $this->request->getPost(['product_id', 'customer_id', 'quantity']);
        $rules = [
            'product_id' => 'required|integer|is_not_unique[products.id]',
            'customer_id' => 'permit_empty|integer|is_not_unique[customers.id]',
            'quantity' => 'required|integer|greater_than[0]',
        ];

        if (! $this->validateData($data, $rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validationErrors());
        }

        $db = db_connect();
        $db->transBegin();

        try {
            $productId = (int) $data['product_id'];
            $quantity = (int) $data['quantity'];
            $product = $db->table('products')
                ->where('id', $productId)
                ->where('is_active', 1)
                ->get()
                ->getRowArray();

            if ($product === null) {
                throw new \RuntimeException('The selected product is unavailable.');
            }

            $db->query(
                'UPDATE products SET stock_quantity = stock_quantity - ?, updated_at = ? '
                . 'WHERE id = ? AND is_active = 1 AND stock_quantity >= ?',
                [$quantity, date('Y-m-d H:i:s'), $productId, $quantity],
            );

            if ($db->affectedRows() !== 1) {
                throw new \RuntimeException(
                    sprintf('Sale rejected. Only %d unit(s) are currently available.', $product['stock_quantity']),
                );
            }

            $total = round((float) $product['price'] * $quantity, 2);
            $inserted = (new SaleModel())->insert([
                'product_id' => $productId,
                'customer_id' => $data['customer_id'] === '' ? null : (int) $data['customer_id'],
                'sold_by' => (int) (session()->get('user')['id'] ?? 0),
                'quantity' => $quantity,
                'total_price' => number_format($total, 2, '.', ''),
            ]);

            if ($inserted === false || $db->transStatus() === false) {
                throw new \RuntimeException('The sale could not be saved. Please try again.');
            }

            $db->transCommit();
        } catch (\Throwable $exception) {
            $db->transRollback();

            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }

        return redirect()->to('/sales')->with('success', 'Sale recorded and inventory updated.');
    }
}
