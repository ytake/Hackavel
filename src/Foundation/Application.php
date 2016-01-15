<?hh

namespace Hackavel\Foundation;

use Hackavel\Action;
use Hackavel\Router\Route;
use Relay\RelayBuilder;
use League\Container\Container;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Application
 */
class Application
{
    /** @var string */
    protected $path;

    /** @var string */
    protected $dependencyConfiguration = '/config/dependencies.php';

    /** @var ContainerInterface|null */
    protected $container;

    /** @var ServerRequestInterface */
    protected $request;

    /** @var Route */
    protected $route;

    /**
     * Application constructor.
     *
     * @param string                  $path
     * @param ContainerInterface|null $container
     */
    public function __construct($path = '', ContainerInterface $container = null)
    {
        $this->path = $path;
        $this->container = (!$container) ? new Container : $container;
    }

    /**
     * application dependency
     */
    protected function setDependencies()
    {
        /** @var ContainerInterface $container */
        $applicationDependency = require_once $this->path . $this->dependencyConfiguration;
        foreach ($applicationDependency['factories'] as $abstract => $factory) {
            $this->container->add($abstract, $factory);
        }
    }

    /**
     * @param RelayBuilder $relay
     *
     * @return $this
     */
    public function buildApplication(RelayBuilder $relay)
    {
        $this->registerCoreDependencies();
        $this->setDependencies();
        $callable[] = $this->container->get('Basic\Router\Router');
        $callable[] = new Action($this->container);
        $next = $relay->newInstance($callable);

        return $next(
            $this->container->get(ServerRequestInterface::class),
            $this->container->get('Psr\Http\Message\ResponseInterface')
        );
    }

    /**
     * application core bindings
     *
     */
    protected function registerCoreDependencies()
    {
        $dependency = new Dependency($this->container);
        $dependency->dependencyRoutes($this->route);
        $dependency->register();
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return Application
     */
    public function setRequest(ServerRequestInterface $request) : Application
    {
        $this->request = $request;
        $this->container->add(ServerRequestInterface::class, $request);

        return $this;
    }

    /**
     * @param callable $closure
     *
     * @return Application
     */
    public function routeRegister(callable $closure) : Application
    {
        $this->route = new Route;
        call_user_func($closure, $this->route);

        return $this;
    }
}
