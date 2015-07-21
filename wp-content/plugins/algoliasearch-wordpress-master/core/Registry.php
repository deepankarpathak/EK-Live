<?php namespace Algolia\Core;

class Registry
{
    private static $instance;
    private $options = array();
    private static $setting_key = 'algolia';

    private $attributes = array(
        'validCredential'               => false,
        'app_id'                        => '',
        'search_key'                    => '',
        'admin_key'                     => '',
        'index_name'                    => '',
        'indexable_types'               => array(
                                                'post' => array('name' => 'Articles', 'order' => 0),
                                                'page' => array('name' => 'Pages', 'order' => 1)
                                            ),
        'searchable'                    => array(
                                                'title'     => array('ordered' => 'ordered', 'order' => 0),
                                                'h1'        => array('ordered' => 'ordered', 'order' => 1),
                                                'h2'        => array('ordered' => 'ordered', 'order' => 2),
                                                'h3'        => array('ordered' => 'ordered', 'order' => 3),
                                                'h4'        => array('ordered' => 'ordered', 'order' => 4),
                                                'h5'        => array('ordered' => 'ordered', 'order' => 5),
                                                'h6'        => array('ordered' => 'ordered', 'order' => 6),
                                                'text'      => array('ordered' => 'unordered', 'order' => 7),
                                                'content'   => array('ordered' => 'unordered', 'order' => 8)
                                            ),
        'sortable'                      => array(),
        'type_of_search'                => array('autocomplete', 'instant'),
        'instant_jquery_selector'       => '#content',
        'extras'                        => array('author' => 'author', 'author_login' => 'author_login', 'permalink' => 'permalink', 'date' => 'date', 'content' => 'content', 'h1' => 'h1', 'h2' => 'h2', 'h3' => 'h3', 'h4' => 'h4', 'h5' => 'h5', 'h6' => 'h6', 'text' => 'text', 'title' => 'title', 'slug' => 'slug', 'modified' => 'modified', 'parent' => 'parent', 'menu_order' => 'menu_order', 'type' => 'type'),
        'metas'                         => array(),
        'number_by_page'                => 10,
        'number_by_type'                => 3,
        'search_input_selector'         => "[name='s']",
        'theme'                         => 'default',
        'last_update'                   => '',
        'enable_truncating'             => true,
        'truncate_size'                 => 9000
    );

    public static function getInstance()
    {
        if (! isset(static::$instance))
            static::$instance = new self();

        return static::$instance;
    }

    private function __construct()
    {
        $import_options = get_option(static::$setting_key);
        $this->options = $import_options == false ? array() : $import_options;
    }

    public function __get($name)
    {
        if (isset($this->attributes[$name]))
        {
            if (isset($this->options[$name]))
                return $this->options[$name];
            else
                return $this->attributes[$name];
        }

        throw new \Exception("Unknown attribute: ".$name);
    }

    public function __set($name, $value)
    {
        if (isset($this->attributes[$name]))
        {
            $this->options[$name] = $value;
            $this->save();
        }
        else
        {
            throw new \Exception("Unknown attribute: ".$name);
        }
    }

    private function save()
    {
        if (get_option(static::$setting_key) !== false)
            update_option(static::$setting_key, $this->options);
        else
            add_option(static::$setting_key, $this->options);
    }

    public function export()
    {
        return json_encode($this->options);
    }

    public function import($attributes)
    {
        if (is_array($attributes))
            foreach ($attributes as $key => $value)
                if (isset($this->attributes[$key]))
                    $this->options[$key] = $value;

        $this->save();
    }

    public function reset_config_to_default()
    {
       foreach ($this->attributes as $key => $value)
           if (in_array($key, array('validCredential', 'app_id', 'search_key', 'admin_key', 'index_name')) == false)
               $this->options[$key] = $value;

       $this->save();
    }
}