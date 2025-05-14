<?php
namespace App\UseCases;

use App\Interfaces\ArticleRepositoryInterface;

class GetAllArticles {
    private ArticleRepositoryInterface $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository) {
        $this->articleRepository = $articleRepository;
    }

    public function execute(): array {
        return $this->articleRepository->getAll();
    }
}
