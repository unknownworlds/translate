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
    whiteboard: {},
    searchInput: '',
    manualInputBaseString: {},
    editStringModalData: {}
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
                if (typeof jqXHR.responseJSON == 'undefined')
                    alert('Error ' + jqXHR.status + ' occurred. Please try again.');
                else
                    alert(jqXHR.responseJSON.error.message);
            }).always(function () {
                data.loading--;
            });
        },
        postRequest: function (url, params, success) {
            data.loading++;

            $.post(url, params, success).fail(function (jqXHR, textStatus, errorThrown) {
                if (typeof jqXHR.responseJSON == 'undefined')
                    alert('Error ' + jqXHR.status + ' occurred. Please try again.');
                else
                    alert(jqXHR.responseJSON.error.message);
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
                app.resetPagination();

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
            this.setPage(0);
        },
        previousPage: function () {
            if (data.currentPage - 1 >= 0)
                this.setPage(data.currentPage - 1);
        },
        nextPage: function () {
            if (data.currentPage + 1 < data.numberOfPages)
                this.setPage(data.currentPage + 1);
        },
        setPage: function (page) {
            data.currentPage = page;

            var start = (data.currentPage * data.pageSize);
            var end = start + data.pageSize;
            data.pagedData = data.filteredData.slice(start, end);
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
        hideAccepted: function () {
            if (data.acceptedStringsHidden) {
                data.filteredData = data.baseStrings;
                this.resetPagination();
                data.acceptedStringsHidden = false;
            }
            else {
                data.filteredData = data.baseStrings.filter(function (item) {
                    var result = true;

                    Object.keys(data.strings[item.id]).forEach(function (key) {
                        if (data.strings[item.id][key].is_accepted == true) {
                            result = false;
                        }
                    })

                    return result;
                });

                this.resetPagination();
                data.acceptedStringsHidden = true;
                data.showingPendingOnly = false;
            }
        },
        showPendingOnly: function () {
            if (data.showingPendingOnly) {
                data.filteredData = data.baseStrings;
                this.resetPagination();
                data.showingPendingOnly = false;
            }
            else {
                data.filteredData = data.baseStrings.filter(function (item) {
                    var hasTranslations = false
                    var is_accepted = false;

                    Object.keys(data.strings[item.id]).forEach(function (key) {
                        if (data.strings[item.id][key].is_accepted == true) {
                            is_accepted = true;
                        }

                        hasTranslations = true;
                    })

                    return !is_accepted && hasTranslations;
                });

                this.resetPagination();
                data.showingPendingOnly = true;
                data.acceptedStringsHidden = false;
            }
        },
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
        /*
         *
         *  TODO: MOVE ELSEWHERE! Separate component or something
         *
         */
        vote: function (base_string_id, string_id, vote) {
            var postData = {
                'string_id': string_id,
                'vote': vote
            };

            app.postRequest("api/strings/vote", postData, function (response) {
                Object.keys(data.strings[base_string_id]).forEach(function (key) {
                    if (data.strings[base_string_id][key].id == string_id) {
                        if (vote == 1)
                            !isNaN(data.strings[base_string_id][key].up_votes) ? data.strings[base_string_id][key].up_votes++ : data.strings[base_string_id][key].up_votes = 1;
                        else
                            !isNaN(data.strings[base_string_id][key].down_votes) ? data.strings[base_string_id][key].down_votes++ : data.strings[base_string_id][key].down_votes = 1;
                    }
                });
            });
        },
        trash: function (base_string_id, string_id) {
            var postData = {
                'string_id': string_id,
                'language_id': data.currentLanguage
            };

            app.postRequest("api/strings/trash", postData, function (response) {
                var index = -1;
                Object.keys(data.strings[base_string_id]).forEach(function (key) {
                    if (data.strings[base_string_id][key].id == string_id) {
                        index = key;
                    }
                });

                data.strings[base_string_id].splice(index, 1);
            });
        },
        trashBaseString: function (base_string) {
            var postData = {
                id: base_string.id
            };

            app.postRequest("api/base-strings/trash", postData, function (response) {
                var index = -1;
                Object.keys(data.baseStrings).forEach(function (key) {
                    if (data.baseStrings[key].id == base_string.id) {
                        index = key;
                    }
                });

                data.baseStrings.splice(index, 1);
                app.resetPagination();
            });
        },
        accept: function (base_string_id, string_id) {
            var postData = {
                'string_id': string_id,
                'base_string_id': base_string_id,
                'language_id': data.currentLanguage
            };

            app.postRequest("api/strings/accept", postData, function (response) {
                Object.keys(data.strings[base_string_id]).forEach(function (key) {
                    if (data.strings[base_string_id][key].id == string_id)
                        data.strings[base_string_id][key].is_accepted = true;
                    else
                        data.strings[base_string_id][key].is_accepted = false;
                });
            });
        },
        add: function (base_string_id) {
            var textInput = $('#stringInput' + base_string_id);

            var postData = {
                'project_id': data.currentProject,
                'language_id': data.currentLanguage,
                'base_string_id': base_string_id,
                'text': textInput.val()
            };

            app.postRequest("api/strings/store", postData, function (response) {
                data.strings[base_string_id].push(response);
                textInput.val('');
            });
        },
        showTranslationHistory: function (base_string_id) {
            var getData = {
                project_id: data.currentProject,
                language_id: data.currentLanguage,
                base_string_id: base_string_id
            };

            app.getRequest("api/strings/history", getData, function (response) {
                data.translatedStringsHistory = response;
                $('#translatedStringsHistory').modal();
            });
        },
        edit: function (string) {
            data.editStringModalData = string;
            $('#stringEditModal').modal();
        },
        saveString: function (string) {
            var postData = {
                string_id: string.id,
                text: string.text
            };

            app.postRequest("api/strings/update", postData, function (response) {
                $('#stringEditModal').modal('hide');
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
            // TODO make it work properly
            var postData = {
                id: base_string_id,
                project_id: data.currentProject,
                key: data.manualInputBaseString.key,
                text: data.manualInputBaseString.text
            };

            app.postRequest("api/base-strings", postData, function (response) {
                if (typeof base_string_id == 'undefined') {
                    data.baseStrings.push(response);
                    data.strings[response.id] = [];
                    app.resetPagination();
                }

                $('#baseStringEditModal').modal('hide');
            });
        }
    },
    watch: {
        searchInput: function (val) {
            data.filteredData = data.baseStrings.filter(function (item) {
                // Search in translated strings
                var translatedTextContainsQuery = false;
                Object.keys(data.strings[item.id]).forEach(function (key) {
                    console.log(data.strings[item.id][key].text);
                    if (data.strings[item.id][key].text.toLowerCase().indexOf(val.toLowerCase()) !== -1) {
                        translatedTextContainsQuery = true;
                    }
                })

                // Search in keys and values, also return true if translated string contains query
                return item.key.toLowerCase().indexOf(val.toLowerCase()) !== -1
                    || item.text.toLowerCase().indexOf(val.toLowerCase()) !== -1
                    || translatedTextContainsQuery;
            });

            this.resetPagination();
            data.showingPendingOnly = false;
            data.acceptedStringsHidden = false;
        },
    }
})