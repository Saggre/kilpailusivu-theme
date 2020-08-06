(function ($, root, undefined) {

    $(function () {

        'use strict';

        var entriesPerPage = 8;
        var currentPage = 0;

        refreshEntries(entriesPerPage, currentPage, true);

        $('.participate-cta').click(function () {
            $(window).scrollTo($('.section.participate'), 400, {
                axis: 'y',
                offset: -50,
            });
        });

        $(window).resize(function () {

            $('.entries-grid .entry .entry-image').each(
                function () {
                    $(this).height($(this).width());
                }
            );
        });

        /**
         * Called on entry like button press
         * @param $button
         */
        function onEntryLiked($button) {
            $button.addClass('liked');

            // Fake like count
            $button.children('.entry-like-button-text').text('');
            $button.children('.entry-like-button-count').text(Math.floor(Math.random() * 256));

            // TODO implementation
        }

        /**
         * Called on entry share button press
         * @param $button
         */
        function onEntryShared($button) {
            // TODO implementation
        }

        $('#participation-form input[name="is-ajax"]').val("1");

        $('#participation-form').ajaxForm({
            dataType: 'json',
            success: function (data, textStatus, jqXHR, $form) {

                if (!data) {
                    $('.participation-form-error').fadeIn().children('.participation-form-error-message').text('Tapahtui tuntematon virhe. Yritä uudelleen');
                } else if (data.error) {
                    $('.participation-form-error').fadeIn().children('.participation-form-error-message').text(data.error_msg);
                } else {
                    $('.participation-form-error-message').text('');
                    currentPage = 0;
                    refreshEntries(entriesPerPage, currentPage);
                }
            },
            beforeSend: function () {
                $('.participation-form-error').hide();
            },
        });

        $('.entry-navigation-previous').show().click(function () {
            currentPage = Math.max(0, currentPage - 1);
            refreshEntries(entriesPerPage, currentPage);
        });

        $('.entry-navigation-next').show().click(function () {
            var pages = parseInt($(".entry-navigation-numbers").attr('pages'));
            currentPage = Math.min(pages - 1, currentPage + 1);
            refreshEntries(entriesPerPage, currentPage);
        });

        function refreshEntries(number, page, first = false) {
            $.ajax({
                type: "POST",
                url: ksData.templateDirectoryUri + '/get_entries.php',
                data: {number: number, offset: page * number},
                success: function (data, textStatus, jqXHR) {
                    if (!data || data.error) {
                        return;
                    }

                    $('.entries-grid').empty();

                    var entries = data.entries;
                    for (var i = 0; i < entries.length; i++) {
                        addEntryHTML(entries[i]);
                    }

                    refreshNavigation(data.post_count, number);

                    if (!first) {
                        $(window).scrollTo($('.section.entries'), 400, {
                            axis: 'y',
                            offset: -50,
                        });
                    }
                },
                dataType: 'json'
            });

        }

        function refreshNavigation(totalEntries, number) {

            var numberEls = $('.entry-navigation .entry-navigation-numbers').empty();
            var pages = Math.ceil(totalEntries / number);

            $(".entry-navigation-numbers").attr('pages', pages);

            for (var i = 0; i < pages; i++) {
                $('<span/>', {
                    "class": 'entry-navigation-number' + (i === currentPage ? ' current' : ''),
                    text: (i + 1),
                    page: i,
                }).appendTo(numberEls).click(function () {

                    currentPage = parseInt($(this).attr('page'));
                    refreshEntries(entriesPerPage, currentPage);
                });
            }


            // Hiden prev and next
            if (currentPage === 0) {
                $(".entry-navigation-previous").hide();
            } else {
                $(".entry-navigation-previous").show();
            }

            if (currentPage === pages - 1) {
                $(".entry-navigation-next").hide();
            } else {
                $(".entry-navigation-next").show();
            }

        }

        function addEntryHTML(entry) {
            var entryElement = $('<div/>', {
                "class": 'entry small-12 medium-6 large-3',
            });

            entryElement.appendTo($('.entries-grid')).hide().fadeIn();

            var image = $('<div/>', {
                "class": 'entry-image',
            }).css('background-image', 'url(' + entry.image + ')').css('background-size', 'cover').appendTo(entryElement);

            image.height(image.width());

            var buttonContainer = $('<div/>', {
                "class": 'entry-button-container grid-x',
            }).appendTo(entryElement);

            $('<a/>', {
                href: '#/',
                "class": 'entry-like-button cell shrink',
            }).appendTo(buttonContainer).prepend(
                $('<span/>', {
                    "class": 'entry-like-button-text',
                    text: 'Äänestä',
                })
            ).append(
                $('<i/>', {
                    "class": 'entry-like-button-icon',
                })
            ).append(
                $('<span/>', {
                    "class": 'entry-like-button-count',
                })
            ).click(function (e) {
                onEntryLiked($(this));
                e.preventDefault();
            });

            $('<a/>', {
                href: '#/',
                "class": 'entry-share-button cell auto',
                text: 'Jaa',
            }).appendTo(buttonContainer).click(function (e) {
                onEntryShared($(this));
                e.preventDefault();
            });

            $('<span/>', {
                "class": 'entry-title',
                text: entry.title,
            }).appendTo(entryElement);

            $('<span/>', {
                "class": 'entry-submitter',
                text: entry.name,
            }).appendTo(entryElement);

            return entryElement;
        }

    });

})(jQuery);
