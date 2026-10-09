<?php

namespace App\Controllers;

use App\Libraries\ImageUploader;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use RuntimeException;

class StaffController extends BaseController
{
    private UserModel $users;

    public function __construct()
    {
        $this->users = new UserModel();
    }

    public function index(): string
    {
        return $this->render('staff/index', [
            'title' => 'Staff',
            'staff' => $this->users->orderBy('full_name')->findAll(),
        ]);
    }

    public function new(): string
    {
        return $this->render('staff/form', ['title' => 'Add Staff Member', 'staffMember' => null]);
    }

    public function create()
    {
        $data = $this->request->getPost(['username', 'full_name', 'password', 'password_confirm']);
        $rules = $this->rules(true, true);
        if (! $this->validateData($data, $rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validationErrors());
        }

        try {
            $data['avatar'] = (new ImageUploader())->store($this->request->getFile('avatar'), 'avatars');
            unset($data['password_confirm']);
            $this->users->insert($data);
        } catch (RuntimeException $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }

        return redirect()->to('/staff')->with('success', 'Staff member added successfully.');
    }

    public function edit(int $id): string
    {
        return $this->render('staff/form', [
            'title' => 'Edit Staff Member',
            'staffMember' => $this->findOrFail($id),
        ]);
    }

    public function update(int $id)
    {
        $staff = $this->findOrFail($id);
        $data = $this->request->getPost(['username', 'full_name', 'password', 'password_confirm']);
        $data['id'] = $id;
        if (! $this->validateData($data, $this->rules(false, $data['password'] !== ''))) {
            return redirect()->back()->withInput()->with('errors', $this->validationErrors());
        }

        try {
            $data['avatar'] = (new ImageUploader())->store(
                $this->request->getFile('avatar'),
                'avatars',
                $staff['avatar'],
            );
            unset($data['id'], $data['password_confirm']);
            if ($data['password'] === '') {
                unset($data['password']);
            }
            $this->users->update($id, $data);
        } catch (RuntimeException $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }

        if ((int) (session()->get('user')['id'] ?? 0) === $id) {
            $fresh = $this->users->find($id);
            session()->set('user', [
                'id' => (int) $fresh['id'],
                'username' => $fresh['username'],
                'full_name' => $fresh['full_name'],
                'avatar' => $fresh['avatar'],
            ]);
        }

        return redirect()->to('/staff')->with('success', 'Staff member updated successfully.');
    }

    public function delete(int $id)
    {
        $staff = $this->findOrFail($id);
        if ((int) (session()->get('user')['id'] ?? 0) === $id) {
            return redirect()->to('/staff')->with('error', 'You cannot delete the account you are currently using.');
        }

        $hasSales = db_connect()->table('sales')->where('sold_by', $id)->countAllResults() > 0;
        if ($hasSales) {
            return redirect()->to('/staff')->with('error', 'This staff member has sales history and cannot be deleted.');
        }

        $this->users->delete($id);
        (new ImageUploader())->remove($staff['avatar'], 'avatars');

        return redirect()->to('/staff')->with('success', 'Staff member deleted.');
    }

    private function rules(bool $creating, bool $changingPassword): array
    {
        $unique = $creating ? 'is_unique[users.username]' : 'is_unique[users.username,id,{id}]';
        $password = $creating
            ? 'required|min_length[8]|max_length[255]'
            : 'permit_empty|min_length[8]|max_length[255]';

        return [
            'id' => $creating ? 'permit_empty' : 'required|integer',
            'username' => 'required|alpha_numeric_punct|min_length[3]|max_length[50]|' . $unique,
            'full_name' => 'required|max_length[100]',
            'password' => $password,
            'password_confirm' => ($creating || $changingPassword ? 'required|' : 'permit_empty|') . 'matches[password]',
        ];
    }

    private function findOrFail(int $id): array
    {
        $staff = $this->users->find($id);
        if ($staff === null) {
            throw PageNotFoundException::forPageNotFound('Staff member not found.');
        }

        return $staff;
    }
}
