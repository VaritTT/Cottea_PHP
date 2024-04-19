document.addEventListener("DOMContentLoaded", function() {
  const inputs = document.querySelectorAll(".input-field input[type='number']");
  const submitBtn = document.querySelector(".btn2");

  function checkInputs() {
      const allFilled = Array.from(inputs).every(input => input.value !== '');
      submitBtn.classList.toggle("active", allFilled);
      submitBtn.disabled = !allFilled;
  }

  inputs.forEach((input, index) => {
      input.addEventListener("input", (e) => {
          let nextInput;
          if (e.target.value) {
              nextInput = inputs[index + 1];
          } else {
              nextInput = inputs[index - 1];
          }

          if (nextInput) {
              nextInput.removeAttribute('disabled');
              if (e.target.value) nextInput.focus();
          }

          let otpValue = Array.from(inputs).reduce((acc, input) => acc + input.value, '');
          document.querySelector('input[name="otp"]').value = otpValue;

          checkInputs();
      });
  });
});

