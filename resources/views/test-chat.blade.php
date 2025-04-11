<!DOCTYPE html>
<html lang="de">
<head>
  <title>Chat GPT Test</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <!-- JavaScript -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <!-- End JavaScript -->

  <!-- CSS -->
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f8;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }
    
    .chat {
      width: 100%;
      max-width: 800px;
      margin: 20px;
      border-radius: 10px;
      background-color: white;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .top {
      display: flex;
      align-items: center;
      padding: 20px;
      border-bottom: 1px solid #eee;
    }
    
    .top img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      margin-right: 15px;
    }
    
    .messages {
      padding: 20px;
      max-height: 500px;
      overflow-y: auto;
    }
    
    .message {
      display: flex;
      align-items: flex-start;
      margin-bottom: 20px;
    }
    
    .message img {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      margin-right: 10px;
    }
    
    .message p {
      padding: 10px 15px;
      border-radius: 15px;
      max-width: 70%;
      word-wrap: break-word;
    }
    
    .left p {
      background-color: #f0f0f0;
    }
    
    .right {
      flex-direction: row-reverse;
    }
    
    .right img {
      margin-right: 0;
      margin-left: 10px;
    }
    
    .right p {
      background-color: #D8F5FF;
    }
    
    .bottom {
      padding: 20px;
      border-top: 1px solid #eee;
    }
    
    .bottom form {
      display: flex;
    }
    
    .bottom input {
      flex: 1;
      padding: 10px 15px;
      border: 1px solid #ddd;
      border-radius: 25px;
      outline: none;
      margin-right: 10px;
    }
    
    .bottom button {
      width: 40px;
      height: 40px;
      border: none;
      border-radius: 50%;
      background-color: #007bff;
      cursor: pointer;
      position: relative;
    }
    
    .bottom button:before {
      content: '';
      display: block;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 0;
      height: 0;
      border-style: solid;
      border-width: 8px 0 8px 15px;
      border-color: transparent transparent transparent white;
    }
  </style>
  <!-- End CSS -->
</head>

<body>
<div class="chat">
  <!-- Header -->
  <div class="top">
    <img src="https://via.placeholder.com/100" alt="Avatar">
    <div>
      <p>Test Chat</p>
      <small>GPT API Test</small>
    </div>
  </div>
  <!-- End Header -->

  <!-- Chat -->
  <div class="messages">
    <div class="left message">
      <img src="https://via.placeholder.com/100" alt="Avatar">
      <p>Starten Sie die Unterhaltung mit ChatGPT!</p>
    </div>
  </div>
  <!-- End Chat -->

  <!-- Footer -->
  <div class="bottom">
    <form>
      <input type="text" id="message" name="message" placeholder="Nachricht eingeben..." autocomplete="off">
      <button type="submit"></button>
    </form>
  </div>
  <!-- End Footer -->
</div>

<script>
  // CSRF-Token für Ajax-Anfragen
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    }
  });

  // Nachricht senden
  $("form").submit(function (event) {
    event.preventDefault();

    // Leere Nachrichten verhindern
    if ($("#message").val().trim() === '') {
      return;
    }

    // Formular deaktivieren
    $("#message").prop('disabled', true);
    $("form button").prop('disabled', true);

    // Benutzernachricht anzeigen
    $(".messages").append('<div class="right message">' +
      '<p>' + $("#message").val() + '</p>' +
      '<img src="https://via.placeholder.com/100" alt="Avatar">' +
      '</div>');

    // Nachricht an Server senden
    console.log("Sende Anfrage an /api/chat mit:", {
      model: "gpt-3.5-turbo",
      message: $("#message").val()
    });
    
    $.ajax({
      url: "/api/chat",
      method: 'POST',
      data: {
        "model": "gpt-3.5-turbo",
        "message": $("#message").val()
      }
    }).done(function (res) {
      console.log("Antwort erhalten:", res);
      
      // Antwort anzeigen
      $(".messages").append('<div class="left message">' +
        '<img src="https://via.placeholder.com/100" alt="Avatar">' +
        '<p>' + res.response + '</p>' +
        '</div>');

      // Zum Ende der Nachrichten scrollen
      $(".messages").scrollTop($(".messages")[0].scrollHeight);

      // Eingabefeld leeren
      $("#message").val('');

      // Formular wieder aktivieren
      $("#message").prop('disabled', false);
      $("form button").prop('disabled', false);
    }).fail(function(error) {
      console.error("Fehler:", error);
      console.log("Fehler-Status:", error.status);
      console.log("Fehler-Text:", error.statusText);
      console.log("Fehler-Response:", error.responseJSON || error.responseText);
      
      // Fehlermeldung anzeigen
      let errorMessage = "Ein Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.";
      
      if (error.status === 429) {
        errorMessage = "Die Anfragelimits der API wurden überschritten. Bitte versuchen Sie es in einigen Minuten erneut.";
        console.log("Rate Limit erreicht: Warten Sie einige Minuten und versuchen Sie es erneut.");
      } else if (error.responseJSON && error.responseJSON.response) {
        errorMessage = error.responseJSON.response;
      }
      
      $(".messages").append('<div class="left message">' +
        '<img src="https://via.placeholder.com/100" alt="Avatar">' +
        '<p>' + errorMessage + '</p>' +
        '</div>');
        
      // Formular wieder aktivieren
      $("#message").prop('disabled', false);
      $("form button").prop('disabled', false);
    });
  });
</script>
</body>
</html> 