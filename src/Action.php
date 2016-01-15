<?hh

namespace Hackavel;

use Hackavel\Middleware\Queueable;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Action
 */
class Action implements Queueable
{
    /** @var ContainerInterface  */
    protected $container;

    /**
     * Action constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $next
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ) {
        $handler = $request->getAttribute('action.handler');
        if ($handler instanceof \Closure) {
            return $handler($request, $response);
        }
        /** @var callable $invoke */
        $invoke = $this->container->get($handler);
        return $invoke($request, $response, $next);
    }
}
