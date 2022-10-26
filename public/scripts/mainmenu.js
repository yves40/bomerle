const menuToggle = document.querySelector('.menu-toggle');
const navigation = document.querySelector('.navigation');

let container = document.querySelector(".container");
let lastScrollValue = 0;

document.addEventListener('scroll', () => {
    let top = document.documentElement.scrollTop;
    if(lastScrollValue < top){
        container.classList.add("hidden");
    }
    else
    {
        container.classList.remove("hidden");
    }
    lastScrollValue = top;
})

menuToggle.onclick = function(){
    navigation.classList.toggle('active');
    menuToggle.classList.toggle('active');
}