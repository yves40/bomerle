// const menuToggle = document.querySelector('.menu-toggle');
const navigation = document.querySelector('nav');
// let container = document.querySelector(".container");
let lastScrollValue = 0;

document.addEventListener('scroll', () => {
    let top = document.documentElement.scrollTop;
    if(lastScrollValue < top){
        navigation.classList.add("hidden");
    }
    else
    {
        navigation.classList.remove("hidden");
    }
    lastScrollValue = top;
})

// menuToggle.onclick = function(){
//     navigation.classList.toggle('active');
//     menuToggle.classList.toggle('active');
// }