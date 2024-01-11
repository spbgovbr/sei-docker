<footer class="page-footer font-small blue pt-1">
    <div class="ght text-center font-small py-3 text-muted card-footer">
        <div>
            <small>
                Último commit: {{ config('app.latest-commit-date') }}
            </small>
        </div>
        <div>
            <small>
                <a href="{{config('app.gitlab-project-url')}}/commit/{{config('app.latest-commit-sha')}}">
                    #{{ Str::substr(config('app.latest-commit-sha'), 0, 8)}}
                </a>
            </small>
        </div>
    </div>
</footer>