<?php

namespace App\Repositories;

use App\Interfaces\UserInterface;
use App\Models\User;
use App\Traits\BasicResponses;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserInterface
{
    use BasicResponses;

    public function index()
    {
        return User::all();
    }

    public function show($cpf)
    {
        return User::findByCPF($cpf);
    }

    public function store($request)
    {
        DB::beginTransaction();
        $user = new User();
        $user->cpf = $request->cpf;
        $user->name = $request->name;
        $user->email = $request->email;
        if ($user->save()) {
            DB::commit();
            return $this->basicResponse(true, $user);
        }
        DB::rollBack();
        return $this->basicResponse(false, array());
    }

    public function update($request, $cpf)
    {
        DB::beginTransaction();
        if ($user = User::findByCPF($cpf)) {
            $user->name = $request->name;
            $user->email = $request->email;
            if ($user->save()) {
                DB::commit();
                return $this->basicResponse(true, $user);
            }
            DB::rollBack();
            return $this->basicResponse(false, array());
        }
        return $this->basicResponse(
            false,
            ['error' => 'User not found']
        );
    }

    public function destroy($cpf)
    {
        $user = User::findByCPF($cpf);
        if ($user) {
            if ($user->delete()) {
                return $this->basicResponse(true, array());
            }
            return $this->basicResponse(
                false,
                ['error' => 'There was an error, try it later.']
            );
        }
        return $this->basicResponse(
            false,
            ['error' => 'User not found']
        );
    }
}
