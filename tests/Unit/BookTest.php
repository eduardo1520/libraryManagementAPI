<?php

use App\DTOs\BookCreateDTOIN;
use App\DTOs\BookDTOIN;
use App\Helpers\ThrowHelper;
use App\Http\Requests\BookRequest;
use App\Models\Author;
use App\Models\Book;
use App\Repositories\Eloquent\AuthorRepository;
use App\Repositories\Eloquent\BookRepository;
use App\Services\BookService;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class BookTest extends TestCase
{

    use DatabaseMigrations;

    private Book $book;
    private Author $author;
    private BookRepository $repository;
    private AuthorRepository $authorRepository;
    private BookService $service;
    private BookCreateDTOIN $bookCreateDtoIn;

    protected function setUp(): void
    {
        parent::setUp();

        $this->author = new Author();

        $data = [
            'name' => 'J.K. Rowling',
            'date_birth' => '31/07/1965',
        ];

        $this->author = $this->author->create($data);

        $this->book = new Book();
        $this->repository = new BookRepository($this->book);
        $this->authorRepository = new AuthorRepository($this->author);
        $this->service = new BookService($this->repository, $this->authorRepository);

    }

    public function addBookCreateFailed()
    {
        return [
            [
                [
                    "title" => "Test Book",
                    "year_published" => 2022,
                    "author_id" => 0
                ],
                "The selected author id is invalid."
            ],
            [
                [
                    "title" => "Test Book2",
                    "year_published" => 0,
                    "author_id" => 1
                ],
                "The year published must be 4 digits."
            ],
            [
                [
                    "title" => "Test Book3",
                    "year_published" => '',
                    "author_id" => 1
                ],
                "The year published field is required."
            ],
            [
                [
                    "title" => "Test Book4",
                    "year_published" => null,
                    "author_id" => 1
                ],
                "The year published field is required."
            ],
            [
                [
                    "title" => "Test Book5",
                    "year_published" => 2024,
                    "author_id" => 1000
                ],
                "The selected author id is invalid."
            ],
            [
                [
                    "title" => "Test Book5",
                    "year_published" => 2030,
                    "author_id" => 1
                ],
                "The year published must not be greater than 2024."
            ],
            [
                [
                    "title" => "",
                    "year_published" => 2024,
                    "author_id" => 1
                ],
                "The title field is required."
            ],
            [
                [
                    "title" => "Test Book6",
                    "year_published" => 1,
                    "author_id" => 1
                ],
                "The year published must be 4 digits."
            ]
        ];
    }
    public function test_should_create_book()
    {
        $this->bookCreateDtoIn = new BookCreateDTOIN("Test Book", 2022, $this->author->id);

        $book = $this->service->createBook($this->bookCreateDtoIn);

        $this->assertInstanceOf(Book::class, $book);
        $this->assertNotEmpty($book);
        $this->assertEquals($this->bookCreateDtoIn->title, $book->title);
        $this->assertEquals($this->bookCreateDtoIn->yearPublished, $book->year_published);
        $this->assertEquals($this->bookCreateDtoIn->authorId, $book->author_id);
    }

    /**
     * @dataProvider addBookCreateFailed
     */
    public function test_should_failed_create_book($data, $expectedMessage)
    {
        $validationError = BookRequest::validateCreate($data);

        if ($validationError) {
            $this->assertStringContainsString($expectedMessage, $validationError);
            return;
        }

        $book = $this->service->createBook(new BookCreateDTOIN($data['title'], $data['year_published'], $data['author_id']));

        if (empty($validationError)) {
            $this->expectException(Exception::class);
            $this->expectExceptionMessage($expectedMessage);

            $this->assertEmpty($book);
        }
    }

    public function test_should_findAll_books()
    {
        $this->bookCreateDtoIn = new BookCreateDTOIN("Test Book", 2022, $this->author->id);
        $this->service->createBook($this->bookCreateDtoIn);
        $this->service->createBook($this->bookCreateDtoIn);
        $this->service->createBook($this->bookCreateDtoIn);

        $books = $this->service->getBooks(5);

        $this->assertNotEmpty($books);
        $this->assertIsArray($books);
    }

    public function test_should_findOne_book()
    {
        $this->bookCreateDtoIn = new BookCreateDTOIN("Test Book", 2022, $this->author->id);
        $newBook = $this->service->createBook($this->bookCreateDtoIn);

        $book = $this->service->getBook(new BookDTOIN($newBook->id));

        $this->assertInstanceOf(Book::class, $book[0]);
        $this->assertNotEmpty($book);
        $this->assertEquals($this->bookCreateDtoIn->title, $book[0]->title);
        $this->assertEquals($this->bookCreateDtoIn->yearPublished, $book[0]->year_published);
        $this->assertEquals($this->bookCreateDtoIn->authorId, $book[0]->author_id);
    }

    public function test_should_findOne_failed_book()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Book not found');

        $book = $this->service->getBook(new BookDTOIN(0));

        $this->assertInstanceOf(Book::class, $book[0]);
        $this->assertNotEmpty($book);
        $this->assertEquals($book[0]->title, 'Big Data');
        $this->assertEquals($book[0]->yearPublished, '2024');
    }

    public function test_should_update_book()
    {
        $this->bookCreateDtoIn = new BookCreateDTOIN("Test Book", 2022, $this->author->id);
        $newBook = $this->service->createBook($this->bookCreateDtoIn);

        $this->bookCreateDtoIn = new BookCreateDTOIN("Test Book Updated", 2022, $this->author->id);
        $book = $this->service->updateBook(new BookDTOIN($newBook->id), $this->bookCreateDtoIn->toArray());

        $this->assertInstanceOf(Book::class, $book[0]);
        $this->assertNotEmpty($book);
        $this->assertEquals($this->bookCreateDtoIn->title, $book[0]->title);
        $this->assertEquals($this->bookCreateDtoIn->yearPublished, $book[0]->year_published);
        $this->assertEquals($this->bookCreateDtoIn->authorId, $book[0]->author_id);
    }

    public function test_should_update_failed_book()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Book not found');

        $this->bookCreateDtoIn = new BookCreateDTOIN("Test Book", 2022, $this->author->id);

        $book = $this->service->updateBook(new BookDTOIN(0), $this->bookCreateDtoIn->toArray());

        $this->assertEmpty($book);
    }

    public function test_should_deleted_book()
    {
        $this->bookCreateDtoIn = new BookCreateDTOIN("Test Book", 2022, $this->author->id);
        $newBook = $this->service->createBook($this->bookCreateDtoIn);

        $bookRemove = $this->service->deleteBook(new BookDTOIN($newBook->id));

        $this->assertNotEmpty($bookRemove);
        $this->assertTrue($bookRemove);
    }

    public function test_should_failed_deleted_book()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Book not found');

        $bookRemove = $this->service->deleteBook(new BookDTOIN(0));

        $this->assertNotEmpty($bookRemove);
        $this->assertTrue($bookRemove);
    }
}
