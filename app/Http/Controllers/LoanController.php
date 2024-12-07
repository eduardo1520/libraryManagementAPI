<?php

namespace App\Http\Controllers;

use App\DTOs\AuthorDTOIN;
use App\DTOs\AuthorDTOOUT;
use App\DTOs\BookDTOOUT;
use App\DTOs\LoanDTOIN;
use App\DTOs\LoanDTOOUT;
use App\DTOs\UserDTOOUT;
use App\Http\Requests\LoanRequest;
use App\Services\AuthorService;
use App\Services\LoanService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LoanController extends Controller
{

    private const PER_PAGE = 10;
    private LoanService $service;
    private AuthorService $authorService;

    public function __construct(LoanService $service, AuthorService $authorService)
    {
        $this->service = $service;
        $this->authorService = $authorService;
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', self::PER_PAGE);
        return response()->json($this->service->getLoans($perPage));
    }

    public function store(Request $request)
    {
        // Lógica para armazenar um novo livro.
    }

    public function show($id)
    {
        $validationError = LoanRequest::validateId($id);

        if ($validationError) {
            return response()->json(['error' => $validationError], Response::HTTP_BAD_REQUEST);
        }

        $loanDTOIN = new LoanDTOIN($id);

        $data = $this->service->getLoan($loanDTOIN);

        if (empty($data[0])) {
            return response()->json(['error' => 'Loan not found'], Response::HTTP_NOT_FOUND);
        }

        $authorDTOIN = new AuthorDTOIN($data[0]->book->author_id);

        $author = $this->authorService->getAuthor($authorDTOIN);

        $date_birth = Carbon::createFromFormat('d/m/Y', $author->date_birth);

        $authDTOOUT = new AuthorDTOOUT(
            $author->id,
            $author->name,
            $date_birth
        );

        $bookDTOOUT = new BookDTOOUT(
            $data[0]->book->id,
            $data[0]->book->title,
            $data[0]->book->year_published,
            $authDTOOUT
        );

        $userDTOOUT = new UserDTOOUT(
            $data[0]->user->id,
            $data[0]->user->name,
        );

        $loanDate = Carbon::createFromFormat('Y-m-d', $data[0]->loan_date);
        $returnDate = Carbon::createFromFormat('Y-m-d', $data[0]->return_date);

        $loanDTOOUT = new LoanDTOOUT(
            $data[0]->id,
            $data[0]->book->id,
            $data[0]->user->id,
            $loanDate,
            $returnDate,
            $bookDTOOUT,
            $userDTOOUT
        );

        return response()->json(["data" => $loanDTOOUT->toArray()], Response::HTTP_OK);
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
