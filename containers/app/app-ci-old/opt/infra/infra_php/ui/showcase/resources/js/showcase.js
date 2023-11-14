
$(function () {

    function scrollWindowNavigate(hash, speed) {
        $("body, html").animate({
            scrollTop: $('#' + hash).offset().top - 90
        }, speed);
    }

    const url = window.location.href;
    const hash = url.substring(url.indexOf("#") + 1);

    $(document).ready(function () {
        if (!hash.includes("http") && url.includes("#")) {
            scrollWindowNavigate(hash, 1);
        }
    })

    $(".showcase-navigator").click(function (e) {
        const url = e.target.href;
        const hash = url.substring(url.indexOf("#") + 1);
        scrollWindowNavigate(hash, 500);
    })

    var currentStick = null;
    var currentHeader = null;
    var currentMid = null;
    var sidebar = $('#sidebar-wrapper')

    function setFixedStick() {
        currentStick = null;
        var belongsToFolder = false;

        if (currentHeader) {
            if (currentHeader.querySelector('h3 .subitem-title')) {
                currentHeader.querySelector('h3 .subitem-title').innerHTML = "";
            }
        }
        currentHeader = null;
        currentMid = null;

        var headers = document.getElementsByClassName('card-header');

        for (var i = 0; i < headers.length; i++) {
            if (headers[i].classList.contains('sticky-change')) {
                var divHeight = headers[i].parentElement.offsetHeight;

                if (!headers[i].classList.contains('sticky-top')) {
                    if (headers[i].getBoundingClientRect().top < 0 && headers[i].getBoundingClientRect().top > (0 - divHeight)) {
                        currentStick = headers[i];
                    }
                } else {
                    if (headers[i].getBoundingClientRect().top === 0) {
                        currentMid = headers[i].parentElement.getAttribute('data-item');
                        currentStick = headers[i];
                    }
                }

            } else {
                if (headers[i].classList.contains('sticky-top')) {
                    if (headers[i].getBoundingClientRect().top === 0) {
                        currentHeader = headers[i];
                    }
                }
            }
        }
    }

    function setCarHeaderContent() {
        var title = null;

        if (currentStick.classList.contains('sticky-top')) {
            title = currentStick.parentElement.getAttribute('data-item');
        } else if (currentMid) {
            title = currentStick.parentElement.getAttribute('data-title');
        } else if (!currentStick.parentElement.parentElement.parentElement.parentElement.parentElement.classList.contains('card-body')) {
            title = currentStick.parentElement.getAttribute('data-title');
        }
        if (currentMid && title !== currentMid) {
            title = currentMid + '/' + title;
        }
        if (title) {
            currentHeader.querySelector('h3 .subitem-title').innerHTML = title;
        }
    }

    window.addEventListener('scroll', function () {
        setFixedStick();

        if (currentStick && currentHeader) {
            setCarHeaderContent();
        }
    })

    $('.showcase-directory .showcase-directory > .card-header.sticky-top').each(function () {
        $(this).addClass('sticky-change subItem');
        $(this).find('h3').find('.subitem-title').remove();
    });

    var scrollSidebar = _.debounce(function (li) {
        var posY = li.offset().top;

        if (!li.visible(false)) {
            sidebar.animate({
                scrollTop: posY - (posY * 0.93)
            }, 200);
        }
    }, 150)

    $('.card').hover(function (e) {
        if ($(this).data('item')) {
            $('.doc-ul li').removeClass('current');
            var item = $(this).data('item');
            var li = $('.doc-ul li[data-item=' + item + ']');

            if (li.offset() !== undefined) {
                li.addClass('current');
                scrollSidebar(li);
            }
        }
    });

    //renderer change

    $("#renderer").change((e) => {
        const val = $('#renderer').val();
        location.href = '/docs/' + val;
    });
});