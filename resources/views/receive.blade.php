<div class="left message">
  <img src="https://assets.edlin.app/images/rossedlin/03/rossedlin-03-100.jpg" alt="Avatar">
  @if(isset($fileUrl) && !empty($fileUrl))
  <img src="{{ $fileUrl }}" alt="Attached File">
  @endif
  <p>{{$message}}</p>
</div>