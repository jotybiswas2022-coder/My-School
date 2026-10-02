<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RouteIntegrityTest extends TestCase
{
    public function test_every_route_action_resolves_to_a_real_method(): void
    {
        $missing = [];

        foreach (Route::getRoutes() as $route) {
            $action = $route->getActionName();

            if (str_contains($action, '@')) {
                [$class, $method] = explode('@', $action);

                if (! class_exists($class) || ! method_exists($class, $method)) {
                    $missing[] = $route->methods()[0] . ' ' . $route->uri() . ' => ' . $action;
                }
            } elseif (str_contains($action, '::')) {
                [$class, $method] = explode('::', $action);

                if (! method_exists($class, $method)) {
                    $missing[] = $route->methods()[0] . ' ' . $route->uri() . ' => ' . $action;
                }
            }
        }

        $this->assertSame([], $missing, "Routes with missing controller methods:\n" . implode("\n", $missing));
    }

    public function test_all_named_routes_are_unique_and_named(): void
    {
        $names = collect(Route::getRoutes()->getRoutes())
            ->map(fn ($route) => $route->getName())
            ->filter()
            ->values();

        $this->assertSame($names->count(), $names->unique()->count(), 'Duplicate route names detected.');
    }
}
