<?php
namespace Gta\AdminBundle\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

class AdminLoader extends Loader
{
    public function load($resource, $type = null)
    {
        $collection = new RouteCollection();

        $resource = '@GtaAdminBundle/Resources/config/routing.yml';
        $type = 'yaml';

        $importedRoutes = $this->import($resource, $type);

        $collection->addCollection($importedRoutes);
        $collection->addPrefix('/admin');
//        $collection->addRequirements(['appcod' => 'gar']);

        return $collection;
    }

    public function supports($resource, $type = null)
    {
        return 'admin' === $type;
    }
}