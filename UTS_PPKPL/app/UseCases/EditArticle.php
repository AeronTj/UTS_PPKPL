<?php
namespace App\UseCases;

use App\Entities\Article;
use App\Interfaces\ArticleRepositoryInterface;

class EditArticle {
    private ArticleRepositoryInterface $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository) {
        $this->articleRepository = $articleRepository;
    }

    public function execute(int $id, string $title, string $content): bool {
        // Mengambil artikel berdasarkan ID
        $article = $this->articleRepository->getById($id);
        
        if ($article) {
            // Update artikel
            $article->title = $title;
            $article->content = $content;
            return $this->articleRepository->update($article);
        }
        return false;
    }
}
