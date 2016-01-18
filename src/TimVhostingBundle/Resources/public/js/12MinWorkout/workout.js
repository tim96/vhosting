
angular.module('7minWorkout').controller('WorkoutController',
    // example how to declare dependencies so that DI does not break after minification.
    ['$scope', '$interval', function($scope, $interval) {

        function WorkoutPlan(args) {
            this.exercises = [];
            this.name = args.name;
            this.title = args.title;
            this.restBetweenExercise = args.restBetweenExercise;
        }

        function Exercise(args) {
            this.name = args.name;
            this.title = args.title;
            this.description = args.description;
            this.image = args.image;
            this.related = {};
            this.related.videos = args.videos;
            this.nameSound = args.nameSound;
            this.procedure = args.procedure;
        }

        var restExercise;
        var workoutPlan;

        var init = function () {
            startWorkout();
        };

        var startWorkout = function() {
            workoutPlan = createWorkout();
            restExercise = {
                details: new Excercise({
                    name: "rest",
                    title: " Relax!",
                    description: " Relax a bit!",
                    image: "img/rest.png"
                }),
                duration: workoutPlan.restBetweenExcercise
            };
            startExercise(workoutPlan.exercises.shift());
        };

        var createWorkout = function () {
            var workout = new WorkoutPlan({
                name: "7minWorkout",
                title: "7 Minute Workout",
                restBetweenExercise: 10
            });

            workout.exercises.push({
                details: new Exercise({
                    name: 'jumpingJacks',
                    title: "Jumping Jacks",
                    description: "Jumping Jacks.",
                    image: "img/JumpingJacks.png",
                    videos: [],
                    variations: [],
                    procedure: ""
                }),
                duration: 30
            })
        };

        var startExercise = function (exercisePlan) {
            $scope.currentExercise = exercisePlan;
            $scope.currentExerciseDuration = 0;
            $interval(
                function () {
                    ++$scope.currentExerciseDuration;
                }, 1000, $scope.currentExercise.duration
            );
        };

        init();
    }]
);
