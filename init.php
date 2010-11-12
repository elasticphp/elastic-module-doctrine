<?php

// ----
// These are the custom factories that return the doctrine proxy config
// ----
Container::instance()->set_factory('doctrine-config-proxydir', array('{{component:Config}}', 'get', true), array('key' => 'doctrine-proxydir'));
Container::instance()->set_factory('doctrine-config-proxynamespace', array('{{component:Config}}', 'get', true), array('key' => 'doctrine-proxynamespace'));

// ----
// These two calls set the proxy info (directory for storage and namespace for
// objects).
// ----
Container::instance()->set_postinit_action('doctrine_configuration#setproxydir',
  'Doctrine\ORM\Configuration',
  array('{{object}}', 'setProxyDir'),
  array('dir' => '{{component:doctrine-config-proxydir}}')
);
Container::instance()->set_postinit_action('doctrine_configuration#setproxynamespace',
  'Doctrine\ORM\Configuration',
  array('{{object}}', 'setProxyNamespace'),
  array('ns' => '{{component:doctrine-config-proxynamespace}}')
);

// ----
// These two calls set the cache implementations for the metadata driver and
// the query cache respectively.
// ----
Container::instance()->set_postinit_action('doctrine_configuration#setmetadatacacheimpl',
  'Doctrine\ORM\Configuration',
  array('{{object}}', 'setMetadataCacheImpl')
);
Container::instance()->set_postinit_action('doctrine_configuration#setquerycacheimpl',
  'Doctrine\ORM\Configuration',
  array('{{object}}', 'setQueryCacheImpl')
);

// ----
// The only reason this is being set explicitly is because the comments in
// Doctrine\ORM\Configuration.php#setMetadataDriverImpl say that in future the
// argument will be forced to be a Closure object and I don't know how they're
// going to go about that. It may change the type-hinting for the function,
// thus stopping us from automatically satisfying this depdendency.
// ----
Container::instance()->set_postinit_action('doctrine_configuration#setmetadatadriverimpl',
  'Doctrine\ORM\Configuration',
  array('{{object}}', 'setMetadataDriverImpl')
);

// ----
// This sets the default namespace for models to "Model"
// ----
Container::instance()->set_postinit_action('doctrine_annotationreader#setdefaultannotationnamespace',
  'Doctrine\Common\Annotations\AnnotationReader',
  array('{{object}}', 'setDefaultAnnotationNamespace'),
  array('defaultnamespace' => 'Doctrine\ORM\Mapping\\')
);

// ----
// This sets the cache implementation to "ArrayCache".
// You may want to change this to APCCache or something in production.
// ----
Container::instance()->set_implementation('Doctrine\Common\Cache\Cache', 'Doctrine\Common\Cache\ArrayCache');

// ----
// This sets the metadata driver implementation to "AnnotationDriver"
// ----
Container::instance()->set_implementation('Doctrine\ORM\Mapping\Driver\Driver', 'Doctrine\ORM\Mapping\Driver\AnnotationDriver');

// ----
// This is the custom factory that returns the doctrine config
// ----
Container::instance()->set_factory('doctrine-config-connection', array('{{component:Config}}', 'get', true), array('key' => 'doctrine-connection'));

// ----
// Here's the custom factory for the doctrine object.
// ----
Container::instance()->set_factory('doctrine', array('Doctrine\ORM\EntityManager', 'create'), array('conn' => '{{component:doctrine-config-connection}}'));

?>
