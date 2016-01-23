
angular.module('7minWorkout')
    .controller('WorkoutController',
    // example how to declare dependencies so that DI does not break after minification.
    ['$scope', '$interval', '$location', function($scope, $interval, $location) {

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

        var startWorkout = function() {
            workoutPlan = createWorkout();
            restExercise = {
                details: new Exercise({
                    name: "rest",
                    title: " Relax!",
                    description: " Relax a bit!",
                    image: "images/rest.png"
                }),
                duration: workoutPlan.restBetweenExercise
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
                    image: "images/JumpingJacks.png",
                    videos: ["//www.youtube.com/embed/dmYwZH_BNd0", "//www.youtube.com/embed/BABOdJ-2Z6o", "//www.youtube.com/embed/c4DAnQ6DtF8"],
                    variations: [],
                    procedure: "Assume an erect position, with feet together and arms at your side.\
                            Slightly bend your knees, and propel yourself a few inches into the air.\
                            While in air, bring your legs out to the side about shoulder width or slightly wider.\
                            As you are moving your legs outward, you should raise your arms up over your head; arms should be slightly bent throughout the entire in-air movement.\
                            Your feet should land shoulder width or wider as your hands meet above your head with arms slightly bent"
                }),
                duration: 20
            });

            workout.exercises.push({
                details: new Exercise({
                    name: "wallSit",
                    title: "Wall Sit",
                    description: "A wall sit, also known as a Roman Chair, is an exercise done to strengthen the quadriceps muscles.",
                    image: "images/wallsit.png",
                    videos: ["//www.youtube.com/embed/y-wV4Venusw", "//www.youtube.com/embed/MMV3v4ap4ro"],
                    procedure: "Place your back against a wall with your feet shoulder width apart and a little ways out from the wall.\
                              Then, keeping your back against the wall, lower your hips until your knees form right angles. "
                }),
                duration: 20
            });

            return workout;
        };

        var startExercise = function (exercisePlan) {
            $scope.currentExercise = exercisePlan;
            $scope.currentExerciseDuration = 0;
            $interval(
                function () {
                    ++$scope.currentExerciseDuration;
                }, 1000, $scope.currentExercise.duration
            ).then(function () {
                var next = getNextExercise(exercisePlan);
                if (next) {
                    startExercise(next);
                }
                else {
                    // console.log("Workout complete!")
                    $location.path('/finish');
                }
            });
        };

        var getNextExercise = function (currentExercisePlan) {
            var nextExercise = null;
            if (currentExercisePlan === restExercise) {
                nextExercise = workoutPlan.exercises.shift();
            } else {
                if (workoutPlan.exercises.length != 0) {
                    nextExercise = restExercise;
                }
            }
            return nextExercise;
        };

        // $scope.$watch('currentExerciseDuration', function (nVal) {
        //    if (nVal == $scope.currentExercise.duration) {
        //        var next = getNextExercise($scope.currentExercise);
        //        if (next) {
        //            startExercise(next);
        //        } else {
        //            console.log("Workout complete!")
        //        }
        //    }
        // });

        // $scope.$watch('obj', function(n,o) { console.log('Data changed!'); } );
        // These changes to $scope.obj will trigger the watch listener:
        // $scope.obj = {}; // Logs 'Data changed!'
        // $scope.obj = obj1; // Logs 'Data changed!'
        // $scope.obj = null; // Logs 'Data changed!'
        // Whereas these will not:
        // $scope.obj.prop1=value; // Does not log 'Data changed!'
        // $scope.obj.prop2={}; // Does not log 'Data changed!'
        // $scope.obj=$scope.obj; // Does not log 'Data changed!'
        // $scope.$watch('obj', function(n,o){console.log('Data changed!'},true);
        // All the previous changes will trigger the listener except the last one.

        var init = function () {
            startWorkout();
        };

        init();
    }]
);
