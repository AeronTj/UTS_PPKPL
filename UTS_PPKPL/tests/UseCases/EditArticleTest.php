<?php

use PHPUnit\Framework\TestCase;
use App\UseCases\EditArticle;
use App\Entities\Article;
use App\Interfaces\ArticleRepositoryInterface;

class EditArticleTest extends TestCase
{
    public function testExecuteEditsArticle()
    {
        $mockRepo = $this->createMock(ArticleRepositoryInterface::class);
        
        $mockRepo->expects($this->once())
                 ->method('getById')
                 ->with(1)
                 ->willReturn(new Article(1, 'Old Title', 'Old Content'));
        
        $mockRepo->expects($this->once())
                 ->method('update')
                 ->with($this->callback(function ($article) {
                     return $article instanceof Article &&
                            $article->getTitle() === 'Updated Title' &&
                            $article->getContent() === 'Updated Content';
                 }))
                 ->willReturn(true);
        
        $editArticle = new EditArticle($mockRepo);
        $result = $editArticle->execute(1, 'Updated Title', 'Updated Content');
        
        $this->assertTrue($result);
    }

    public function testExecuteFailsWhenArticleNotFound()
    {
        $mockRepo = $this->createMock(ArticleRepositoryInterface::class);
        $mockRepo->expects($this->once())
                 ->method('getById')
                 ->with(1)
                 ->willReturn(null); 
    
        $editArticle = new EditArticle($mockRepo);
        $result = $editArticle->execute(1, 'New Title', 'New Content');
    
        $this->assertFalse($result); 
    }

    public function testExecuteFailsWhenNewTitleIsEmpty()
    {
        $mockRepo = $this->createMock(ArticleRepositoryInterface::class);
        $mockRepo->expects($this->once())
                 ->method('getById')
                 ->with(1)
                 ->willReturn(new Article(1, 'Old Title', 'Old Content'));
    
        $editArticle = new EditArticle($mockRepo);
        $result = $editArticle->execute(1, '', 'Updated Content');
    
        $this->assertFalse($result); 
    }
}
