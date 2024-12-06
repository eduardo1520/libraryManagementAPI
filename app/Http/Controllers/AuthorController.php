<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {
        // Retorne uma lista de livros, por exemplo.
    }

    public function store(Request $request)
    {
        // Lógica para armazenar um novo livro.
    }

    public function show($id)
    {
        // Lógica para mostrar um livro específico.
    }

    public function update(Request $request, $id)
    {
        // Lógica para atualizar um livro existente.
    }

    public function destroy($id)
    {
        // Lógica para excluir um livro.
    }
}
