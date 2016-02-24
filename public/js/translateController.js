angular.module('translate', [])
    .controller('TranslateController', function ($scope, $http, $filter) {
        $scope.currentProject = null;
        $scope.currentLanguage = null;
        $scope.isAdmin = false;
        $scope.baseStrings = [];
        $scope.strings = [];
        $scope.filteredData = [];
        $scope.pagedData = [];
        $scope.translatedStringsHistory = [];
        $scope.topUsers = [];
        $scope.admins = [];
        $scope.loading = 0;
        $scope.acceptedStringsHidden = false;

        // Pagination
        $scope.currentPage = 0;
        $scope.pageSize = 100;
        $scope.numberOfPages = 0;

        $scope.loadTranslatedStrings = function () {
            $scope.loading++;
            $http.get('/api/strings?project_id=' + $scope.currentProject + '&language_id=' + $scope.currentLanguage).success(function (data, status, headers, config) {
                angular.forEach(data, function (string) {
                    $scope.strings[string.base_string_id].push(string);
                });
            }).error(function (data, status, headers, config) {
                alert('Error ' + status + ' occured. Please try again.')
            }).finally(function () {
                $scope.loading--;
            });
        }

        $scope.loadInvolvedUsers = function () {
            $scope.loading++;
            $http.get('/api/strings/users?project_id=' + $scope.currentProject + '&language_id=' + $scope.currentLanguage).success(function (data, status, headers, config) {
                $scope.topUsers = data
            }).error(function (data, status, headers, config) {
                alert('Error ' + status + ' occured. Please try again.')
            }).finally(function () {
                $scope.loading--;
            });
        }

        $scope.loadAdmins = function () {
            $scope.loading++;
            $http.get('/api/strings/admins?project_id=' + $scope.currentProject + '&language_id=' + $scope.currentLanguage).success(function (data, status, headers, config) {
                $scope.admins = data
            }).error(function (data, status, headers, config) {
                alert('Error ' + status + ' occured. Please try again.')
            }).finally(function () {
                $scope.loading--;
            });
        }

        $scope.checkPrivileges = function () {
            $scope.loading++;
            $http.get('/api/check-privileges?language_id=' + $scope.currentLanguage).success(function (data, status, headers, config) {
                $scope.isAdmin = data;
            }).error(function (data, status, headers, config) {
                alert('Error ' + status + ' occured. Please try again.')
            }).finally(function () {
                $scope.loading--;
            });
        }

        $scope.loadData = function () {
            $scope.loading++;
            $scope.currentProject = $('#project').val();
            $scope.currentLanguage = $('#language').val();

            $http.get('/api/base-strings?project_id=' + $scope.currentProject).success(function (data, status, headers, config) {
                $scope.baseStrings = data;

                angular.forEach(data, function (string) {
                    $scope.strings[string.id] = [];
                });

                $scope.pagedData = $scope.filteredData = $scope.baseStrings;
                $scope.numberOfPages = Math.ceil($scope.filteredData.length / $scope.pageSize);

                $scope.loadTranslatedStrings();
                $scope.loadInvolvedUsers();
                $scope.loadAdmins();
                $scope.checkPrivileges();
            }).error(function (data, status, headers, config) {
                alert('Error ' + status + ' occured. Please try again.')
            }).finally(function () {
                $scope.loading--;
            });
        }

        $scope.vote = function (base_string_id, string_id, vote) {
            $scope.loading++;
            var postData = {
                'string_id': string_id,
                'vote': vote
            };

            $http.post('/api/strings/vote', postData).success(function (data, status, headers, config) {
                angular.forEach($scope.strings[base_string_id], function (string, key) {
                    if (string.id == string_id) {
                        if (vote == 1)
                            !isNaN($scope.strings[base_string_id][key].up_votes) ? $scope.strings[base_string_id][key].up_votes++ : $scope.strings[base_string_id][key].up_votes = 1;
                        else
                            !isNaN($scope.strings[base_string_id][key].down_votes) ? $scope.strings[base_string_id][key].down_votes++ : $scope.strings[base_string_id][key].down_votes = 1;
                    }
                });
            }).error(function (data, status, headers, config) {
                alert('Error ' + status + ' occured. ' + data.error.message)
            }).finally(function () {
                $scope.loading--;
            });
        }

        $scope.trash = function (base_string_id, string_id) {
            $scope.loading++;
            var postData = {
                'string_id': string_id,
                'language_id': $scope.currentLanguage
            };

            $http.post('/api/strings/trash', postData).success(function (data, status, headers, config) {
                angular.forEach($scope.strings[base_string_id], function (string, key) {
                    if (string.id == string_id)
                        $scope.strings[base_string_id].splice(key, 1);
                });
            }).error(function (data, status, headers, config) {
                alert('Error ' + status + ' occured. Please try again.')
            }).finally(function () {
                $scope.loading--;
            });
        }

        $scope.accept = function (base_string_id, string_id) {
            $scope.loading++;
            var postData = {
                'string_id': string_id,
                'base_string_id': base_string_id,
                'language_id': $scope.currentLanguage
            };

            $http.post('/api/strings/accept', postData).success(function (data, status, headers, config) {
                angular.forEach($scope.strings[base_string_id], function (string, key) {
                    if (string.id == string_id)
                        $scope.strings[base_string_id][key].is_accepted = true;
                    else
                        $scope.strings[base_string_id][key].is_accepted = false;
                });
            }).error(function (data, status, headers, config) {
                alert('Error ' + status + ' occured. Please try again.')
            }).finally(function () {
                $scope.loading--;
            });
        }

        $scope.add = function (base_string_id) {
            $scope.loading++;
            var textInput = $('#stringInput' + base_string_id);

            var postData = {
                'project_id': $scope.currentProject,
                'language_id': $scope.currentLanguage,
                'base_string_id': base_string_id,
                'text': textInput.val()
            };

            $http.post('/api/strings/store', postData).success(function (data, status, headers, config) {
                $scope.strings[base_string_id].push(data);
                textInput.val('');
            }).error(function (data, status, headers, config) {
                alert('Error ' + status + ' occured. Please try again. (' + data.error.message + ')')
            }).finally(function () {
                $scope.loading--;
            });
        }

        $scope.showTranslationHistory = function (base_string_id) {
            $scope.loading++;
            $http.get('/api/strings/history?project_id=' + $scope.currentProject + '&language_id=' + $scope.currentLanguage + '&base_string_id=' + base_string_id).success(function (data, status, headers, config) {
                $scope.translatedStringsHistory = data;
                $('#translatedStringsHistory').modal();
            }).error(function (data, status, headers, config) {
                alert('Error ' + status + ' occured. Please try again.')
            }).finally(function () {
                $scope.loading--;
            });
        }

        $scope.hideAccepted = function () {
            if ($scope.acceptedStringsHidden) {
                $scope.filteredData = $scope.baseStrings;
                $scope.numberOfPages = Math.ceil($scope.filteredData.length / $scope.pageSize);
                $scope.setPage(0);
                $scope.acceptedStringsHidden = false;
            }
            else {
                angular.forEach($scope.baseStrings, function (baseString, key) {
                    $scope.baseStrings[key].is_translated = false;

                    angular.forEach($scope.strings[baseString.id], function (string) {
                        if ($scope.baseStrings[key].is_translated !== true)
                            $scope.baseStrings[key].is_translated = false;

                        if (string.is_accepted == true) {
                            $scope.baseStrings[key].is_translated = true;
                        }
                    });
                });

                $scope.filteredData = $filter('filter')($scope.baseStrings, {is_translated: false})
                $scope.numberOfPages = Math.ceil($scope.filteredData.length / $scope.pageSize);
                $scope.setPage(0);
                $scope.acceptedStringsHidden = true;
            }
        }

        $scope.range = function (start, end) {
            var ret = [];
            if (!end) {
                end = start;
                start = 0;
            }
            for (var i = start; i < end; i++) {
                ret.push(i);
            }
            return ret;
        };

        $scope.previousPage = function () {
            $scope.setPage($scope.currentPage - 1);
        };

        $scope.nextPage = function () {
            $scope.setPage($scope.currentPage + 1);
        };

        $scope.setPage = function (page) {
            $scope.currentPage = page;

            var start = ($scope.currentPage * $scope.pageSize);
            var end = start + $scope.pageSize;
            $scope.pagedData = $scope.filteredData.slice(start, end)
        };
    });
