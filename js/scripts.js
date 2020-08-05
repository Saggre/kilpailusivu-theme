(function ($, root, undefined) {

    $(function () {

        'use strict';

        getEntries(8, 0);

        $('#participation-form input[name="is-ajax"]').val("1");

        $('#participation-form').ajaxForm({
            dataType: 'json',
            success: function (data, textStatus, jqXHR, $form) {
                console.log(data);
                console.log($form);

                if (!data) {
                    $('.participation-form-error').text('Tapahtui tuntematon virhe. Yritä uudelleen');
                } else if (data.error) {
                    $('.participation-form-error').text(data.error_msg);
                } else {
                    $('.participation-form-error').text('');
                    // TODO update image on site frontend
                }
            }
        });

        function getEntries(number, offset) {

            $.ajax({
                type: "POST",
                url: ksData.templateDirectoryUri + '/get_entries.php',
                data: {number: number, offset: offset},
                success: function (data, textStatus, jqXHR) {
                    console.log(data);
                },
                dataType: 'json'
            });


        }

    });

})(jQuery, this);
