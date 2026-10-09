<?php

namespace App\Controllers;

use App\Libraries\ImageUploader;
use App\Models\ProductModel;
use RuntimeException;

class ProductsController extends BaseController
{
    private ProductModel $products;

    public function __construct()
    {
        $this->products = new ProductModel();
    }

    public function index(): string
    {
        return $this->render('products/index', [
            'title' => 'Products',
            'products' => $this->products->orderBy('is_active', 'DESC')->orderBy('name')->findAll(),
        ]);
    }

    public function new(): string
    {
        return $this->render('products/form', ['title' => 'Add Product', 'product' => null]);
    }

    public function create()
    {
        $data = $this->request->getPost(['name', 'price', 'stock_quantity']);
        if (! $this->validateData($data, $this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validationErrors());
        }

        try {
            $data['image'] = (new ImageUploader())->store($this->request->getFile('image'), 'products');
            $data['is_active'] = 1;
            $this->products->insert($data);
        } catch (RuntimeException $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }

        return redirect()->to('/products')->with('success', 'Product added successfully.');
    }

    public function edit(int $id): string
    {
        return $this->render('products/form', [
            'title' => 'Edit Product',
            'product' => $this->findOrFail($id),
        ]);
    }

    public function update(int $id)
    {
        $product = $this->findOrFail($id);
        $data = $this->request->getPost(['name', 'price', 'stock_quantity']);
        if (! $this->validateData($data, $this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validationErrors());
        }

        try {
            $data['image'] = (new ImageUploader())->store(
                $this->request->getFile('image'),
                'products',
                $product['image'],
            );
            $this->products->update($id, $data);
        } catch (RuntimeException $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }

        return redirect()->to('/products')->with('success', 'Product updated successfully.');
    }

    public function archive(int $id)
    {
        $this->findOrFail($id);
        $this->products->update($id, ['is_active' => 0]);

        return redirect()->to('/products')->with('success', 'Product archived. Sales history was preserved.');
    }

    public function restore(int $id)
    {
        $this->findOrFail($id);
        $this->products->update($id, ['is_active' => 1]);

        return redirect()->to('/products')->with('success', 'Product restored.');
    }

    private function rules(): array
    {
        return [
            'name' => 'required|max_length[100]',
            'price' => 'required|numeric|greater_than_equal_to[0]',
            'stock_quantity' => 'required|integer|greater_than_equal_to[0]',
        ];
    }

    private function findOrFail(int $id): array
    {
        $product = $this->products->find($id);
        if ($product === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Product not found.');
        }

        return $product;
    }
}
