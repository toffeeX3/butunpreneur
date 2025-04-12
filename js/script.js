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

// window.onload = function() {
//    // Handle message box fade-out
//    const messageBox = document.getElementById('message-box');
//    const closeButton = document.getElementById('close-message');

//    // If messageBox exists, start fade-out and auto-hide after 2 seconds
//    if (messageBox) {
//        // Auto-fade out after 2 seconds
//        setTimeout(function() {
//            messageBox.style.opacity = '0'; 
//            setTimeout(function() {
//                messageBox.style.display = 'none'; 
//            }, 1000);
//        }, 5000); 
       
//        if (closeButton) {
//            closeButton.addEventListener('click', function() {
//                messageBox.style.opacity = '0';
//                setTimeout(function() {
//                    messageBox.style.display = 'none';
//                }, 200);
//            });
//        }
//    }
// }

function showMessage(text) {
   // Remove old message if it's there
   const existing = document.getElementById('message-box');
   if (existing) existing.remove();

   // Create new message box
   const box = document.createElement('div');
   box.id = 'message-box';
   box.className = 'message-box';
   box.innerHTML = `
      ${text}
      <button id="close-message">Close</button>
   `;
   document.body.appendChild(box);

   // Auto-fade after 5 seconds
   setTimeout(() => {
      box.style.opacity = '0';
      setTimeout(() => {
         box.style.display = 'none';
      }, 1000);
   }, 5000);

   // Close button
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

document.addEventListener('DOMContentLoaded', () => {
   // Handle all cart form submissions with fetch
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
               showMessage(data.message); // ✅ your existing function!
            }
         })
         .catch(err => {
            console.error('Error:', err);
         });
      });
   });
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

