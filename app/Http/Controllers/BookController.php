<?php

namespace App\Http\Controllers;

use App\DTOs\AuthorDTOOUT;
use App\DTOs\BookCreateDTOIN;
use App\DTOs\BookDTOIN;
use App\DTOs\BookDTOOUT;
use App\Http\Requests\BookRequest;
use App\Services\BookService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookController extends Controller
{
    private const PER_PAGE = 15;
    private BookService $service;

    public function __construct(BookService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', self::PER_PAGE);
        return response()->json($this->service->getBooks($perPage));
    }

    public function store(Request $request)
    {
        $validationError = BookRequest::validateCreate($request->all());

        if ($validationError) {
            return response()->json(['error' => $validationError], Response::HTTP_BAD_REQUEST);
        }

        $bookCreateDTOIN = new BookCreateDTOIN(
            $request->title,
            $request->year_published,
            $request->author_id
        );

        $this->service->createBook($bookCreateDTOIN);

        return response()->json([], Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $validationError = BookRequest::validateId($id);

        if ($validationError) {
            return response()->json(['error' => $validationError], Response::HTTP_BAD_REQUEST);
        }

        $bookDTOIN = new BookDTOIN($id);

        $data = $this->service->getBook($bookDTOIN);

        if (empty($data[0])) {
            return response()->json(['error' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }

        $dateBirth = Carbon::createFromFormat('d/m/Y', $data[0]->author->date_birth);

        $authorDTOOUT = new AuthorDTOOUT(
            $data[0]->author->id,
            $data[0]->author->name,
            $dateBirth
        );

        $bookDTOOUT = new BookDTOOUT($data[0]->id, $data[0]->title, $data[0]->year_published, $authorDTOOUT);

        return response()->json(['data' => $bookDTOOUT], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $validationError = BookRequest::validateId($id);

        if ($validationError) {
            return response()->json(['error' => $validationError], Response::HTTP_BAD_REQUEST);
        }

        $bookDTO = new BookDTOIN($id);

        $this->service->updateBook($bookDTO, $request->all());

        return response()->json([], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $validationError = BookRequest::validateId($id);

        if ($validationError) {
            return response()->json(['error' => $validationError], Response::HTTP_BAD_REQUEST);
        }

        $bookDTO = new BookDTOIN($id);

        $this->service->deleteBook($bookDTO);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
