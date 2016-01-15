<?hh

namespace Hackavel\Router;

/**
 * Class Route
 *
 * @package Basic\Router
 *
 * @method void get($route, $action)
 * @method void post($route, $action)
 * @method void put($route, $action)
 * @method void patch($route, $action)
 * @method void delete($route, $action)
 * @method void options($route, $action)
 */
class Route implements RouteInterface
{
    /** @var array  */
    protected $collection = [];

    /** @var array */
    protected $methods = [
        'GET',
        'POST',
        'PUT',
        'PATCH',
        'DELETE',
        'OPTIONS',
    ];

    /**
     * @return array
     */
    public function getCollection() : array
    {
        return $this->collection;
    }

    /**
     * @param $name
     * @param $arguments
     */
    public function __call($name, $arguments)
    {
        foreach ($this->methods as $method) {
            if ($method === strtoupper($name)) {
                $this->collection[] = [
                    $method, $arguments[0], $arguments[1]
                ];
            }
        }
    }
}
