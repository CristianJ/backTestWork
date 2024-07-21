<?php
namespace src\Books\Application\Services;




use src\utils\Constants;
use src\utils\BaseResponse;
use src\Books\Domain\Model\Book;
use src\Books\Application\DTO\BookDTO;
use src\Books\Application\DTO\CreateBookDTO;
use src\Books\Application\DTO\UpdateBookDTO;
use src\Books\Domain\Repository\BookRepositoryInterface;

class BookService
{
    private $repository;

    public function __construct(BookRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function createBook(CreateBookDTO $dto): BaseResponse
    {
        $form = new BookForm();
        $form->loadFromDTO($dto);

        if (!$form->validate()) {
            return new BaseResponse(false, Constants::FORM_VALIDATION, $form->errors);
        }

        try {
            $book = new Book($dto->title, $dto->authors, $dto->publicationYear, $dto->description);
            $this->repository->save($book);
            return new BaseResponse(true, Constants::SUCCESS_BOOK_CREATE, $book);
        } catch (\Exception $e) {
            return new BaseResponse(false, Constants::FAILED_CREATE_BOOK . $e->getMessage(), null);
        }
    }

    public function updateBook(UpdateBookDTO $dto): BaseResponse
    {
        try {
            $book = $this->repository->findById($dto->id);
            if (!$book) {
                return new BaseResponse(false, Constants::BOOK_NOT_FOUND, null);
            }

            if ($dto->title !== null) {
                $book->setTitle($dto->title);
            }
            if ($dto->authors !== null) {
                $book->setAuthors($dto->authors);
            }
            if ($dto->publicationYear !== null) {
                $book->setPublicationYear($dto->publicationYear);
            }
            if ($dto->description !== null) {
                $book->setDescription($dto->description);
            }

            $this->repository->save($book);

            return new BaseResponse(true, Constants::SUCCESS_BOOK_UPDATE, null);
        } catch (\Exception $e) {
            return new BaseResponse(false, Constants::FAILED_UPDATE_BOOK . $e->getMessage(), null);
        }
    }

    public function getBookById($id): BaseResponse
    {
        try {
            $book = $this->repository->findById($id);
            if ($book) {
                $bookDTO = new BookDTO($book->getId(), $book->getTitle(), $book->getAuthors(), $book->getPublicationYear(), $book->getDescription());
                return new BaseResponse(true, Constants::BOOK_FOUND, $bookDTO);
            }
            return new BaseResponse(false, Constants::BOOK_NOT_FOUND, null);
        } catch (\Exception $e) {
            return new BaseResponse(false, Constants::FAILED_GET_BOOK . $e->getMessage(), null);
        }
    }

    public function getAllBooks(): BaseResponse
    {
        try {
            $books = $this->repository->findAll();
            $booksDTO = array_map(function($book) {
                return new BookDTO($book->getId(), $book->getTitle(), $book->getAuthors(), $book->getPublicationYear(), $book->getDescription());
            }, $books);
            return new BaseResponse(true, Constants::BOOKS_FOUND, $booksDTO);
        } catch (\Exception $e) {
            return new BaseResponse(false, Constants::FAILED_GET_BOOKS . $e->getMessage(), null);
        }
    }

    public function deleteBook($id): BaseResponse
    {
        try {
            $book = $this->repository->findById($id);
            if ($book) {
                $this->repository->delete($book);
                return new BaseResponse(true, Constants::SUCCESS_DELETE_BOOK, $book);
            }
            return new BaseResponse(false, Constants::BOOK_NOT_FOUND, null);
        } catch (\Exception $e) {
            return new BaseResponse(false, Constants::FAILED_DELETE_BOOK . $e->getMessage(), null);
        }
    }
}