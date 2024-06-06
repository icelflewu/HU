

document.addEventListener('DOMContentLoaded', () => {
    const text = "360 MiXX";
    let index = 0;
    const typingSpeed = 90; // Kecepatan mengetik dalam milidetik

    function type() {
        if (index < text.length) {
            document.getElementById("typing").innerHTML += text.charAt(index);
            index++;
            setTimeout(type, typingSpeed);
        }
    }

    type();
});


// function untuk "gallery"
document.addEventListener('DOMContentLoaded', (event) => {
    // Mengambil elemen tombol dan konten untuk gallery
    const showMoreButtonGallery = document.querySelector('#gallery .show-more');
    const galleryContent = document.querySelector('.gallery-content');

    showMoreButtonGallery.addEventListener('click', () => {
        if(galleryContent.style.display === 'none') {
            galleryContent.style.display = 'block';
            showMoreButtonGallery.textContent = 'Hide';
        } else {
            galleryContent.style.display = 'none';
            showMoreButtonGallery.textContent = 'Show More';
        }
    });
});


// function untuk "blog"
document.addEventListener('DOMContentLoaded', (event) => {
    const showMoreButton = document.querySelector('#blog .show-more');
    const blogContent = document.querySelector('.blog-content');

    showMoreButton.addEventListener('click', () => {
        if(blogContent.style.display === 'none') {
            blogContent.style.display = 'block';
            showMoreButton.textContent = 'Hide';
        } else {
            blogContent.style.display = 'none';
            showMoreButton.textContent = 'Show More';
        }
    });
});


// function untuk "quotes"
async function ambilKutipan() {
    const respon = await fetch("https://api.quotable.io/random");
    const data = await respon.json();
  
    const elemenKutipan = document.getElementById("quote");
    elemenKutipan.textContent = `"${data.content}" - ${data.author}`;
  }
  
  ambilKutipan();

// Fungsi untuk "Scroll to Top"
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    document.getElementById("scrollToTopBtn").style.display = "block";
  } else {
    document.getElementById("scrollToTopBtn").style.display = "none";
  }
}
function topFunction() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
}
document.getElementById("scrollToTopBtn").addEventListener("click", topFunction);

// Tambahkan di dalam file script.js
document.addEventListener('DOMContentLoaded', function() {
    const galleryContent = document.querySelectorAll('.gallery-content a');
    const lightboxOverlay = document.getElementById('lightbox-overlay');
    const lightboxImage = document.getElementById('lightbox-image');
    const lightboxCaption = document.getElementById('lightbox-caption');
    const lightboxClose = document.getElementById('lightbox-close');
    const lightboxPrev = document.getElementById('lightbox-prev');
    const lightboxNext = document.getElementById('lightbox-next');

    let currentIndex = 0;

    // Fungsi untuk membuka lightbox
    function openLightbox(index) {
        currentIndex = index;
        const imageUrl = galleryContent[currentIndex].getAttribute('href');
        lightboxImage.src = imageUrl;
        lightboxCaption.textContent = galleryContent[currentIndex].querySelector('img').alt;
        lightboxOverlay.style.display = 'block';
    }

    // Fungsi untuk menutup lightbox
    function closeLightbox() {
        lightboxOverlay.style.display = 'none';
    }

    // Fungsi untuk menampilkan gambar sebelumnya
    function showPrevImage() {
        currentIndex = (currentIndex === 0) ? galleryContent.length - 1 : currentIndex - 1;
        const imageUrl = galleryContent[currentIndex].getAttribute('href');
        lightboxImage.src = imageUrl;
        lightboxCaption.textContent = galleryContent[currentIndex].querySelector('img').alt;
    }


    // Event listener untuk membuka lightbox saat gambar diklik
    galleryContent.forEach((item, index) => {
        item.addEventListener('click', (event) => {
            event.preventDefault();
            openLightbox(index);
        });
    });

    // Event listener untuk menutup lightbox saat diklik di luar area gambar
    lightboxOverlay.addEventListener('click', (event) => {
        if (event.target === lightboxOverlay) {
            closeLightbox();
        }
    });
});

// Fungsi untuk menambahkan kelas saat navigation bar mencapai bagian atas
window.addEventListener('scroll', function() {
    const nav = document.getElementById('sticky-nav');
    if (window.pageYOffset > 0) {
        nav.classList.add('sticky');
    } else {
        nav.classList.remove('sticky');
    }
});

//fungsi untuk "toggle menu"
const toggleMenu = document.querySelector('.toggle-menu');
const menu = document.querySelector('.menu');
const nav = document.getElementById('sticky-nav');

toggleMenu.addEventListener('click', function() {
    this.classList.toggle('active');
    menu.classList.toggle('active');
    nav.classList.toggle('active');


});

document.getElementById('payment-method').addEventListener('change', function() {
    var paymentProofDiv = document.getElementById('payment-proof');
    if (this.value === 'cash') {
        paymentProofDiv.style.display = 'none';
    } else {
        paymentProofDiv.style.display = 'block';
    }
});

