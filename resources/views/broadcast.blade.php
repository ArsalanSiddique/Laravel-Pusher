<div class="right message">
  <p>{{$message}}</p>
  @if(isset($fileUrl) && !empty($fileUrl))
  <img src="{{ $fileUrl }}" alt="Attached File">
  @endif
  <img src="https://assets.edlin.app/images/rossedlin/03/rossedlin-03-100.jpg" alt="Profile picture">
</div>