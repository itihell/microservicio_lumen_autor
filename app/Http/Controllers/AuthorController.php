<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Models\Author;
use Illuminate\Http\Response;

class AuthorController extends Controller
{
    use ApiResponser;

    public $rules = [
        'name' => ['required', 'max:250'],
        'gender' => ['required', 'max:250', 'in:male,female'],
        'country' => ['required', 'max:250'],
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        $authors = Author::all();

        return $this->successResponse($authors, 200);
    }

    public function store(Request $request)
    {
        $campos = $this->validate($request, $this->rules);
        $author = Author::create($campos);
        return $this->successResponse($author, Response::HTTP_CREATED);
    }

    public function show($author)
    {
        $author = Author::findOrFail($author);

        return $this->successResponse($author, Response::HTTP_OK);
    }

    public function update(Request $request, $author)
    {
        $author = Author::findOrFail($author);
        $campos = $this->validate($request, $this->rules);
        $author->fill($campos);
        if ($author->isClean()) {
            return $this->errorResponse(
                "No hay cambios en los datos",
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        $author->save();

        return $this->successResponse($author, Response::HTTP_OK);
    }

    public function destroy($author)
    {
        $author = Author::findOrFail($author);
        $author->delete();
        return $this->successResponse($author);
    }
}
