// Navbar and profile toggle functions
const navbar = document.querySelector('.header .flex .navbar');
const profile = document.querySelector('.header .flex .profile');
const search = document.querySelector('.header .flex .search');

// Toggle menu
document.querySelector('#menu-btn').onclick = () => {
   navbar.classList.toggle('active');
   profile.classList.remove('active');
   search.classList.remove('active');
}

// Toggle profile
document.querySelector('#user-btn').onclick = () => {
   profile.classList.toggle('active');
   navbar.classList.remove('active');
}

// Scroll function to close menus
window.onscroll = () => {
   navbar.classList.remove('active');
   profile.classList.remove('active');
   search.classList.remove('active');
}

// Toggle search bar
document.querySelector('#search-btn').onclick = () => {
   search.classList.toggle('active');
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



document.addEventListener("DOMContentLoaded", () => {
   const loginForm = document.querySelector('#login-form');

   if (loginForm) {
      loginForm.addEventListener('submit', function (e) {
         e.preventDefault();  // Prevent normal form submission

         const formData = new FormData(loginForm);

         fetch('ajax_login.php', {  // Send data to ajax_login.php
            method: 'POST',
            body: formData
         })
         .then(res => res.json())  // Handle JSON response from server
         .then(data => {
            if (data.success) {
               window.location.href = 'home.php';  // Redirect if login is successful
            } else {
               showMessage(data.message || 'Login failed.');  // Display error message
            }
         })
         .catch(() => {
            showMessage('Something went wrong!');
         });
      });
   }
});



document.addEventListener('DOMContentLoaded', () => {
   const messageBox = document.getElementById('message-box');
   const closeButton = document.getElementById('close-message');

   if (messageBox && closeButton) {
      closeButton.addEventListener('click', () => {
         messageBox.style.opacity = '0';
         setTimeout(() => {
            messageBox.remove();
         }, 300); // matches CSS transition
      });

      // Optional: auto-hide after 4 seconds
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

      // Track the last clicked submit button
      form.querySelectorAll('button[type="submit"]').forEach(button => {
         button.addEventListener('click', function () {
            clickedButton = this;
         });
      });

      form.addEventListener('submit', function(e) {
         e.preventDefault();

         const formData = new FormData(form);

         // Add the clicked button's name and value manually
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
               setTimeout(() => location.reload(), 700); // Optional
            }
         })
         .catch(err => {
            console.error('Fetch error:', err);
            showMessage('Something went wrong!');
         });

         clickedButton = null; // Reset
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

