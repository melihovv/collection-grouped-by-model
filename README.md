# Collection Grouped By Model

[![Build Status](https://travis-ci.org/melihovv/collection-grouped-by-model.svg?branch=master)](https://travis-ci.org/melihovv/collection-grouped-by-model)
[![styleci](https://styleci.io/repos/CHANGEME/shield)](https://styleci.io/repos/CHANGEME)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/melihovv/collection-grouped-by-model/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/melihovv/collection-grouped-by-model/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/CHANGEME/mini.png)](https://insight.sensiolabs.com/projects/CHANGEME)

[![Packagist](https://img.shields.io/packagist/v/melihovv/collection-grouped-by-model.svg)](https://packagist.org/packages/melihovv/collection-grouped-by-model)
[![Packagist](https://poser.pugx.org/melihovv/collection-grouped-by-model/d/total.svg)](https://packagist.org/packages/melihovv/collection-grouped-by-model)
[![Packagist](https://img.shields.io/packagist/l/melihovv/collection-grouped-by-model.svg)](https://packagist.org/packages/melihovv/collection-grouped-by-model)

A collection grouped by model.

## Installation

Install via composer
```
composer require melihovv/collection-grouped-by-model
```

## Usage

```
$posts = Post::all()
$postsGroupedByAuthor = (new CollectionGroupedByModel($posts))
    ->groupByModel(function (Post $post) {
      return $post->author_id;
    }, function (Post $post) {
      return $post->author;
    });

foreach ($postsGroupedByAuthor as $authorPosts) {
  $authorPosts->model() // returns posts' author
  $authorPosts->collection() // returns author's posts
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
