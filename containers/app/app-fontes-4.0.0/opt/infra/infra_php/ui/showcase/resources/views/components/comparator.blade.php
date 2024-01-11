<div class="container-fluid p-0 comparator">
    <div class="card mb-5 p-0">
        <div class="row no-gutters comparator">
            <div class="col-md-5 d-flex align-items-center">
                <div class="card-body pl-5 pt-5" {{ isset($leftSideId)?'id='.$leftSideId:'' }}>
                    {{ $slot }}
                </div>
            </div>
            <div class="col-md-7 p-0 tabs-container">
                <ul class="nav nav-tabs" role="tablist">
                    @foreach($tabs as $tab)
                        <li class="nav-item">
                            <a class="nav-link {{ $loop->first?'active':'' }}" data-toggle="tab" href="#{{ $tab['id'] }}" role="tab" aria-controls="home" aria-selected="{{ $loop->first?'true':'false' }}">{{ $tab['name'] }} </a>
                        </li>
                    @endforeach
                </ul>
                <div class="mh-100 d-block overflow-auto">
                    <div class="tab-content">
                        @foreach($tabs as $tab)
                            <div class="tab-pane fade {{ $loop->first?'show active':'' }} prismjs-container" id="{{ $tab['id'] }}" role="tabpanel">
                                <div class="prism-js">
                                    <pre class="m-0"><code class="language-{{$tab['preClass']}} m-0">{{ $tab['content'] }}</code></pre>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>