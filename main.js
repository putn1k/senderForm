const forms = document.querySelectorAll('form');

function sendForm(form) {
  const formData = new FormData(form);
  fetch('mail.php', {
      method: 'POST',
      body: formData
    })
    .then(function (data) {
      if (data.status === 200) {
        goodSend(form);
        console.log(data);
      } else {
        badSend(form);
        console.log(data);
      }
    })
}

function formHandler(collection) {
  if (collection.length > 0) {
    collection.forEach( (form) => {
      form.addEventListener('submit', (event) => {
        event.preventDefault();
        sendForm(form);
        form.reset();
      })
    });
  }
}

// условия отправки

function goodSend(form) {
  form.classList.add('form--send-ok');
}

function badSend(form) {
  form.classList.add('form--not-send');
}

formHandler(forms);