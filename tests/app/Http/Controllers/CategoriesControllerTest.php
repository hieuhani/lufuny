<?php


class CategoriesControllerTest extends TestCase
{
    public function testGetAllCategories()
    {
        $this->get('/categories')
            ->seeStatusCode(200)
            ->seeJson([
                'name' => 'Chưa phân loại'
            ]);
    }
}
