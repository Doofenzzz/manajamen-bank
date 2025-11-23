@if ($errors->any())
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Ups!</strong> Ada beberapa kesalahan input.
    <ul class="mb-0 mt-2">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@foreach (['success','info','warning','danger'] as $type)
  @if(session()->has($type))
    <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
      {{ session($type) }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif
@endforeach