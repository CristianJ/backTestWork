<?php
namespace src\Authors\Application\Service;


use src\utils\Constants;
use src\utils\BaseResponse;
use src\Authors\Domain\Model\Author;
use src\Authors\Application\DTO\AuthorDTO;
use src\Authors\Application\DTO\CreateAuthorDTO;
use src\Authors\Application\DTO\UpdateAuthorDTO;
use src\Authors\Domain\Repository\AuthorRepositoryInterface;


class AuthorService
{
    private $repository;

    public function __construct(AuthorRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function createAuthor(CreateAuthorDTO $dto): BaseResponse
    {
        $form = new AuthorForm();
        $form->loadFromDTO($dto);

        if (!$form->validate()) {
            return new BaseResponse(false, Constants::FORM_VALIDATION, $form->errors);
        }

        try {
            $author = new Author($dto->name, $dto->birthday, $dto->bookId);
            $this->repository->save($author);
            return new BaseResponse(true, Constants::SUCCESS_AUTHOR_CREATE, $author);
        } catch (\Exception $e) {
            return new BaseResponse(false, Constants::FAILED_CREATE_AUTHOR. $e, null);
        }
    }

    public function updateAuthor(UpdateAuthorDTO $dto): BaseResponse
    {
        try {
            $author = $this->repository->findById($dto->id);
            if (!$author) {
                return new BaseResponse(false, Constants::AUTHOR_NOT_FOUND, null);
            }

            if ($dto->name !== null) {
                $author->setFullName($dto->name);
            }
            if ($dto->birthday !== null) {
                $author->setBirthDay($dto->birthday);
            }
            if ($dto->book_id !== null) {
                $author->setBookId($dto->book_id);
            }

            $this->repository->save($author);

            return new BaseResponse(true, Constants::SUCESS_AUTHOR_UPDATE, null);
        } catch (\Exception $e) {
            return new BaseResponse(false, Constants::FAILED_UPDATE_AUTHOR . $e->getMessage(), null);
        }
    }

    public function getAuthorById($id): BaseResponse
    {
        try {
            $author = $this->repository->findById($id);
            if ($author) {
                $authorDTO = new AuthorDTO($author->getId(), $author->getFullName(), $author->getBirthDay(), $author->getBookId());
                return new BaseResponse(true, Constants::AUTHOR_FOUND, $authorDTO);
            }
            return new BaseResponse(false, Constants::AUTHOR_NOT_FOUND, null);
        } catch (\Exception $e) {
            return new BaseResponse(false, Constants::FAILED_GET_AUTHOR, null);
        }
    }

    public function getAllAuthors(): BaseResponse
    {
        try {
            $authors = $this->repository->findAll();
            $authorsDTO = array_map(function($author) {
                return new AuthorDTO($author->getId(), $author->getFullName(), $author->getBirthDay(), $author->getBookId());
            }, $authors);
            return new BaseResponse(true, Constants::AUTHORS_FOUND, $authorsDTO);
        } catch (\Exception $e) {
            return new BaseResponse(false, Constants::FAILED_GET_AUTHORS . $e->getMessage(), null);
        }
    }

    public function deleteAuthor($id): BaseResponse
    {
        try {
            $author = $this->repository->findById($id);
            if ($author) {
                $this->repository->delete($author);
                return new BaseResponse(true, Constants::SUCCESS_DELETE_AUTHOR, $author);
            }
            return new BaseResponse(false, Constants::AUTHOR_NOT_FOUND, null);
        } catch (\Exception $e) {
            return new BaseResponse(false, Constants::FAILED_GET_AUTHOR, null);
        }
    }
}