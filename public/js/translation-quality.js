var data = {

};

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var app = new Vue({
    el: '#vueMe',
    data: function () {
        return data;
    },
    methods: {
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
        toggleSelection: function (base_string_id, event) {
            app.postRequest("/api/tools/mark-quality-controlled-string", {base_string_id}, function (response) {
                event.target.innerHTML = (response.quality_controlled == true) ? 'Deselect': 'Select';
            });
        },
    }
})