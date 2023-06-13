//change navbar style on scroll
window.addEventListener('scroll',()=>{
    document.querySelector('nav').classList.toggle('window-scroll', window.scrollY > 100)
})


//show/hide faq answer

const faqs = document.querySelectorAll('.faq');

faqs.forEach(faq=>{
    faq.addEventListener('click',()=>{
        faq.classList.toggle('open');

        const icon = faq.querySelector('.faq__icon i')
        if(icon.className === 'uil uil-plus'){
            icon.className = "uil uil-minus";
        }else{
            icon.className = "uil uil-plus";
        }
    })

})

//show/hide nav

const menu = document.querySelector(".nav__menu");
const menuBtn = document.querySelector("#open-menu-btn");
const closeBtn = document.querySelector("#close-menu-btn");


menuBtn.addEventListener('click', ()=>{
    menu.style.display = "flex";
    closeBtn.style.display = "inline-block";
    menuBtn.style.display = "none";
})

//close menu

const closeNav = () => {
    menu.style.display="none";
    closeBtn.style.display="none";
    menuBtn.style.display="inline-block";
}


closeBtn.addEventListener('click', closeNav);


const usernameInput = document.getElementById("username");
const passwordInput = document.getElementById("password");

usernameInput.addEventListener("input", () => {
  const value = usernameInput.value.trim();
  if (value === "") {
    usernameInput.classList.add("invalid");
  } else {
    usernameInput.classList.remove("invalid");
  }
});

passwordInput.addEventListener("input", () => {
  const value = passwordInput.value.trim();
  if (value === "") {
    passwordInput.classList.add("invalid");
  } else {
    passwordInput.classList.remove("invalid");
  }
});


