<?hh

namespace Hackavel\Router;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Router
 */
class Router
{
    /** @var RouteInterface */
    protected $collection;

    /**
     * Router constructor.
     *
     * @param RouteInterface $collection
     */
    public function __construct(RouteInterface $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $next
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $route = $this->dispatchRoutes()->dispatch($request->getMethod(), $request->getUri()->getPath());
        switch ($route[0]) {
            case Dispatcher::NOT_FOUND:
                // ... 404 Not Found
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $route[1];
                // ... 405 Method Not Allowed
                break;
            case Dispatcher::FOUND:
                return $next($request->withAttribute('action.handler', $route[1]), $response);
                break;
        }
    }

    /**
     * @return Dispatcher
     */
    protected function dispatchRoutes() : Dispatcher
    {
        /** @var  $dispatcher */
        return \FastRoute\simpleDispatcher(function (RouteCollector $route) {
            $collections = $this->collection->getCollection();
            foreach ($collections as $collect) {
                list($method, $uri, $action) = $collect;
                $route->addRoute($method, $uri, $action);
            }
        });
    }
}
