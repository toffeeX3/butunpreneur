
const navbar = document.querySelector('.header .flex .navbar');
const profile = document.querySelector('.header .flex .profile');
const search = document.querySelector('.header .flex .search');

document.querySelector('#menu-btn').onclick = () => {
   navbar.classList.toggle('active');
   profile.classList.remove('active');
   search.classList.remove('active');
}

// profil 
document.querySelector('#user-btn').onclick = () => {
   profile.classList.toggle('active');
   search.classList.remove('active');
   navbar.classList.remove('active');
}

// close saat scrool
window.onscroll = () => {
   navbar.classList.remove('active');
   profile.classList.remove('active');
   search.classList.remove('active');
}

// search
document.querySelector('#search-btn').onclick = () => {
   search.classList.toggle('active');
   profile.classList.remove('active');
   navbar.classList.remove('active');
}

function loader() {
   document.querySelector('.loader').style.display = 'none';
}

function fadeOut() {
   setInterval(loader, 1000);
}


function showMessage(text) {
   // remove
   const existing = document.getElementById('message-box');
   if (existing) existing.remove();

   const box = document.createElement('div');
   box.id = 'message-box';
   box.className = 'message-box';
   box.innerHTML = `
      ${text}
      <button id="close-message">Close</button>
   `;
   document.body.appendChild(box);

   // set timeout untuk close box atomatis
   setTimeout(() => {
      box.style.opacity = '0';
      setTimeout(() => {
         box.style.display = 'none';
      }, 1000);
   }, 5000); // detik

   // ngambil dr button id 
   const closeButton = document.getElementById('close-message');
   if (closeButton) {
      closeButton.addEventListener('click', () => {
         box.style.opacity = '0';
         setTimeout(() => {
            box.style.display = 'none';
         }, 200);
      });
   }
}

//for add to cart
document.addEventListener("DOMContentLoaded", function () {
   document.querySelectorAll('.add-to-cart-form').forEach(form => {
      form.addEventListener('submit', function(e) {
         e.preventDefault();

         const formData = new FormData(form);

         fetch(form.action, {
            method: 'POST',
            body: formData
         })
         .then(res => res.json())
         .then(data => {
            showMessage(data.message);
         })
         .catch(() => {
            showMessage('Something went wrong!');
         });
      });
   });
});

// for cart
document.addEventListener('DOMContentLoaded', () => {
   document.querySelectorAll('.cart-action-form, .delete-all-form').forEach(form => {
      form.addEventListener('submit', function(e) {
         e.preventDefault();

         const formData = new FormData(this);

         fetch('components/cart_actions_ajax.php', {
            method: 'POST',
            body: formData
         })
         .then(res => res.json())
         .then(data => {
            if (data.message) {
               showMessage(data.message);
            }
         })
         .catch(err => {
            console.error('Error:', err);
         });
      });
   });
});

document.addEventListener('DOMContentLoaded', () => {
   // ambil id
   const messageBox = document.getElementById('message-box');
   const closeButton = document.getElementById('close-message');

   if (messageBox && closeButton) {
      closeButton.addEventListener('click', () => {
         messageBox.style.opacity = '0';
         setTimeout(() => {
            messageBox.remove();
         }, 300); 
      });

      setTimeout(() => {
         if (messageBox) {
            messageBox.style.opacity = '0';
            setTimeout(() => {
               messageBox.remove();
            }, 300);
         }
      }, 4000);
   }
});


document.addEventListener('DOMContentLoaded', () => {
   document.querySelectorAll('.cart-action-form, .delete-all-form').forEach(form => {
      let clickedButton = null;

      // ngambil button yg terakhir di klik
      form.querySelectorAll('button[type="submit"]').forEach(button => {
         button.addEventListener('click', function () {
            clickedButton = this;
         });
      });

      form.addEventListener('submit', function(e) {
         e.preventDefault();

         const formData = new FormData(form);

         // nambah value yg tadi
         if (clickedButton && clickedButton.name) {
            formData.append(clickedButton.name, clickedButton.value || '1');
         }

         fetch(form.action, {
            method: 'POST',
            body: formData
         })
         .then(res => res.json())
         .then(data => {
            if (data.message) {
               showMessage(data.message);
               setTimeout(() => location.reload(), 700);
            }
         })
         .catch(err => {
            console.error('Fetch error:', err);
            showMessage('Something went wrong!');
         });

         clickedButton = null;
      });
   });
});





document.querySelectorAll('input[type="number"]').forEach(numberInput => {
   numberInput.oninput = () => {
      if (numberInput.value.length > numberInput.maxLength) {
         numberInput.value = numberInput.value.slice(0, numberInput.maxLength);
      }
   };
});

