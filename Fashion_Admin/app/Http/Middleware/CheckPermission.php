<?php

namespace App\Http\Middleware;

use Closure;
use ReflectionClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use FashionCore\Interfaces\IUserRepository;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    protected $userRepo;

    public function __construct(IUserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }


    public function handle(Request $request, Closure $next): Response
    {
        $route = $request->route();
        $action = $route->getAction();

        // Lấy controller và phương thức từ route
        $controller = $action['controller'];
        list($controller, $method) = explode('@', $controller);
        $permissionKey = $this->getPermissionKeyForMethod($controller, $method);

        if ($permissionKey && !$this->userRepo->checkUserPermission($permissionKey)) {
            abort(403, 'Unauthorized action.');
        }
        return $next($request);
    }

    protected function getPermissionKeyForMethod($controller, $method)
    {
        // lấy controller và phương thức ra để xử lý
        $reflection = new ReflectionClass($controller);
        $methodReflection = $reflection->getMethod($method);
        $docComment = $methodReflection->getDocComment();

        // tìm kiếm, nó sẽ tìm kiếm các phương thức dựa theo key mà đặt trong comment /** */
        if (preg_match('/@permission\s+(\w+)/', $docComment, $matches)) {
            return $matches[1];
        }


        return null;
    }
}
