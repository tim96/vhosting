
angular.module('app', ['ngRoute', 'ngSanitize', '7minWorkout']).
config(function ($routeProvider, $sceDelegateProvider) {
    $routeProvider.when('/start', {
        templateUrl: '/partials/start.html'
    });
    $routeProvider.when('/workout', {
        templateUrl: '/partials/workout.html',
        controller: 'WorkoutController'
    });
    $routeProvider.when('/finish', {
        templateUrl: '/partials/finish.html'
    });
    $routeProvider.otherwise({
        redirectTo: '/start'
    });
    $sceDelegateProvider.resourceUrlWhitelist([
        // Allow same origin resource loads.
        'self',
        'http://*.youtube.com/**'
    ]);
});

angular.module('7minWorkout', []);

