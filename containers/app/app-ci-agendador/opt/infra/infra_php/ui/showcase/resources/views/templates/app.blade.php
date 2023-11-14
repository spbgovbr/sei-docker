<!doctype html>
<html lang="en" class="line-numbers">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="/js/app.js"></script>
        @yield('includes')
        <script>
            function r(id, name) {
                return {
                    id: id,
                    name: name
                }
            }

            function getCountryStates(country) {
                var data = fetchStates(country);
                return data;
            }

            function fetchStates(country) {
                var data = [
                    r('1', '11'),
                    r('2', '12'),
                    r('3', '21'),
                    r('4', '22'),
                ].filter(r => r.name.substr(0, country.length) == country);
                return data;
            }
        </script>
        <link href="/css/app.css" rel="stylesheet">
        <title>TRF4\UI Docs</title>
    </head>
    <body>
        @yield('body-content')
    </body>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script>
        $(function () {
            $(".showcaser-form").submit(function (e) {
                e.preventDefault();

                const $this = $(this);
                const resultArea = $this.data('result-area');
                const resultAreaEl = $('#' + resultArea);
                const httpMethod = $(this).find('select[name=http_method]').val();

                resultAreaEl.html('');
                resultAreaEl.addClass('loading');
                resultAreaEl.append('<div class="loader">Loading...</div>');

                $.ajax({
                    url: '/showcase-uiget',
                    method: httpMethod,
                    data: $this.serialize()
                }).then((data) => {
                    resultAreaEl.html(data);
                    Prism.highlightAllUnder(resultAreaEl[0]);
                    resultAreaEl.removeClass('loading');
                    resultAreaEl.find('.loader').remove();

                    resultAreaEl.find('[data-toggle="tab"]').click((e) => {
                        resultAreaEl.find('.tab-pane.active.show').removeClass('active show');
                        e.preventDefault();
                        const target = $(e.target).attr("href");
                        $(target).tab('show');
                    });
                });
            });
        });
    </script>

</html>
