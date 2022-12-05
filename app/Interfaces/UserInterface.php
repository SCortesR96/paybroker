<?php

namespace App\Interfaces;

interface UserInterface
{
    public function index();

    public function store($request);

    public function show($cpf);

    public function update($request, $id);

    public function destroy($id);
}
