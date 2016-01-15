<?hh

namespace Hackavel\Middleware;

/**
 * Class Middleware
 */
class Middleware
{
    /** @var array  */
    protected $middleware = [];

    /**
     * append middleware
     * @param $middleware
     */
    public function append($middleware)
    {
        $this->middleware[] = $middleware;
    }

    /**
     * @return array
     */
    public function allMiddleware() : array
    {
        return $this->middleware;
    }
}
