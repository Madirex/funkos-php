<?php

use PHPUnit\Framework\TestCase;

use services\CategoriesService;
use models\Category;

require_once __DIR__ . '/../src/services/CategoriesService.php';
require_once __DIR__ . '/../src/models/Category.php';

/**
 * Tests
 */
class CategoriesServiceTest extends TestCase
{
    /**
     * Test findAll
     */
    public function testFindAll()
    {
       $categoriesMock = $this->createMock(CategoriesService::class);
       $categoriesMock->expects($this->exactly(4))
           ->method('findAll')
           ->willReturn([
               new Category(1, 'Category 1', '2021-01-01', '2021-01-01', false),
               new Category(2, 'Category 2', '2021-01-01', '2021-01-01', false),
               new Category(3, 'Category 3', '2021-01-01', '2021-01-01', false),
           ]);

        $this->assertEquals(3, count($categoriesMock->findAll()));
        $this->assertEquals('Category 1', $categoriesMock->findAll()[0]->name);
        $this->assertEquals('Category 2', $categoriesMock->findAll()[1]->name);
        $this->assertEquals('Category 3', $categoriesMock->findAll()[2]->name);
    }

    /**
     * Test FindAll With Search Term
     */
    public function testFindAllWithSearchTerm()
    {
        $categoriesMock = $this->createMock(CategoriesService::class);
        $categoriesMock->expects($this->exactly(2))
            ->method('findAll')
            ->with('Category 1')
            ->willReturn([
                new Category(1, 'Category 1', '2021-01-01', '2021-01-01', false),
            ]);
        
        $this->assertEquals(1, count($categoriesMock->findAll('Category 1')));
        $this->assertEquals('Category 1', $categoriesMock->findAll('Category 1')[0]->name);
    }

    /**
     * Test FindByName
     */
    public function testFindByName()
    {
        $categoriesMock = $this->createMock(CategoriesService::class);
        $categoriesMock->expects($this->exactly(1))
            ->method('findByName')
            ->willReturn(new Category(1, 'Category 1', '2021-01-01', '2021-01-01', false));

        $this->assertEquals('Category 1', $categoriesMock->findByName('Category 1')->name);
    }

    /**
     * Test FindById
     */
    public function testFindById()
    {
        $categoriesMock = $this->createMock(CategoriesService::class);
        $categoriesMock->expects($this->exactly(1))
            ->method('findById')
            ->willReturn(new Category(1, 'Category 1', '2021-01-01', '2021-01-01', false));

        $this->assertEquals('Category 1', $categoriesMock->findById(1)->name);
    }

    /**
     * Test FindByName Returns False
     */
    public function testFindByNameReturnsFalse()
    {
        $categoriesMock = $this->createMock(CategoriesService::class);
        $categoriesMock->expects($this->exactly(1))
            ->method('findByName')
            ->willReturn(false);

        $this->assertEquals(false, $categoriesMock->findByName('Category 1'));
    }

    /**
     * Test DeleteById
     */
    public function testDeleteById()
    {
        $categoriesMock = $this->createMock(CategoriesService::class);
        $categoriesMock->expects($this->exactly(1))
            ->method('deleteById')
            ->willReturn(true);

        $this->assertEquals(true, $categoriesMock->deleteById(1));
        $this->assertEquals(false, $categoriesMock->findById(1));
    }

    /**
     * Test Save
     */
    public function testSave()
    {
        $category = new Category(1, 'Category 1', '2021-01-01', '2021-01-01', false);
        $categoriesMock = $this->createMock(CategoriesService::class);
        $categoriesMock->expects($this->exactly(1))
            ->method('save')
            ->with($category)
            ->willReturn(true);

        $this->assertEquals(true, $categoriesMock->save($category));
    }

    /**
     * Test Update
     */
    public function testUpdate()
    {
        $category = new Category(1, 'Category 1', '2021-01-01', '2021-01-01', false);
        $categoriesMock = $this->createMock(CategoriesService::class);
        $categoriesMock->expects($this->exactly(1))
            ->method('update')
            ->with($category)
            ->willReturn(true);

        $this->assertEquals(true, $categoriesMock->update($category));
    }
}
