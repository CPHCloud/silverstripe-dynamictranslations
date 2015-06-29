# silverstripe-dynamictranslations
Dynamic Translations module for SilverStripe.

## How it works
Replace the strings you want to translate with the following syntax:

```
$dt('SearchBar.Submit', 'Submit search')
```

The next time the template is loaded, the module will search for an entity in the database named `SearchBar.Submit`. If it can’t find one, it will create it (with the second argument as the default string) and save it. It will then store this data in a cache ready for the next request that uses this translation.

Translations can be added and edited in the CMS, as well as grouped into categories for easier management by CMS users. The list of categories can be managed in the CMS, and default categories can be created on `dev/build` by adding categories to your YAML configuration:, e.g.:

```yml
DynamicTranslationCategory:
  default_categories:
    - Header
    - Footer
    - Search
```

Note that the default categories will only be created if no categories already exist in the database.
