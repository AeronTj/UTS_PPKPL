<?php

use PHPUnit\Framework\TestCase;
use App\UseCases\CreateArticle;
use App\Interfaces\ArticleRepositoryInterface;
use App\Entities\Article;

class CreateArticleTest extends TestCase
{
    public function testExecuteCreatesArticle()
    {
        $mockRepo = $this->createMock(ArticleRepositoryInterface::class);
        $mockRepo->expects($this->once())
                 ->method('save')
                 ->with($this->callback(function ($article) {
                     return $article instanceof Article &&
                            $article->getTitle() === 'New Title' &&
                            $article->getContent() === 'New Content';
                 }))
                 ->willReturn(true);

        $createArticle = new CreateArticle($mockRepo);
        $result = $createArticle->execute('New Title', 'New Content');
        
        $this->assertTrue($result);
    }

    public function testExecuteFailsWhenTitleIsEmpty()
    {
        $mockRepo = $this->createMock(ArticleRepositoryInterface::class);
        $createArticle = new CreateArticle($mockRepo);
        $result = $createArticle->execute('', 'Some content');
        $this->assertFalse($result); // Expecting failure due to empty title
    }

    public function testExecuteFailsWhenContentIsEmpty()
    {
        $mockRepo = $this->createMock(ArticleRepositoryInterface::class);
        $createArticle = new CreateArticle($mockRepo);
        $result = $createArticle->execute('Some Title', '');
        $this->assertFalse($result); // Expecting failure due to empty content
    }
}
