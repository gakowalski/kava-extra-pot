# Cherry X Post Meta module

Allows to easily add new metaboxes to any post types.

## How to use:

1. Copy this module into your theme/plugin
2. Add path to `cherry-x-post-meta.php` file to `CX_Loader` initialization.
3. Initialize module on `after_setup_theme` hook with priority `0` or later, Example:

```php
add_action( 'after_setup_theme', 'twentyseventeen_meta_init', 0 );

function twentyseventeen_meta_init() {

	new Cherry_X_Post_Meta( array(
		'id'            => 'template-settings',
		'title'         => esc_html__( 'Template Settings', 'jet-woo-builder' ),
		'page'          => array( 'post', 'page' ),
		'context'       => 'normal',
		'priority'      => 'high',
		'callback_args' => false,
		'builder_cb'    => 'twentyseventeen_get_builder',
		'fields'        => array(
		'_sample_products' => array(
				'type'              => 'select',
				'element'           => 'control',
				'options'           => false,
				'options_callback'  => array( $this, 'get_products' ),
				'label'             => esc_html__( 'Sample Product for Editing (if not selected - will be used latest added)', 'jet-woo-builder' ),
				'sanitize_callback' => 'esc_attr',
			),
		),
	) );

}

function twentyseventeen_get_builder() {
  return new CX_Interface_Builder(
		array(
			'path' => get_theme_file_path( 'framework/modules/interface-builder/' ),
		  'url'  => get_theme_file_uri( 'framework/modules/interface-builder/' ),
		)
	);
}
```

## Arguments:
`Cherry_X_Post_Meta` accepts an array of options with next structure:

* `id`            - Metabox ID. Should be unique for each metabox
* `title`         - Similar to title attribute for add_meta_box function
* `page`          - Similar to page attribute for add_meta_box function
* `context`       - Similar to context attribute for add_meta_box function
* `priority`      - Similar to priority attribute for add_meta_box function
* `callback_args` - Similar to callback_args attribute for add_meta_box function
* `builder_cb`    - *REQUIRED* Call back function that returns new Interface builder instance. This argumnet is required to properly render meta fields.
* `fields`        - meta fields list. Format is similar to Inteface Builder fields format
* `admin_columns` - set of apropriate admin columns added to posts list.

## Notes:
admin_columns example:
```php
'admin_columns' => array(
    'thumbnail' => array(
        'label'    => __( 'Thumbnail', 'cherry-services' ),
        'callback' => array( $this, 'show_thumb' ),
        'position' => 1,
    ),
    'cherry-services-slogan' => array(
        'label' => __( 'Slogan', 'cherry-services' ),
    ),
),
```
Where:
* `label`    - Column title
* `callback` - Column cell content render callback. If not set - module tooks column key and try to get apropriate vlue from post meta with teh same key
* `position` - Column position
