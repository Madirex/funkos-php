<?php

use PHPUnit\Framework\TestCase;

use services\FunkosService;
use models\Funko;

require_once __DIR__ . '/../src/services/FunkosService.php';
require_once __DIR__ . '/../src/models/Funko.php';

/**
 * Tests
 */
class FunkosServiceTest extends TestCase
{
    /**
     * Test findAll with category name
     */
    public function testFindAll()
    {
       $funkosMock = $this->createMock(FunkosService::class);
       $funkosMock->expects($this->exactly(7))
           ->method('findAllWithCategoryName')
           ->willReturn([
               new Funko(1, "7fefb257-2c30-4aea-964c-b3d9fb9ff793", "Funko 1", "images/funkos.bmp", 100, 10, "2021-01-01", "2021-01-01", 1, "Category 1", false),
                new Funko(2, "7fefb257-2c30-4aea-964c-b3d9fb9ff793", "Funko 2", "images/funkos.bmp", 100, 10, "2021-01-01", "2021-01-01", 1, "Category 2", false),
                new Funko(3, "7fefb257-2c30-4aea-964c-b3d9fb9ff793", "Funko 3", "images/funkos.bmp", 100, 10, "2021-01-01", "2021-01-01", 1, "Category 3", false),
           ]);

        $this->assertEquals(3, count($funkosMock->findAllWithCategoryName()));
        $this->assertEquals('Funko 1', $funkosMock->findAllWithCategoryName()[0]->description);
        $this->assertEquals('Funko 2', $funkosMock->findAllWithCategoryName()[1]->description);
        $this->assertEquals('Funko 3', $funkosMock->findAllWithCategoryName()[2]->description);
        $this->assertEquals('images/funkos.bmp', $funkosMock->findAllWithCategoryName()[0]->image);
        $this->assertEquals(100, $funkosMock->findAllWithCategoryName()[0]->price);
        $this->assertEquals(10, $funkosMock->findAllWithCategoryName()[0]->stock);
    }

    /**
     * Test FindAll With Search Term
     */
    public function testFindAllWithCategoryName()
    {
        $funkosMock = $this->createMock(FunkosService::class);
        $funkosMock->expects($this->exactly(2))
            ->method('findAllWithCategoryName')
            ->with('Test')
            ->willReturn([
                new Funko(1, "7fefb257-2c30-4aea-964c-b3d9fb9ff793", "Test", "images/funkos.bmp", 100, 10, "2021-01-01", "2021-01-01", 1, "Category 1", false),
            ]);
        
        $this->assertEquals(1, count($funkosMock->findAllWithCategoryName('Test')));
        $this->assertEquals('Test', $funkosMock->findAllWithCategoryName('Test')[0]->description);
    }

    /**
     * Test FindById
     */
    public function testFindById()
    {
        $funkosMock = $this->createMock(FunkosService::class);
        $funkosMock->expects($this->exactly(1))
            ->method('findById')
            ->willReturn(new Funko(1, "7fefb257-2c30-4aea-964c-b3d9fb9ff793", "Funko 1", "images/funkos.bmp", 100, 10, "2021-01-01", "2021-01-01", 1, "Category 1", false));

        $this->assertEquals('Funko 1', $funkosMock->findById(1)->description);
    }

    /**
     * Test deleteById
     */
    public function testDeleteById()
    {
        $funkosMock = $this->createMock(FunkosService::class);
        $funkosMock->expects($this->exactly(1))
            ->method('deleteById')
            ->willReturn(true);

        $this->assertEquals(true, $funkosMock->deleteById(1));
    }

    /**
     * Test Save
     */
    public function testSave()
    {
        $funko = new Funko(1, "7fefb257-2c30-4aea-964c-b3d9fb9ff793", "Funko 1", "images/funkos.bmp", 100, 10, "2021-01-01", "2021-01-01", 1, "Category 1", false);
        $funkosMock = $this->createMock(FunkosService::class);
        $funkosMock->expects($this->exactly(1))
            ->method('save')
            ->with($funko)
            ->willReturn(true);

        $this->assertEquals(true, $funkosMock->save($funko));
    }

    /**
     * Test Update
     */
    public function testUpdate()
    {
        $funko = new Funko(1, "7fefb257-2c30-4aea-964c-b3d9fb9ff793", "Funko 1", "images/funkos.bmp", 100, 10, "2021-01-01", "2021-01-01", 1, "Category 1", false);
        $funkosMock = $this->createMock(FunkosService::class);
        $funkosMock->expects($this->exactly(1))
            ->method('update')
            ->with($funko)
            ->willReturn(true);

        $this->assertEquals(true, $funkosMock->update($funko));
    }
}
