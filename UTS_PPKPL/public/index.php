<?php
require_once '../config/database.php';
require_once '../app/Entities/Article.php';
require_once '../app/Interfaces/ArticleRepositoryInterface.php';
require_once '../infrastructure/Database/ArticleRepository.php';
require_once '../app/UseCases/CreateArticle.php';
require_once '../app/UseCases/EditArticle.php';
require_once '../app/UseCases/DeleteArticle.php';
require_once '../app/UseCases/GetAllArticles.php';

$articleRepository = new Infrastructure\Database\ArticleRepository($pdo);

$createArticleUseCase = new \App\UseCases\CreateArticle($articleRepository);
$editArticleUseCase = new \App\UseCases\EditArticle($articleRepository);
$deleteArticleUseCase = new \App\UseCases\DeleteArticle($articleRepository);
$getAllArticlesUseCase = new \App\UseCases\GetAllArticles($articleRepository);

// Create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $createArticleUseCase->execute($_POST['title'], $_POST['content']);
    header('Location: index.php');
    exit;
}

// Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    $editArticleUseCase->execute($_POST['id'], $_POST['title'], $_POST['content']);
    header('Location: index.php');
    exit;
}

// Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $deleteArticleUseCase->execute($_POST['id']);

    // Reset auto increment
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM articles");
    $result = $stmt->fetch();
    if ($result && $result['total'] == 0) {
        $pdo->exec("ALTER TABLE articles AUTO_INCREMENT = 1");
    }
    
    header('Location: index.php');
    exit;
}

// Ambil keyword pencarian
$searchKeyword = isset($_POST['search']) ? trim($_POST['search']) : '';
$articles = $getAllArticlesUseCase->execute();

// Filter pencarian
if (!empty($searchKeyword)) {
    $articles = array_filter($articles, function($article) use ($searchKeyword) {
        return stripos($article->title, $searchKeyword) !== false || 
               stripos($article->content, $searchKeyword) !== false;
    });
}

// Urutkan berdasarkan ID
usort($articles, fn($a, $b) => $a->id <=> $b->id);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>UTS PPKPL - BREAD</title>
    <style>
        body { font-family: sans-serif; width: 800px; margin: auto; margin-bottom: 40px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; vertical-align: top; }
        td:nth-child(3) { word-break: break-word; white-space: pre-wrap; max-width: 300px; }
        input[type=text], textarea { width: 100%; padding: 8px; margin: 5px 0; box-sizing: border-box; }
        button { padding: 8px 12px; margin-right: 5px; }
        textarea { resize: vertical; min-height: 60px; }
    </style>
</head>
<body>
    <h1>UTS PPKPL - BREAD</h1>

    <!-- Form Pencarian -->
    <h3>Search Articles</h3>
    <form method="post">
        <input type="text" name="search" placeholder="Search by title or content" value="<?= htmlspecialchars($searchKeyword) ?>">
        <button type="submit">Search</button>
    </form>

    <!-- Form Create -->
    <h3>Create New Article</h3>
    <form method="post">
        <input type="text" name="title" placeholder="Title" required>
        <textarea name="content" placeholder="Content" required></textarea>
        <button type="submit" name="create">Create</button>
    </form>

    <!-- Tabel Artikel -->
    <h3>Articles</h3>
    <table>
        <tr>
            <th>No</th>
            <th>Title</th>
            <th>Content</th>
            <th>Actions</th>
        </tr>
        <?php $no = 1; foreach ($articles as $article): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($article->title) ?></td>
                <td><?= htmlspecialchars($article->content) ?></td>
                <td>
                    <!-- Edit -->
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $article->id ?>">
                        <input type="text" name="title" value="<?= htmlspecialchars($article->title) ?>" required>
                        <textarea name="content" required><?= htmlspecialchars($article->content) ?></textarea>
                        <button type="submit" name="edit">Edit</button>
                    </form>
                    <!-- Delete -->
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $article->id ?>">
                        <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete?')">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
