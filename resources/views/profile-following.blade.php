<x-profile :sharedData="$sharedData">
    <div class="list-group">
      @foreach ($posts as $post)
        <a href="/post/{{$post->id}}" class="list-group-item list-group-item-action">
          <img class="avatar-tiny" src="{{ $sharedData['avatar'] }}" />
          <strong>{{ $post->title }}</strong> on {{$post->created_at->format('d/j/Y')}}
        </a>
      @endforeach
    </div>
</x-profile>