<?php

namespace Tests\Unit\app\Services;

use App\DTOs\AuthorCreateDTOIN;
use App\DTOs\AuthorDTOIN;
use App\DTOs\BookCreateDTOIN;
use App\DTOs\BookDTOIN;
use App\DTOs\LoanCreateDTOIN;
use App\DTOs\LoanDTOIN;
use App\DTOs\LoanUpdateDTOIN;
use App\DTOs\UserCreateDTOIN;
use App\Models\Author;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use App\Repositories\Eloquent\AuthorRepository;
use App\Repositories\Eloquent\BookRepository;
use App\Repositories\Eloquent\LoanRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Services\AuthorService;
use App\Services\BookService;
use App\Services\LoanService;
use App\Services\UserService;
use Carbon\Carbon;
use Exception;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class LoanTest extends TestCase
{

    use DatabaseMigrations;

    private Loan $loan;
    private User $user;
    private Book $book;
    private Author $author;
    private UserService $userService;
    private BookService $bookService;
    private AuthorService $authorService;
    private LoanService $loanService;
    private UserRepository $userRepository;
    private BookRepository $bookRepository;
    private AuthorRepository $authorRepository;
    private LoanRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = new User();
        $this->userRepository = new UserRepository($this->user);
        $this->userService = new UserService($this->userRepository);

        $this->author = new Author();
        $this->authorRepository = new AuthorRepository($this->author);
        $this->authorService = new AuthorService($this->authorRepository);

        $this->book = new Book();
        $this->bookRepository = new BookRepository($this->book);
        $this->bookService = new BookService($this->bookRepository, $this->authorRepository);

        $this->loan = new Loan();
        $this->repository = new LoanRepository($this->loan);
        $this->loanService = new LoanService($this->repository);
    }

    public function test_should_create_loan()
    {
        $userCreateDTOIN = new UserCreateDTOIN('eduardo', 'eduardo@gmail.com', '7654321');
        $this->userService->createUser($userCreateDTOIN);
        $newUser = $this->userRepository->findByUser($userCreateDTOIN->name, $userCreateDTOIN->email);

        $authorCreateDTOIN = new AuthorCreateDTOIN('Paul Jack', Carbon::parse('10/12/2000'));
        $newAuthor = $this->authorService->createAuthor($authorCreateDTOIN);
        $author = $this->authorService->getAuthor(new AuthorDTOIN($newAuthor->id));

        $bookCreateDTOIN = new BookCreateDTOIN('The Lord of the Rings', 2020,$author[0]->id);
        $newBook = $this->bookService->createBook($bookCreateDTOIN);
        $book = $this->bookService->getBook(new BookDTOIN($newBook->id));

        $loanCreateDTOIN = new LoanCreateDTOIN($newUser[0]->id, $book[0]->id, Carbon::now(), Carbon::now()->addDays(7));

        $newLoan = $this->loanService->createLoan($loanCreateDTOIN);
        $loan = $this->loanService->getLoan(new LoanDTOIN($newLoan['id']));

        $this->assertEquals($loanCreateDTOIN->bookId, $loan[0]->book->id);
        $this->assertEquals($loanCreateDTOIN->userId, $loan[0]->user->id);

        $this->assertInstanceOf(Carbon::class, $loanCreateDTOIN->loanDate);
        $this->assertInstanceOf(Carbon::class, $loanCreateDTOIN->returnDate);
        $this->assertInstanceOf(Carbon::class, Carbon::parse($loan[0]->loan_date));
        $this->assertInstanceOf(Carbon::class, Carbon::parse($loan[0]->return_date));

        $this->assertEquals($loanCreateDTOIN->loanDate->toDateString(),Carbon::parse($loan[0]->loan_date)->toDateString());
        $this->assertEquals($loanCreateDTOIN->returnDate->toDateString(),Carbon::parse($loan[0]->return_date)->toDateString());
    }

    public function test_should_create_failed_loan()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Create failed loan.');

        $loanCreateDTOIN = new LoanCreateDTOIN(0,0,Carbon::now(), Carbon::now()->addDays(7));
        $newLoan = $this->loanService->createLoan($loanCreateDTOIN);

        $loan = $this->loanService->getLoan(new LoanDTOIN($newLoan['id']));

        $this->assertEquals($loanCreateDTOIN->bookId, $loan[0]->book->id);
        $this->assertEquals($loanCreateDTOIN->userId, $loan[0]->user->id);

        $this->assertInstanceOf(Carbon::class, $loanCreateDTOIN->loanDate);
        $this->assertInstanceOf(Carbon::class, $loanCreateDTOIN->returnDate);
        $this->assertInstanceOf(Carbon::class, Carbon::parse($loan[0]->loan_date));
        $this->assertInstanceOf(Carbon::class, Carbon::parse($loan[0]->return_date));

        $this->assertEquals($loanCreateDTOIN->loanDate->toDateString(),Carbon::parse($loan[0]->loan_date)->toDateString());
        $this->assertEquals($loanCreateDTOIN->returnDate->toDateString(),Carbon::parse($loan[0]->return_date)->toDateString());

    }
    public function test_should_fetch_all_loan()
    {
        $amount = 5;
        $perPage = 5;

        $this->createLoan($amount);
        $loans = $this->loanService->getLoans($perPage);

        $this->assertNotEmpty($loans);
        $this->assertIsArray($loans);
    }

    public function test_should_updated_loan()
    {
        $amount = 5;

        $this->createLoan($amount);
        $loan = $this->loanService->getLoan(new LoanDTOIN(1));
        $loan[0]['book_id'] = 4;

        $updatedLoan = $this->loanService->updateLoan(new LoanUpdateDTOIN($loan[0]['id'], null, $loan[0]['book_id'], null, null), $loan[0]->toArray());

        $this->assertInstanceOf(Loan::class, $updatedLoan[0]);
        $this->assertEquals($loan[0]['book_id'], $updatedLoan[0]->book_id);
    }

    public function test_should_delete_loan()
    {
        $amount = 5;

        $this->createLoan($amount);
        $loan = $this->loanService->getLoan(new LoanDTOIN(1));

        $deletedLoan = $this->loanService->deleteLoan(new LoanDTOIN($loan[0]->id));

        $this->assertTrue($deletedLoan);
    }

    private function createLoan($amount)
    {
        for ($item = 0; $item < $amount; $item++) {
            $userCreateDTOIN = new UserCreateDTOIN('client_'. $item, 'client_'.$item. '@gmail.com', implode("", array_unique(array_map(fn() => random_int(1, 60), range(1, 6)))));
            $this->userService->createUser($userCreateDTOIN);
            $newUser = $this->userRepository->findByUser($userCreateDTOIN->name, $userCreateDTOIN->email);

            $authorCreateDTOIN = new AuthorCreateDTOIN('Paul Jack'. $item, Carbon::parse('10/12/2000'));
            $newAuthor = $this->authorService->createAuthor($authorCreateDTOIN);
            $author = $this->authorService->getAuthor(new AuthorDTOIN($newAuthor->id));

            $bookCreateDTOIN = new BookCreateDTOIN('The Lord of the Rings '. $item, 2020,$author[0]->id);
            $newBook = $this->bookService->createBook($bookCreateDTOIN);
            $book = $this->bookService->getBook(new BookDTOIN($newBook->id));

            $loanCreateDTOIN = new LoanCreateDTOIN($newUser[0]->id, $book[0]->id, Carbon::now(), Carbon::now()->addDays(7));
            $this->loanService->createLoan($loanCreateDTOIN);
        };
    }

}
