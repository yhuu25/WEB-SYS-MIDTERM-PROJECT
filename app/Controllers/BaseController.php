<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $helpers = ['form', 'url'];

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger,
    ): void {
        parent::initController($request, $response, $logger);
    }

    protected function render(string $view, array $data = []): string
    {
        $data['currentUser'] = session()->get('user');

        return view($view, $data);
    }

    protected function validationErrors(): array
    {
        return $this->validator?->getErrors() ?? [];
    }
}
