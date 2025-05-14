<?php
namespace App\UseCases;

use App\Interfaces\ArticleRepositoryInterface;

class DeleteArticle
{
    private ArticleRepositoryInterface $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function execute(int $id): bool
    {
        // Verifikasi apakah artikel ada
        $article = $this->articleRepository->getById($id);
        
        if ($article === null) {
            return false; // Artikel tidak ditemukan
        }

        // Hapus artikel
        return $this->articleRepository->delete($id);
    }
}
