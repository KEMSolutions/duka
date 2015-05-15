@foreach($videos as $video)
    <div class="widget">
        <h4 class="widget-heading">{{ Lang::get("boukem.video") }}</h4>
        <div class="w-box">
            <figure>
                <div class="video-container">

                    <video width="100%" height=180 controls preload="metadata" poster="{{ $video->poster }}" class="video-js vjs-default-skin vjs-big-play-centered">
                        <source src="{{ $video->h264high }}" media="only screen and (min-device-width: 568px)" type='video/mp4'></source>
                        <source src="{{ $video->h264low }}"  media="only screen and (max-device-width: 568px)" type='video/mp4'></source>
                        <source src="{{ $video->webm }}" type='video/webm'></source>
                    </video>

                </div>
                <h2>{{ $video->title }}</h2>
            </figure>
        </div>
    </div>
@endforeach