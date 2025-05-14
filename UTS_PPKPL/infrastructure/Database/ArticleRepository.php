<?php
namespace Infrastructure\Database;

use App\Entities\Article;
use App\Interfaces\ArticleRepositoryInterface;
use PDO;

class ArticleRepository implements ArticleRepositoryInterface {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Ambil semua artikel
    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM articles ORDER BY id DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new Article($row['id'], $row['title'], $row['content']), $rows);
    }

    // Ambil artikel berdasarkan ID
    public function getById(int $id): ?Article {
        $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Article($row['id'], $row['title'], $row['content']) : null;
    }

    // Simpan artikel baru
    public function save(Article $article): bool {
        $stmt = $this->pdo->prepare("INSERT INTO articles (title, content) VALUES (?, ?)");
        $success = $stmt->execute([$article->title, $article->content]);
    
        if ($success) {
            $article->id = (int) $this->pdo->lastInsertId();
        }
    
        return $success;
    }
    

    // Perbarui artikel
    public function update(Article $article): bool {
        $stmt = $this->pdo->prepare("UPDATE articles SET title = ?, content = ? WHERE id = ?");
        return $stmt->execute([$article->title, $article->content, $article->id]);
    }

    // Hapus artikel
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM articles WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
