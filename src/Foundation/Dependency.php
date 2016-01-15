<?hh

namespace Steam\Foundation;

use Hackavel\Router\Router;
use Hackavel\Router\RouteInterface;
use Psr\Http\Message\ResponseInterface;
use Interop\Container\ContainerInterface;

/**
 * Class Dependency
 */
class Dependency
{
    /** @var ContainerInterface */
    protected $container;

    /**
     * Dependency constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param RouteInterface $route
     */
    public function dependencyRoutes(RouteInterface $route)
    {
        $this->container->add(RouteInterface::class, $route);
    }

    /**
     *
     */
    public function register()
    {
        $this->container->add(ResponseInterface::class, 'Zend\Diactoros\Response');
        $this->container->add(Router::class, function () {
            return new Router($this->container->get(RouteInterface::class));
        });
    }
}
