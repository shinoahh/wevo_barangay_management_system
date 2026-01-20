function sendOtp() {
  let emailInput = $('#email').val().trim();
  if (emailInput === '') {
    alert("Please enter your email.");
    return;
  }

  $.ajax({
    type: 'POST',
    url: 'send_otp.php',
    data: $('#otpForm').serialize(),
    success: function(response) {
      alert(response);
      if (response.includes("successfully")) {
        startTimer(3 * 60);
      }
    },
    error: function(xhr, status, error) {
      alert("Error: " + error);
    }
  });
}

function startTimer(duration) {
  var timer = duration, minutes, seconds;
  var display = document.getElementById('timer');
  var sendOtpBtn = document.getElementById('sendOtpBtn');

  sendOtpBtn.disabled = true; // Disable button

  var interval = setInterval(function () {
    minutes = parseInt(timer / 60, 10);
    seconds = parseInt(timer % 60, 10);

    minutes = minutes < 10 ? "0" + minutes : minutes;
    seconds = seconds < 10 ? "0" + seconds : seconds;

    display.textContent = minutes + ":" + seconds;

    if (--timer < 0) {
      clearInterval(interval);
      display.textContent = "Time's up!";
      sendOtpBtn.disabled = false; // Re-enable button
    }
  }, 1000);
}