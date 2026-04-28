// assets/js/script.js

document.addEventListener('DOMContentLoaded', function() {
    // Select all "Add to Cart" buttons
    const addToCartButtons = document.querySelectorAll('.add-to-cart');

    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent any default action (e.g., form submission)

            const productId = this.dataset.id; // Get product ID from data-id attribute

            // Send AJAX request
            fetch('ajax-add-to-cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'product_id=' + encodeURIComponent(productId) + '&quantity=1'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Optional: Show a success message
                    alert('Product added to cart!');

                    // Update the cart count in the navigation
                    const cartLink = document.querySelector('nav a[href="cart.php"]');
                    if (cartLink) {
                        cartLink.textContent = 'Cart (' + data.cartCount + ')';
                    }
                } else {
                    alert('Failed to add product. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding to cart.');
            });
        });
    });
});