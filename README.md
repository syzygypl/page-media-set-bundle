# Page Media Set

This bundle provides an easy way to add a collection of media for each page type in [Kunstmaan Bundles CMS][kunstmaan].

The main goal is to have a common set of media attachments across many page types without the hassle of upgrading 
the db schema every time. For instance, think of adding a top page banner with the same dimensions to every page
without the need to add the relations themselves to the entities.

## Installation:

`composer require arsthanea/page-media-set-bundle`

After installation, add the `PageMediaSetBundle` to your kernel and update your db schema / create migration.

## Usage

There are three main steps:

 1. Implement the `HasMediaSetInterface` on your entities. This tells the bundle what types of media will be used for each page.
 2. Configure your media types in symfony configuration (see below)
 3. You now have an additional „Media Set” tab when editing the page where you can set the media
 
After setting them there are two ways of accessing them:

### Twig function

There is a simple helper function in twig templates:

```twig
{% block header %}
    {% set banner = page_media(page, "banner") %}
    {% if banner %}<img src="{{ banner }}" alt="page banner">{% endif %}
{% endblock %}
```

### Service in the container

For example in your controller:

```php
    /** @var HasMediaSetInterface $page */
    $mediaUrl = $this->get('page_media_set.page_media_set_service')->getPageMedia($page, "banner");
```


## Configuration

### Available formats

Add to your `config.yml` or similar:

```yml
page_media_set:
  formats:
    banner:
      min_width: 1920
      min_height: 420
      max_width: 1920
      max_height: 460
    teaser: ~
```

You need to configure all your media types, but the constraints are optional. 

### Predefined media set types

You can configure the media set definitions using symfony config, instead of returning them using the `getMediaSetDefinition` method.

```yaml
# app/config/config.yml
page_media_set:
  types:
    'Acme\Foo\Bar\BazEntity': [ 'foo', 'bar' ]
```

In this case `Acme\Foo\Bar\BazEntity::getMediaSetDefinition()` won’t be called, `foo` and `bar` formats will be used.

### Format names

Format names are taken from translations, from `messages` dictionary using `page_media_set.format.%s` keys. For instance:

```yml
# messages.yml
page_media_set:
  format:
    banner: Top page banner
```

### Indexer

If you’re using the search bundle, you may enable indexing page thumbnails. The first defined media will be stored in
the elasticsearch document under 'photo' key and then you can use it directly on the search results page.

```yml
# config.yml

page_media_set:
  indexer: true
```

  [kunstmaan]: https://bundles.kunstmaan.be/
