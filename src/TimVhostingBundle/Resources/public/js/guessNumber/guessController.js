
function GuessTheNumberController($scope) {

    $scope.verifyGuess = function () {
        $scope.deviation = $scope.original - $scope.guess;
        $scope.noOfTries = $scope.noOfTries + 1;
    };

    $scope.initializeGame = function () {
        $scope.noOfTries = 0;
        $scope.original = Math.floor((Math.random() * 1000) + 1);
        $scope.guess = null;
        $scope.deviation = null;
    };

    $scope.isDeviationLessThanZero = function () {
        return $scope.deviation < 0;
    }

    $scope.isDeviationMoreThanZero = function () {
        return $scope.deviation > 0;
    }

    $scope.isDeviationEqualZero = function () {
        return $scope.deviation === 0;
    }

    $scope.initializeGame();
}