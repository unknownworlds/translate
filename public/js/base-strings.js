$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var app = new Vue({
    el: '#baseStrings',
    methods: {
        restoreTranslations: function (baseStringId) {
            let postData = {
                id: baseStringId,
            };

            $.post('/api/base-strings/restore-translations', postData, function (response) {
                alert(response);
            }).fail(function (jqXHR, textStatus, errorThrown) {
                alert('Request failed, try again');
            });
        },
    },
})