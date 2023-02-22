// ------------------ Init loop to trap all mouse clicks -------------------------
$(document).ready(function () {
    const buttonhide = $("#hideslider");
    const buttonshow = $("#showslider");
    $(buttonhide).hide();
    $props.load();
    let imgsource = $(".sliderdata").data('images');
    console.log(`Images source to be retrieved from ${imgsource}`);
    console.log($props.version());
    $(buttonshow).click(function (e) { 
        e.preventDefault();
        $(buttonshow).hide();
        $(buttonhide).show();
    });
    $(buttonhide).click(function (e) { 
        e.preventDefault();
        $(buttonshow).show();
        $(buttonhide).hide();
    });
})

// let track = document.querySelector('.carousel-slides');
// let slides = Array.from(track.children);
// const nextButton = document.querySelector('.carousel-button--right');
// const prevButton = document.querySelector('.carousel-button--left');
// const dotsNav = document.querySelector('.carousel-nav');
// const dots = Array.from(dotsNav.children);

// let slideWidth = slides[0].getBoundingClientRect().width;

// const setSlidePosition = (slide, index) => {
//   slide.style.left = slideWidth * index + 'px';
// }
// slides.forEach( setSlidePosition );

// const moveToSlide = (track, currentSlide, targetSlide) => {
//   track.style.transform = 'translateX(-' + targetSlide.style.left + ')';
//   currentSlide.classList.remove('current-slide');
//   targetSlide.classList.add('current-slide') 
// }

// const updateDots = (currentDot, targetDot) => {
//   currentDot.classList.remove('current-slide');
//   targetDot.classList.add('current-slide')
// }

// const hideShowArrows = (slides, prevButton, nextButton, targetIndex) => {
//   if(targetIndex === 0) {
//     prevButton.classList.add('is-hidden');
//     nextButton.classList.remove('is-hidden');
//   }
//   else {
//     if(targetIndex === slides.length - 1) {
//       prevButton.classList.remove('is-hidden');
//       nextButton.classList.add('is-hidden');
//     }
//     else {
//       prevButton.classList.remove('is-hidden');
//       nextButton.classList.remove('is-hidden');
//     }
//   }
// }

// // Left click  <-
// prevButton.addEventListener('click', e => {
//   const currentSlide = document.querySelector('.current-slide');
//   const prevSlide = currentSlide.previousElementSibling;
//   const currentDot = dotsNav.querySelector('.current-slide');
//   const prevDot = currentDot.previousElementSibling;
//   const prevIndex = slides.findIndex( slide => slide === prevSlide);
//   moveToSlide(track, currentSlide, prevSlide);
//   updateDots(currentDot, prevDot);
//   hideShowArrows(slides, prevButton, nextButton, prevIndex);
// })
// // Right click ->
// nextButton.addEventListener('click', e => {
//   const currentSlide = document.querySelector('.current-slide');
//   const nextSlide = currentSlide.nextElementSibling;
//   const currentDot = dotsNav.querySelector('.current-slide');
//   const nextDot = currentDot.nextElementSibling;
//   const nextIndex = slides.findIndex( slide => slide === nextSlide);
//   moveToSlide(track, currentSlide, nextSlide);
//   updateDots(currentDot, nextDot);
//   hideShowArrows(slides, prevButton, nextButton, nextIndex);
// })

// // Nav selector click, move to the selected slide
// dotsNav.addEventListener('click', e => {
//   // What indicator was clicked on ? 
//   const targetDot = e.target;
//   // Check an indicator has been clicked (not elsewhere in the parent)
//   if(!targetDot.classList.contains('carousel-indicator')) {
//     return;
//   }
//   const currentSlide = track.querySelector('.current-slide');
//   const currentDot = dotsNav.querySelector('.current-slide');
//   const targetIndex = dots.findIndex(dot => dot === targetDot); // Returns the index
//   const targetSlide = slides[targetIndex];
//   const nextIndex = slides.findIndex( slide => slide === targetSlide);
//   moveToSlide(track, currentSlide, targetSlide);
//   updateDots(currentDot, targetDot);
//   hideShowArrows(slides, prevButton, nextButton, nextIndex);
// })

// // Track window resize
// window.onresize =  () =>  {
//     slideWidth = currentSlideWidth;
//     // Place photos with the new window size
//     slides.forEach( setSlidePosition );
//     let currentslide = document.getElementsByClassName('current-slide carousel-slide')[0];
//     let theimg = currentslide.firstElementChild.getAttribute('src');
//     console.log(`Resized : Current image : ${theimg}`);
//     moveToSlide(track, currentslide, currentslide);
// };

// /*
//       R E S E R V O I R    C O D E 

    
// setInterval(() => {
//   let currentSlideWidth = slides[0].getBoundingClientRect().width;
//   if(currentSlideWidth !== slideWidth) {
//     slideWidth = currentSlideWidth;
//     // Place photos with the new window size
//     slides.forEach( setSlidePosition );
//     let currentslide = document.getElementsByClassName('current-slide carousel-slide')[0];
//     let theimg = currentslide.firstElementChild.getAttribute('src');
//     console.log(`Resized : Current image : ${theimg}`);
//     moveToSlide(track, currentslide, currentslide);
//   }
// }, 500);
// */


