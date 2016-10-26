var data = {
    currentProject: null,
    currentLanguage: null,
    projectHandlers: [],
    isAdmin: false,
    isRoot: false,
    baseStrings: [],
    strings: [],
    filteredData: [],
    pagedData: [],
    translatedStringsHistory: [],
    topUsers: [],
    admins: [],
    loading: 0,
    acceptedStringsHidden: false,
    showingPendingOnly: false,
    currentPage: 0,
    pageSize: 100,
    numberOfPages: 0,
    whiteboard: {}
};

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var app = new Vue({
    el: '#translate',
    data: function () {
        return data;
    },
    methods: {
        getRequest: function (url, params, success) {
            data.loading++;

            $.get(url, params, success).fail(function (jqXHR, textStatus, errorThrown) {
                alert('Error ' + jqXHR.status + ' occurred. Please try again.')
            }).always(function () {
                data.loading--;
            });
        },
        postRequest: function (url, params, success) {
            data.loading++;

            $.post(url, params, success).fail(function (jqXHR, textStatus, errorThrown) {
                alert('Error ' + jqXHR.status + ' occurred. Please try again.')
            }).always(function () {
                data.loading--;
            });
        },
        loadData: function () {
            data.currentProject = $('#project').val();
            data.currentLanguage = $('#language').val();

            app.getRequest("api/base-strings", {project_id: data.currentProject}, function (response) {
                data.baseStrings = response;

                Object.keys(response).forEach(function (key) {
                    data.strings[response[key].id] = [];
                });

                data.pagedData = data.filteredData = data.baseStrings;
                // app.resetPagination();

                app.loadTranslatedStrings();
                app.loadInvolvedUsers();
                app.loadAdmins();
                app.checkPrivileges();
                app.checkProjectHandlers();
                app.loadWhiteboard();
            });
        },
        resetPagination: function () {
            data.numberOfPages = Math.ceil(data.filteredData.length / data.pageSize);
            data.setPage(0);
        },
        loadTranslatedStrings: function () {
            app.getRequest("api/strings", {
                project_id: data.currentProject,
                language_id: data.currentLanguage
            }, function (response) {
                Object.keys(response).forEach(function (key) {
                    data.strings[response[key].base_string_id].push(response[key]);
                });
            });
        },
        loadInvolvedUsers: function () {
            app.getRequest("api/strings/users", {
                project_id: data.currentProject,
                language_id: data.currentLanguage
            }, function (response) {
                data.topUsers = response
            });
        },
        loadAdmins: function () {
            app.getRequest("api/strings/admins", {
                project_id: data.currentProject,
                language_id: data.currentLanguage
            }, function (response) {
                data.admins = response
            });
        },
        checkPrivileges: function () {
            app.getRequest("api/check-privileges", {language_id: data.currentLanguage}, function (response) {
                data.isAdmin = response.is_admin;
                data.isRoot = response.is_root;
            });
        },
        checkProjectHandlers: function () {
            app.getRequest("api/project-handlers", {language_id: data.currentLanguage}, function (response) {
                data.projectHandlers = response;
            });
        },
        loadWhiteboard: function () {
            app.getRequest("api/admin-whiteboard/" + data.currentProject + '/' + data.currentLanguage, {}, function (response) {
                data.whiteboard = response;
            });
        },
        saveWhiteboard: function () {
            var postData = {
                project_id: data.currentProject,
                language_id: data.currentLanguage,
                text: data.whiteboard.text
            };

            app.postRequest("api/admin-whiteboard", postData, function (response) {
                app.loadWhiteboard();
            });
        },
        /*
         *
         *  TODO: MOVE ELSEWHERE! Separate component or something
         *
         */
        vote: function (base_string_id, string_id, vote) {
            data.loading++;
            var postData = {
                'string_id': string_id,
                'vote': vote
            };

            $http.post('/api/strings/vote', postData).success(function (data, status, headers, config) {
                angular.forEach(data.strings[base_string_id], function (string, key) {
                    if (string.id == string_id) {
                        if (vote == 1)
                            !isNaN(data.strings[base_string_id][key].up_votes) ? data.strings[base_string_id][key].up_votes++ : data.strings[base_string_id][key].up_votes = 1;
                        else
                            !isNaN(data.strings[base_string_id][key].down_votes) ? data.strings[base_string_id][key].down_votes++ : data.strings[base_string_id][key].down_votes = 1;
                    }
                });
            }).error(function (data, status, headers, config) {
                alert('Error ' + status + ' occured. ' + data.error.message)
            }).finally(function () {
                data.loading--;
            });
        },
        trash: function (base_string_id, string_id) {
            data.loading++;
            var postData = {
                'string_id': string_id,
                'language_id': data.currentLanguage
            };

            $http.post('/api/strings/trash', postData).success(function (data, status, headers, config) {
                angular.forEach(data.strings[base_string_id], function (string, key) {
                    if (string.id == string_id)
                        data.strings[base_string_id].splice(key, 1);
                });
            }).error(function (data, status, headers, config) {
                alert('Error ' + status + ' occured. Please try again.')
            }).finally(function () {
                data.loading--;
            });
        },
        trashBaseString: function (base_string) {
            data.loading++;

            var postData = {
                id: base_string.id
            };

            $http.post('/api/base-strings/trash', postData).success(function (data, status, headers, config) {
                angular.forEach(data.baseStrings, function (string, key) {
                    if (string.id == base_string.id) {
                        data.baseStrings.splice(key, 3);
                    }
                });
            }).error(function (data, status, headers, config) {
                alert('Error ' + status + ' occured. Please try again.')
            }).finally(function () {
                data.loading--;
                data.resetPagination();
            });
        },
        accept: function (base_string_id, string_id) {
            data.loading++;
            var postData = {
                'string_id': string_id,
                'base_string_id': base_string_id,
                'language_id': data.currentLanguage
            };

            $http.post('/api/strings/accept', postData).success(function (data, status, headers, config) {
                angular.forEach(data.strings[base_string_id], function (string, key) {
                    if (string.id == string_id)
                        data.strings[base_string_id][key].is_accepted = true;
                    else
                        data.strings[base_string_id][key].is_accepted = false;
                });
            }).error(function (data, status, headers, config) {
                alert('Error ' + status + ' occured. Please try again.')
            }).finally(function () {
                data.loading--;
            });
        },
        add: function (base_string_id) {
            data.loading++;
            var textInput = $('#stringInput' + base_string_id);

            var postData = {
                'project_id': data.currentProject,
                'language_id': data.currentLanguage,
                'base_string_id': base_string_id,
                'text': textInput.val()
            };

            $http.post('/api/strings/store', postData).success(function (data, status, headers, config) {
                data.strings[base_string_id].push(data);
                textInput.val('');
            }).error(function (data, status, headers, config) {
                alert('Error ' + status + ' occured. Please try again. (' + data.error.message + ')')
            }).finally(function () {
                data.loading--;
            });
        },
        showTranslationHistory: function (base_string_id) {
            data.loading++;
            $http.get('/api/strings/history?project_id=' + data.currentProject + '&language_id=' + data.currentLanguage + '&base_string_id=' + base_string_id).success(function (data, status, headers, config) {
                data.translatedStringsHistory = data;
                $('#translatedStringsHistory').modal();
            }).error(function (data, status, headers, config) {
                alert('Error ' + status + ' occured. Please try again.')
            }).finally(function () {
                data.loading--;
            });
        },
        showNewBaseStringForm: function () {
            data.manualInputBaseString = {};
            $('#baseStringEditModal').modal();
        },
        editBaseString: function (base_string) {
            data.manualInputBaseString = base_string;
            $('#baseStringEditModal').modal();
        },
        saveBaseString: function (base_string_id) {
            data.loading++;

            var postData = {
                id: base_string_id,
                project_id: data.currentProject,
                key: data.manualInputBaseString.key,
                text: data.manualInputBaseString.text
            };

            $http.post('/api/base-strings', postData).success(function (data, status, headers, config) {
                if (typeof base_string_id == 'undefined') {
                    data.baseStrings.push(data);
                    data.strings[data.id] = [];
                    data.resetPagination();
                }
            }).error(function (data, status, headers, config) {
                alert('Error ' + status + ' occured. ' + data.error.message)
            }).finally(function () {
                data.loading--;
                $('#baseStringEditModal').modal('hide');
            });
        },
        hideAccepted: function () {
            if (data.acceptedStringsHidden) {
                // data.filteredData = data.baseStrings;
                // data.resetPagination();
                data.acceptedStringsHidden = false;
            }
            else {
                // data.assignTagsToBaseStrings();
                //
                // data.filteredData = $filter('filter')(data.baseStrings, {is_translated: false})
                // data.resetPagination();
                data.acceptedStringsHidden = true;
                data.showingPendingOnly = false;
            }
        },
        showPendingOnly: function () {
            if (data.showingPendingOnly) {
                // data.filteredData = data.baseStrings;
                // data.resetPagination();
                data.showingPendingOnly = false;
            }
            else {
                // data.assignTagsToBaseStrings();

                // data.filteredData = $filter('filter')(data.baseStrings, {
                //     translation_pending: true,
                //     is_translated: false
                // })
                // data.resetPagination();
                data.showingPendingOnly = true;
                data.acceptedStringsHidden = false;
            }
        },
        // assignTagsToBaseStrings: function () {
        //     angular.forEach(data.baseStrings, function (baseString, key) {
        //         data.baseStrings[key].is_translated = false;
        //
        //         angular.forEach(data.strings[baseString.id], function (string) {
        //             if (data.baseStrings[key].is_translated !== true)
        //                 data.baseStrings[key].is_translated = false;
        //
        //             if (string.is_accepted == true) {
        //                 data.baseStrings[key].is_translated = true;
        //             }
        //         });
        //
        //         if (data.strings[baseString.id].length > 0)
        //             data.baseStrings[key].translation_pending = true;
        //         else
        //             data.baseStrings[key].translation_pending = false;
        //     });
        // },
        range: function (start, end) {
            var ret = [];
            if (!end) {
                end = start;
                start = 0;
            }
            for (var i = start; i < end; i++) {
                ret.push(i);
            }
            return ret;
        },
        previousPage: function () {
            if (data.currentPage - 1 >= 0)
                data.setPage(data.currentPage - 1);
        },
        nextPage: function () {
            if (data.currentPage + 1 < data.numberOfPages)
                data.setPage(data.currentPage + 1);
        },
        setPage: function (page) {
            data.currentPage = page;

            var start = (data.currentPage * data.pageSize);
            var end = start + data.pageSize;
            data.pagedData = data.filteredData.slice(start, end)
        }
        // data.$watch('searchInput', function (val) {
        //     data.filteredData = $filter('filter')(data.baseStrings, {key: val} && {text: val});
        //     data.resetPagination();
        //     data.showingPendingOnly = false;
        //     data.acceptedStringsHidden = false;
        // })
    }
})