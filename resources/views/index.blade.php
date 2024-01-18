<!DOCTYPE html>
<html lang="en">

<head>
  <title>Chat Laravel Pusher | Edlin App</title>
  <link rel="icon" href="https://assets.edlin.app/favicon/favicon.ico" />
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- JavaScript -->
  <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <!-- End JavaScript -->

  <!-- CSS -->
  <link rel="stylesheet" href="/style.css">
  <!-- End CSS -->

</head>

<body>
  <div class="chat">

    <!-- Header -->
    <div class="top">
      <img src="https://assets.edlin.app/images/rossedlin/03/rossedlin-03-100.jpg" alt="Avatar">
      <div>
        <p>{{ auth()->user()->name }}</p>
        <small>Online</small>
      </div>
    </div>
    <!-- End Header -->

    <!-- Chat -->
    <div class="messages">
      @include('receive', ['message' => "Hey! What's up!  👋", 'user_id' => 11])
    </div>
    <!-- End Chat -->

    <!-- Footer -->
    <div class="bottom">
      <form enctype="multipart/form-data">
        <div class="row">
          <div class="col-3">
            <select class="form-control" name="user_id" id="user-id">
              <option value="1">Arsalan</option>
              <option value="2">Ahmed</option>
              <option value="3">Siddique</option>
            </select>
          </div>
          <div class="col-3">
            <input type="text" class="form-control" id="message" name="message" placeholder="Enter message..." autocomplete="off">
          </div>
          <div class="col-3">
            <input type="file" name="image" id="file" class="form-control">
          </div>
          <div class="col-3">
            <button type="submit"></button>
          </div>
        </div>
      </form>
    </div>
    <!-- End Footer -->

  </div>
</body>

<script>
  const user_id = "{{ auth()->user() ? auth()->user()->id : '' }}";
  const app_key = "{{ env('PUSHER_APP_KEY') }}";
  const pusher = new Pusher(app_key, {
    cluster: 'ap2'
  });
  const channel = pusher.subscribe(user_id + '-message');

  //Receive messages
  channel.bind('chat', function(data) {
    console.log(data);
    $.post("/receive", {
        _token: '{{csrf_token()}}',
        message: data.message,
        user_id: data.user_id,
        file: data.file,
      })
      .done(function(res) {
        $(".messages > .message").last().after(res);
        $(document).scrollTop($(document).height());
      });
  });

  //Broadcast messages
  $("form").submit(function(event) {
    event.preventDefault();

    // $.ajax({
    //   url: "/broadcast",
    //   method: 'POST',
    //   headers: {
    //     'X-Socket-Id': pusher.connection.socket_id
    //   },
    //   data: {
    //     _token: '{{csrf_token()}}',
    //     message: $("form #message").val(),
    //     user_id: $("form #user-id").val(),
    //     file: $("form #file").val(),
    //   }
    // }).done(function(res) {
    //   $(".messages > .message").last().after(res);
    //   $("form #message").val('');
    //   $(document).scrollTop($(document).height());
    // });

    var formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('message', $("form #message").val());
    formData.append('user_id', $("form #user-id").val());
    var fileInput = $("form #file")[0];
    if (fileInput.files.length > 0) {
      // Append the file to the FormData object
      formData.append('file', fileInput.files[0]);
    }

    $.ajax({
    url: "/broadcast",
    method: 'POST',
    headers: {
        'X-Socket-Id': pusher.connection.socket_id
    },
    data: formData,
    contentType: false,
    processData: false,
    success: function(res) {
        $(".messages > .message").last().after(res);
        $("form #message").val('');
        $(document).scrollTop($(document).height());
    },
    error: function(xhr, status, error) {
        console.error(error);
    }
});
  });
</script>

</html>