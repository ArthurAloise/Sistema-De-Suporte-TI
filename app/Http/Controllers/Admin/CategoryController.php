<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\SlaService;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth'); // habilite se necessário
    // }

    public function index(Request $request)
    {
        $search = trim($request->get('q', ''));
        $categories = Category::when(
            $search,
            fn($q) =>
            $q->where('nome', 'LIKE', "%{$search}%")
        )
            ->orderBy('nome')
            ->paginate(10)
            ->withQueryString();

        return view('admin.categories.index', compact('categories', 'search'));
    }

    public function create()
    {
        $category = new Category();
        return view('admin.categories.create', compact('category'));
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());
        SlaService::forgetCategory($category->nome);

        return redirect()->route('categories.index')->with('success', 'Categoria criada com sucesso.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        SlaService::forgetCategory($category->nome);


        return redirect()->route('categories.index')->with('success', 'Categoria atualizada com sucesso.');
    }

    public function destroy(Category $category)
    {
        // Verifica se existem tickets associados antes de excluir
        if ($category->tickets()->exists()) {
            return redirect()->route('categories.index')
                ->with('error', "Não foi possível excluir a categoria '{$category->nome}'. Existem tickets associados a ela.");
        }

        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Categoria excluída com sucesso.');
    }
}
