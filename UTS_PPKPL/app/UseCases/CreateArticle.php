<?php
namespace App\UseCases;

use App\Entities\Article;
use App\Interfaces\ArticleRepositoryInterface;

class CreateArticle {
    private ArticleRepositoryInterface $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository) {
        $this->articleRepository = $articleRepository;
    }

    public function execute(string $title, string $content): bool {
        $article = new Article(null, $title, $content);
        return $this->articleRepository->save($article);
    }
}
