<?php

declare(strict_types=1);

namespace Melihovv\CollectionGroupedByModel\Tests;

use Melihovv\CollectionGroupedByModel\CollectionGroupedByModel;
use PHPUnit\Framework\TestCase;

class CollectionGroupedByModelTest extends TestCase
{
    /** @test */
    public function it_groups_collection_by_model()
    {
        $groupedCollection = (new CollectionGroupedByModel([
            (object) [
                'id' => 1,
                'relation_id' => 1,
                'relation' => (object) [
                    'id' => '1',
                ],
            ],
            (object) [
                'id' => 2,
                'relation_id' => 2,
                'relation' => (object) [
                    'id' => '2',
                ],
            ],
            (object) [
                'id' => 3,
                'relation_id' => 1,
                'relation' => (object) [
                    'id' => '1',
                ],
            ],
        ]))
            ->groupByModel(function ($item) {
                return $item->relation_id;
            }, function ($item) {
                return $item->relation;
            });

        $this->assertCount(2, $groupedCollection[1]);
        $this->assertEquals(1, $groupedCollection[1]->model()->id);
        $this->assertEquals([1, 3], $groupedCollection[1]->collection()->pluck('id')->all());

        $this->assertCount(1, $groupedCollection[2]);
        $this->assertEquals(2, $groupedCollection[2]->model()->id);
        $this->assertEquals([2], $groupedCollection[2]->collection()->pluck('id')->all());
    }

    /** @test */
    public function it_groups_collection_by_model_using_short_syntax()
    {
        $groupedCollection = (new CollectionGroupedByModel([
            (object) [
                'id' => 1,
                'relation_id' => 1,
                'relation' => (object) [
                    'id' => '1',
                ],
            ],
            (object) [
                'id' => 2,
                'relation_id' => 2,
                'relation' => (object) [
                    'id' => '2',
                ],
            ],
            (object) [
                'id' => 3,
                'relation_id' => 1,
                'relation' => (object) [
                    'id' => '1',
                ],
            ],
        ]))
            ->groupByModel('relation_id', 'relation');

        $this->assertCount(2, $groupedCollection[1]);
        $this->assertEquals(1, $groupedCollection[1]->model()->id);
        $this->assertEquals([1, 3], $groupedCollection[1]->collection()->pluck('id')->all());

        $this->assertCount(1, $groupedCollection[2]);
        $this->assertEquals(2, $groupedCollection[2]->model()->id);
        $this->assertEquals([2], $groupedCollection[2]->collection()->pluck('id')->all());
    }

    /** @test */
    public function it_can_group_by_multiple_models()
    {
        $groupedCollection = (new CollectionGroupedByModel([
            (object) [
                'id' => 1,
                'relation1_id' => 1,
                'relation1' => (object) [
                    'id' => '1',
                ],
                'relation2_id' => 1,
                'relation2' => (object) [
                    'id' => '1',
                ],
            ],
            (object) [
                'id' => 2,
                'relation1_id' => 2,
                'relation1' => (object) [
                    'id' => '2',
                ],
                'relation2_id' => 2,
                'relation2' => (object) [
                    'id' => '2',
                ],
            ],
            (object) [
                'id' => 3,
                'relation1_id' => 1,
                'relation1' => (object) [
                    'id' => '1',
                ],
                'relation2_id' => 1,
                'relation2' => (object) [
                    'id' => '1',
                ],
            ],
        ]))
            ->groupByModel(function ($item) {
                return "$item->relation1_id,$item->relation2_id";
            }, function ($item) {
                return [$item->relation1, $item->relation2];
            });

        $this->assertCount(2, $groupedCollection['1,1']);
        $this->assertEquals([
            (object) [
                'id' => '1',
            ],
            (object) [
                'id' => '1',
            ],
        ], $groupedCollection['1,1']->model());
        $this->assertEquals([1, 3], $groupedCollection['1,1']->collection()->pluck('id')->all());

        $this->assertCount(1, $groupedCollection['2,2']);
        $this->assertEquals([
            (object) [
                'id' => '2',
            ],
            (object) [
                'id' => '2',
            ],
        ], $groupedCollection['2,2']->model());
        $this->assertEquals([2], $groupedCollection['2,2']->collection()->pluck('id')->all());
    }
}
