<?php

namespace Tests\Unit\app\Services;

use App\DTOs\AuthorCreateDTOIN;
use App\DTOs\AuthorDTOIN;
use App\Http\Requests\AuthorRequest;
use App\Models\Author;
use App\Repositories\Eloquent\AuthorRepository;
use App\Services\AuthorService;
use Carbon\Carbon;
use Exception;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class AuthorTest extends TestCase
{

    use DatabaseMigrations;  // Usar o trait para migração no banco de dados

    protected AuthorService $authorService;
    protected AuthorRepository $authorRepository;
    protected Author $author;
    protected AuthorCreateDTOIN $authorCreateDTOIn;

    // Método executado antes de cada teste
    protected function setUp(): void
    {
        parent::setUp(); // Sempre chame o parent::setUp()

        $this->author = new Author();
        $this->authorRepository = new AuthorRepository($this->author);
        $this->authorService  = new AuthorService($this->authorRepository);

    }
    public function test_should_create_author()
    {
        // Mock de dados
        $data = [
            'name' => 'J.K. Rowling',
            'date_birth' => '31/07/1965',
        ];

        // Configurar valores das propriedades públicas
        $this->authorCreateDTOIn = new AuthorCreateDTOIN($data['name'], Carbon::createFromFormat('d/m/Y', $data['date_birth']));

        // Chama o método de criação de autor
        $author = $this->authorService->createAuthor($this->authorCreateDTOIn);

        // Validação do resultado
        $this->assertInstanceOf(Author::class, $author); // Verifica se é uma instância de Author
        $this->assertEquals($data['name'], $author->name); // Verifica se o nome é o esperado
        $this->assertEquals($data['date_birth'], $author->date_birth); // Verifica se a data de nascimento é a esperada
    }

    public function test_should_failed_when_try_create_author_without_name()
    {
         // Mock de dados
         $data = [
            'name' => '',
            'date_birth' => '31/07/1965',
        ];

        $validationError = AuthorRequest::validateCreate($data);

        $this->assertStringContainsString('The name field is required', $validationError);
    }
    public function test_should_failed_when_try_create_author_without_date_birth()
    {
         // Mock de dados
         $data = [
            'name' => 'J.K. Rowling',
            'date_birth' => '',
        ];

        $validationError = AuthorRequest::validateCreate($data);

        $this->assertStringContainsString('The date birth field is required', $validationError);
    }

    public function test_should_find_all_authors()
    {
        $data = [
            [
                'name' => 'Paul Jack',
                'date_birth' => '10/12/1980',
            ],
            [
                'name' => 'Marly Lucy',
                'date_birth' => '04/09/1990',
            ],
            [
                'name' => 'Enzo Patrick',
                'date_birth' => '02/05/1975',
            ]
        ];

        array_walk($data, function($person) {
            $this->authorCreateDTOIn = new AuthorCreateDTOIN($person['name'], Carbon::createFromFormat('d/m/Y', $person['date_birth']));
            $this->authorService->createAuthor($this->authorCreateDTOIn);
        });

        $authors = $this->authorService->getAuthors(5);

        $this->assertNotEmpty($authors);
        $this->assertIsArray($authors);
    }

    public function test_should_find_one_author()
    {
        $data = [
            'name' => 'Paul Jack',
            'date_birth' => '10/12/1980',
        ];

        $this->authorCreateDTOIn = new AuthorCreateDTOIN($data['name'], Carbon::createFromFormat('d/m/Y', $data['date_birth']));

        $newAuthor = $this->authorService->createAuthor($this->authorCreateDTOIn);

        $author = $this->authorService->getAuthor(new AuthorDTOIN($newAuthor->id));

        $this->assertInstanceOf(Author::class, $author[0]);
        $this->assertNotEmpty($author);
        $this->assertEquals($this->authorCreateDTOIn->name, $author[0]->name);
        $this->assertEquals(Carbon::parse($this->authorCreateDTOIn->dateBirth)->format('d/m/Y'), $author[0]->date_birth);

    }

    public function test_should_find_one_failed_author()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Author not found');

        $author = $this->authorService->getAuthor(new AuthorDTOIN(0));

        $this->assertInstanceOf(Author::class, $author[0]);
        $this->assertNotEmpty($author);
        $this->assertEquals($this->authorCreateDTOIn->name, $author[0]->name);
        $this->assertEquals(Carbon::parse($this->authorCreateDTOIn->dateBirth)->format('d/m/Y'), $author[0]->date_birth);

    }

    public function test_should_remove_author()
    {
        $data = [
            'name' => 'Paul Jack',
            'date_birth' => '10/12/1980',
        ];

        $this->authorCreateDTOIn = new AuthorCreateDTOIN($data['name'], Carbon::createFromFormat('d/m/Y', $data['date_birth']));

        $newAuthor = $this->authorService->createAuthor($this->authorCreateDTOIn);

        $removeAuthor = $this->authorService->deleteAuthor(new AuthorDTOIN($newAuthor->id));

        $this->assertTrue($removeAuthor);
    }

    public function test_should_remove_failed_author()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Author not found');

        $data = [
            'name' => 'Paul Jack',
            'date_birth' => '10/12/1980',
        ];

        $this->authorCreateDTOIn = new AuthorCreateDTOIN($data['name'], Carbon::createFromFormat('d/m/Y', $data['date_birth']));

        $this->authorService->createAuthor($this->authorCreateDTOIn);

        $removeAuthor = $this->authorService->deleteAuthor(new AuthorDTOIN(0));

        $this->assertTrue($removeAuthor);
    }
}
