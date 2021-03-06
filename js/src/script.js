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

        $('#participation-form input[name="is-ajax"]').val("1");

        /**
         * Called on entry like button press
         * @param $entry
         */
        function onEntryLikePressed($entry) {
            likeEntry($entry);
        }

        /**
         * Called on entry share button press
         * @param $entry
         */
        function onEntrySharePressed($entry) {
            // TODO implementation
        }

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

                    $form.trigger("reset");
                }

            },
            beforeSend: function () {
                $('.participation-form-error').hide();
            },
            clearForm: false,
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

        /**
         * Sends a like for an entry and updates its number of likes in UI
         */
        function likeEntry($entryElement) {
            var entryId = parseInt($entryElement.attr('entry-id'));

            $.ajax({
                type: "POST",
                url: ksData.templateDirectoryUri + '/api/like_entry.php',
                data: {entry_id: entryId},
                success: function (data, textStatus, jqXHR) {

                    if (!data || data.error) {
                        return;
                    }

                    $entryElement.find('.entry-like-button-text').text('');
                    $entryElement.find('.entry-like-button-count').text(data.likes.toString());
                    $entryElement.find('.entry-like-button').addClass('liked');

                },
                dataType: 'json'
            });
        }

        /**
         * Gets entries for the current page and displays them
         * @param number
         * @param page
         * @param first
         */
        function refreshEntries(number, page, first = false) {
            $.ajax({
                type: "POST",
                url: ksData.templateDirectoryUri + '/api/get_entries.php',
                data: {number: number, offset: page * number},
                success: function (data, textStatus, jqXHR) {
                    if (!data || data.error) {
                        return;
                    }

                    $('.entries-grid').empty();

                    var entries = data.entries;

                    if (entries.length === 0) {
                        $('.entries-no-entries').show();
                    } else {
                        $('.entries-no-entries').hide();
                        for (var i = 0; i < entries.length; i++) {
                            addEntryHTML(entries[i]);
                        }
                    }

                    refreshNavigation(parseInt(data.post_count), number);

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

        /**
         * Refresh entry navigation bar according to data provided
         * @param totalEntries
         * @param number
         */
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


            // Show/hide prev and next buttons
            if (currentPage === 0 || totalEntries === 0) {
                $(".entry-navigation-previous").hide();
            } else {
                $(".entry-navigation-previous").show();
            }

            if (currentPage === pages - 1 || totalEntries === 0) {
                $(".entry-navigation-next").hide();
            } else {
                $(".entry-navigation-next").show();
            }

        }

        /**
         * Creates and adds HTML for an entry
         * @param entry
         * @returns {*|jQuery|HTMLElement} returns the newly created element
         */
        function addEntryHTML(entry) {
            var $entryElement = $('<div/>', {
                "class": 'entry small-12 medium-6 large-3',
                "entry-id": entry.id,
            });

            $entryElement.appendTo($('.entries-grid')).hide().fadeIn();

            var image = $('<div/>', {
                "class": 'entry-image',
            }).css('background-image', 'url(' + entry.image + ')').css('background-size', 'cover').appendTo($entryElement);

            image.height(image.width());

            var buttonContainer = $('<div/>', {
                "class": 'entry-button-container grid-x',
            }).appendTo($entryElement);

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
                onEntryLikePressed($entryElement);
                e.preventDefault();
            });

            $('<a/>', {
                href: '#/',
                "class": 'entry-share-button cell auto',
                text: 'Jaa',
            }).appendTo(buttonContainer).click(function (e) {
                onEntrySharePressed($entryElement);
                e.preventDefault();
            });

            $('<span/>', {
                "class": 'entry-title',
                text: entry.title,
            }).appendTo($entryElement);

            $('<span/>', {
                "class": 'entry-submitter',
                text: entry.name,
            }).appendTo($entryElement);

            return $entryElement;
        }

    });

})(jQuery);
