<?php

namespace App\Http\Controllers;

use App\DTOs\AuthorCreateDTOIN;
use App\DTOs\AuthorDTOIN;
use App\DTOs\AuthorDTOOUT;
use App\DTOs\AuthorUpdateDTOIN;
use App\Http\Requests\AuthorRequest;
use Laravel\Lumen\Routing\Controller;
use App\Services\AuthorService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthorController extends Controller
{

    private const PER_PAGE = 15;
    private AuthorService $service;

    public function __construct(AuthorService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', self::PER_PAGE);
        return response()->json($this->service->getAuthors($perPage));
    }

    public function store(Request $request)
    {
        $validationError = AuthorRequest::validateCreate($request->all());

        if ($validationError) {
            return response()->json(['error' => $validationError], Response::HTTP_BAD_REQUEST);
        }

        $dateBirth = Carbon::createFromFormat('d/m/Y', $request->date_birth);

        $authorDTO = new AuthorCreateDTOIN($request->name, $dateBirth);

        $this->service->createAuthor($authorDTO);

        return response()->json([], Response::HTTP_CREATED);
    }

    public function show(int $id)
    {
        $validationError = AuthorRequest::validateId($id);

        if ($validationError) {
            return response()->json(['error' => $validationError], Response::HTTP_BAD_REQUEST);
        }

        $authorDTOIN = new AuthorDTOIN($id);

        $data = $this->service->getAuthor($authorDTOIN);

        if (empty($data)) {
            return response()->json(['error' => 'Author not found'], Response::HTTP_NOT_FOUND);
        }

        $dateBirth = Carbon::createFromFormat('d/m/Y', $data->date_birth);

        $authorDTOOUT = new AuthorDTOOUT($data->id, $data->name, $dateBirth);

        return response()->json(['data' => $authorDTOOUT], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $validationError = AuthorRequest::validateId($id);

        if ($validationError) {
            return response()->json(['error' => $validationError], Response::HTTP_BAD_REQUEST);
        }

        $dateBirth = Carbon::createFromFormat('d/m/Y', $request->date_birth);

        $authorUpdateDTOIN = new AuthorUpdateDTOIN($id, $request->name, $dateBirth);
        $authorDTOIN = new AuthorDTOIN($id);

        $author = $this->service->getAuthor($authorDTOIN);

        if (empty($author)) {
            return response()->json(['error' => 'Author not found'], Response::HTTP_NOT_FOUND);
        }

        $author->name = $authorUpdateDTOIN->name;

        $author->date_birth = $authorUpdateDTOIN->dateBirth;

        $author->save();

        return response()->json([], Response::HTTP_OK);
    }

    public function destroy(int $id)
    {
        $validationError = AuthorRequest::validateId($id);

        if ($validationError) {
            return response()->json(['error' => $validationError], Response::HTTP_BAD_REQUEST);
        }

        $authorDTO = new AuthorDTOIN($id);

        $this->service->deleteAuthor($authorDTO);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
