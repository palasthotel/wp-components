# WP Components

This composer library provides php class helpers for typical WordPress extensions. 

Find your component and copy past the PHP file into your plugins classes/Components and change its namespace to your plugins namespace. You should not use composer dependency management in plugins because you will might have several plugins that use it and there could be different versions of which only one will be loaded at runtime.

## Plugin

Extend the abstract class Plugin.

```php
class MyPlugin extends \Palasthotel\WordPress\Plugin {
    public function onCreate(){
        // you must implement onCreate function
        // this is where you build your plugin components and hooks
        
        // (optional) language path settings
        $this->loadTextdomain(
            "my-plugin-domain", // the text domain of your plugin
            "languages" // relative path in your plugin directory
        );
    }
    
    public function onActivation($networkWide){
        parent::onActivation($networkWide);
        // you can overwrite onActivation
        // better use onSiteActivation
    }
    
    public function onSiteActivation(){
        // you can overwrite onSiteActivation
        // this will be called for every site if plugin get activate network wide
        // this also works with single site setups
    }
    
    public function onDeactivation($networkWide){
        parent::onDeactivation($networkWide);
        // you can overwrite onDeactivation
        // better use onSiteDeactivation
    }
    
    public function onSiteDeactivation(){
        // you can overwrite onDeactivation function
        // this will be called for every site if plugin get deactivate network wide
        // this also works with single site setups
    }
}
MyPlugin::instance();
```
This Plugin class automatically has some properties like `path`, `url` and `basename` and holds the singleton instance.

## Component

Every subcomponent of the plugin that wants to have to plugin as a dependency.

```php
class MyComponent extends \Palasthotel\WordPress\Component {
    public function onCreate(){
        // $this->plugin is available here
    }
}
```

## Database

Use the abstract class Database.

```php
class MyDatabase extends \Palasthotel\WordPress\Database {
    public function init(){
        $this->table = $this->wpdb->prefix."my_table";
    }
    
    // implement query and manipulation functions
    
    public function createTables(){
        parent::createTables(); 
        dbDelta("CREATE TABLE QUERY");
    }
}
```

## Assets

Register javascript or style assets. This will automagically use dependency management via _script_.asset.php file if exists or use `filemtime` for proper versioning instead.

```php

add_action('wp_enqueue_scripts', function(){
    $assets = new \Palasthotel\WordPress\Assets($plugin);
    $assets->registerScript("my-script-handle","js/my-file.js");
    $assets->registerStyle("my-style-handle", "css/my-file.css");
    
    // call wp_enqueue_(script/style) directly or somewhere else
    wp_enqueue_script("my-script-handle");
    wp_enqueue_style("my-style-handle");
});
```

## Templates

Use Templates class to find templates.

```php
$templates = new \Palasthotel\WordPress\Templates($plugin);
$templates->useThemeDirectory("plugin-parts");
$templates->useAddTemplatePathsFilter("my_plugin_add_template_paths");

$path = $templates->get_template_path("my-template-file.php");

// will first search in theme (if provided with useThemeDirectory(...))
// then in custom paths provided by filter (useAddTemplatePathsFilter(...))
// them in default "templates" folder in the plugin

```

## Attachment Meta Field

Use these classes if you want to add some meta fields to attachments.

### TextMetaField

```php
$field = \Palasthotel\WordPress\Attachment\TextMetaField::build("my_meta_key")
->label("My label")
->help("Some helping hint");
```

The value will be saved to `my_meta_key` post meta. It can be accessed by with `$field->getValue($attachment_id);` and set with `$field->setValue($attachment_id, $value)`.

Saving and providing values can be customized.

```php
class MyStore implements \Palasthotel\WordPress\Service\StoreInterface {
    public function set($id,$value){
     // save the value however you like
    }
    public function get($id){
     // get the value and return it
    }
    public function delete($id){
      // delete value
    }
}
$field->useStore(new MyStore());
```

### TextareaMetaField

This class is working exactly like TextMetaField.

### SelectMetaField

```php
$field = \Palasthotel\WordPress\Attachment\SelectMetaField::build("my_meta_key")
->label("My label")
->help("Some helping hint")
->options([
    \Palasthotel\WordPress\Model\Option::build("","-nothing selected-"),
    \Palasthotel\WordPress\Model\Option::build("1","One"),
    \Palasthotel\WordPress\Model\Option::build("2","Two"),
]);
```

Getter, setter and useStore functions are working exactly like TextMetaField.

## TermMetaFields

Use these classes if you want to add some term meta fields.

### TermMetaInputField

```php
$taxonomies = ["category", "post_tag"];
$field = \Palasthotel\WordPress\Taxonomy\TermMetaInputField::build("my_meta_key", $taxonomies)
->label("My label")
->description("Some description")
->type("number");
```

The value will be saved to `my_meta_key` term meta. It can be accessed by with `$field->getValue($term_id);` and set with `$field->setValue($term_id, $value)`.

Saving and providing values can be customized.

```php
class MyStore implements \Palasthotel\WordPress\Service\StoreInterface {
    public function set($id,$value){
     // save the value however you like
    }
    public function get($id){
     // get the value and return it
    }
    public function delete($id){
      // delete value
    }
}
$field->useStore(new MyStore());
```

#### TermMetaCheckboxField

```php
$taxonomies = ["category", "post_tag"];
$field = \Palasthotel\WordPress\Taxonomy\TermMetaCheckboxField::build("my_meta_key",$taxonomies)
->label("My label")
->description("Some description")
->truthyValue("yes");
```

Getter, setter and useStore functions are working exactly like TermMetaInputField.

You can access checked boolean state with `$field->isChecked($term_id)`.

## Posts table

### Add Column

```php
$column = \Palasthotel\WordPress\PostsTable\Column::build("my_col_id");

// add a column label
$column->label("My Label");

// add a render function for column contents
$column->render(function($post_id){
    echo "<p>my column content</p>";
});

// add column after title column
$column->after("title");
// add your column before title column
$column->before("title");

// add column only for these post types
$column->postTypes(["my_post_type"]);

// or implement ProviderInterface to wait for filter be ready 
class MyPostTypesProvider implements \Palasthotel\WordPress\Service\ProviderInterface {
    public function get(){
        return apply_filters("my_post_types_filter", []);
    }
}
$column->postTypes(new MyPostTypesProvider());
```