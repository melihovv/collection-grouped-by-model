# Collection Grouped By Model

[![GitHub Workflow Status](https://github.com/melihovv/collection-grouped-by-model/workflows/Run%20tests/badge.svg)](https://github.com/melihovv/collection-grouped-by-model/actions)
[![styleci](https://styleci.io/repos/107822001/shield)](https://styleci.io/repos/107822001)

[![Packagist](https://img.shields.io/packagist/v/melihovv/collection-grouped-by-model.svg)](https://packagist.org/packages/melihovv/collection-grouped-by-model)
[![Packagist](https://poser.pugx.org/melihovv/collection-grouped-by-model/d/total.svg)](https://packagist.org/packages/melihovv/collection-grouped-by-model)
[![Packagist](https://img.shields.io/packagist/l/melihovv/collection-grouped-by-model.svg)](https://packagist.org/packages/melihovv/collection-grouped-by-model)

This package allows easily group laravel collection by any php object (model, array/collection of models, etc), not only by scalars (strings, numbers, booleans).

## Installation

Install via composer
```
composer require melihovv/collection-grouped-by-model
```

## Usage

```php
$posts = Post::all()
$postsGroupedByAuthor = (new CollectionGroupedByModel($posts))->groupByModel('author_id', 'author');

foreach ($postsGroupedByAuthor as $authorPosts) {
  $authorPosts->model(); // returns posts' author
  $authorPosts->collection(); // returns author's posts
}
```

### Nested grouping: first group products by category, then by manufacturer.

```php
$products = Product::all();
$groupedProducts = (new CollectionGroupedByModel($products))
    ->groupByModel('category_id', 'category')
    ->transform(function (CollectionGroupedByModel $productsGroupedByCategory) {
      return $productsGroupedByCategory->groupByModel('manufacturer_id', 'manufacturer');
    });

foreach ($groupedProducts as $categoryProducts) {
  $categoryProducts->model(); // returns category

  foreach ($categoryProducts->collection() as $manufacturerProducts) {
    $manufacturerProducts->model(); // returns manufacturer
    $manufacturerProducts->collection(); // returns products grouped by category and manufacturer
  }
}
```

### Group by several models

```php
$posts = Post::all()
$postsGroupedByAuthorAndCategory = (new CollectionGroupedByModel($posts))
    ->groupByModel(function (Post $post) {
      return "$post->author_id,$post->category_id";
    }, function (Post $post) {
      return [$post->author, $post->category];
    });

foreach ($postsGroupedByAuthorAndCategory as $authorPostsInCategory) {
  list($author, $category) = $authorPostsWithCategory->model();
  // or using php 7.1 array destruction
  [$author, $category] = $authorPostsWithCategory->model();
  $authorPostsWithCategory->collection(); // returns author's posts in category
}
```

## Security

If you discover any security related issues, please email amelihovv@ya.ru
instead of using the issue tracker.

## Credits

- [Alexander Melihov](https://github.com/melihovv/collection-grouped-by-model)
- [All contributors](https://github.com/melihovv/collection-grouped-by-model/graphs/contributors)

This package is bootstrapped with the help of
[melihovv/laravel-package-generator](https://github.com/melihovv/laravel-package-generator).
