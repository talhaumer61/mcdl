"use strict";

if (document.querySelector("#switcher-canvas")) {
    //switcher color pickers
    const pickrContainerPrimary = document.querySelector('.pickr-container-primary');
    const themeContainerPrimary = document.querySelector('.theme-container-primary');
    /* for theme primary */
    const nanoThemes = [
        [
            'nano',
            {

                defaultRepresentation: 'RGB',
                components: {
                    preview: true,
                    opacity: false,
                    hue: true,

                    interaction: {
                        hex: false,
                        rgba: true,
                        hsva: false,
                        input: true,
                        clear: false,
                        save: false
                    }
                }
            }
        ]
    ];
    const nanoButtons = [];
    let nanoPickr = null;
    for (const [theme, config] of nanoThemes) {
        const button = document.createElement('button');
        button.innerHTML = theme;
        nanoButtons.push(button);

        button.addEventListener('click', () => {
            const el = document.createElement('p');
            pickrContainerPrimary.appendChild(el);

            /* Delete previous instance */
            if (nanoPickr) {
                nanoPickr.destroyAndRemove();
            }

            /* Apply active class */
            for (const btn of nanoButtons) {
                btn.classList[btn === button ? 'add' : 'remove']('active');
            }

            /* Create fresh instance */
            nanoPickr = new Pickr(Object.assign({
                el,
                theme,
                default: '#8e54e9'
            }, config));

            /* Set events */
            nanoPickr.on('changestop', (source, instance) => {
                let color = instance.getColor().toRGBA();
                let html = document.querySelector('html');
                html.style.setProperty('--primary-rgb', `${Math.floor(color[0])}, ${Math.floor(color[1])}, ${Math.floor(color[2])}`);
                /* theme color picker */
                localStorage.setItem('primaryRGB', `${Math.floor(color[0])}, ${Math.floor(color[1])}, ${Math.floor(color[2])}`);
                updateColors();
            });
        });

        themeContainerPrimary.appendChild(button);
    }
    nanoButtons[0].click();
    /* for theme primary */
}

/* footer year */
document.getElementById("year").innerHTML = new Date().getFullYear();
/* footer year */

let mainContent;
(function () {
    let html = document.querySelector('html');
    mainContent = document.querySelector('.main-content');

if (document.querySelector("#switcher-canvas")) {
    localStorageBackup();
    switcherClick();
    checkOptions();
    setTimeout(() => {
      checkOptions();
    }, 1000);
  }
})();


function switcherClick() {
    let ltrBtn, rtlBtn, lightBtn, darkBtn, primaryDefaultColor1Btn, primaryDefaultColor2Btn, primaryDefaultColor3Btn, primaryDefaultColor4Btn, primaryDefaultColor5Btn, ResetAll;
    let html = document.querySelector('html');
    lightBtn = document.querySelector('#switcher-light-theme');
    darkBtn = document.querySelector('#switcher-dark-theme');
    ltrBtn = document.querySelector('#switcher-ltr');
    rtlBtn = document.querySelector('#switcher-rtl');
    primaryDefaultColor1Btn = document.querySelector('#switcher-primary');
    primaryDefaultColor2Btn = document.querySelector('#switcher-primary1');
    primaryDefaultColor3Btn = document.querySelector('#switcher-primary2');
    primaryDefaultColor4Btn = document.querySelector('#switcher-primary3');
    primaryDefaultColor5Btn = document.querySelector('#switcher-primary4');
    ResetAll = document.querySelector('#reset-all');

    /* Light Layout Start */
    let lightThemeVar = lightBtn.addEventListener('click', () => {
        lightFn();
        localStorage.setItem("velvetHeader", 'light');
        localStorage.setItem("velvetMenu", 'light');
    })
    /* Light Layout End */

    /* Dark Layout Start */
    let darkThemeVar = darkBtn.addEventListener('click', () => {
        darkFn();
        localStorage.setItem("velvetMenu", 'dark');
        localStorage.setItem("velvetHeader", 'dark');
    });
    /* Dark Layout End */


    // primary theme
    let primaryColor1Var = primaryDefaultColor1Btn.addEventListener('click', () => {
        localStorage.setItem("primaryRGB", "13, 73, 159");
        html.style.setProperty('--primary-rgb', `13, 73, 159`);
        updateColors();
    })
    let primaryColor2Var = primaryDefaultColor2Btn.addEventListener('click', () => {
        localStorage.setItem("primaryRGB", "0, 128, 172");
        html.style.setProperty('--primary-rgb', `0, 128, 172`);
        updateColors();
    })
    let primaryColor3Var = primaryDefaultColor3Btn.addEventListener('click', () => {
        localStorage.setItem("primaryRGB", "100, 48, 124");
        html.style.setProperty('--primary-rgb', `100, 48, 124`);
        updateColors();
    })
    let primaryColor4Var = primaryDefaultColor4Btn.addEventListener('click', () => {
        localStorage.setItem("primaryRGB", "5, 154, 114");
        html.style.setProperty('--primary-rgb', `5, 154, 114`);
        updateColors();
    })
    let primaryColor5Var = primaryDefaultColor5Btn.addEventListener('click', () => {
        localStorage.setItem("primaryRGB", "177, 90, 17");
        html.style.setProperty('--primary-rgb', `177, 90, 17`);
        updateColors();
    })

    /* rtl start */
    let rtlVar = rtlBtn.addEventListener('click', () => {
        localStorage.setItem("velvetrtl", true);
        localStorage.removeItem("velvetltr");
        rtlFn();
    });
    /* rtl end */

    /* ltr start */
    let ltrVar = ltrBtn.addEventListener('click', () => {
        //    local storage
        localStorage.setItem("velvetltr", true);
        localStorage.removeItem("velvetrtl");
        ltrFn();
    });
    /* ltr end */

    // reset all start
    let resetVar = ResetAll.addEventListener('click', () => {

        // clear primary & bg color
        html.style.removeProperty(`--primary-rgb`);

        // clear rtl
        html.removeAttribute('dir', 'rtl');
        html.setAttribute('dir', 'ltr');

        ResetAllFn();
    })
    // reset all start
}

function ltrFn() {
    let html = document.querySelector('html')
    document.querySelector("#style")?.setAttribute("href", "assets/libs/bootstrap/css/bootstrap.min.css");
    html.setAttribute("dir", "ltr");
    document.querySelector('#switcher-ltr').checked = true;
    checkOptions();
}

function rtlFn() {
    let html = document.querySelector('html');
    html.setAttribute("dir", "rtl");
    document.querySelector("#style")?.setAttribute("href", "assets/libs/bootstrap/css/bootstrap.rtl.min.css");
    checkOptions();
}

if(localStorage.velvetrtl){
    rtlFn()
}

function lightFn() {
    let html = document.querySelector('html');
    html.setAttribute('data-theme-mode', 'light');
    document.querySelector('#switcher-light-theme').checked = true;
    updateColors()
    localStorage.removeItem("velvetdarktheme");
    checkOptions();
    // html.style.removeProperty('--primary-rgb');
}

function darkFn() {
    let html = document.querySelector('html');
    html.setAttribute('data-theme-mode', 'dark');
    updateColors()
    localStorage.setItem("velvetdarktheme", true);
    localStorage.removeItem("velvetlighttheme");
    checkOptions();
    // html.style.removeProperty("--primary-rgb");
}

function ResetAllFn() {
    let html = document.querySelector('html');
    checkOptions();

    // clearing localstorage
    localStorage.clear();

    // reseting chart colors
    updateColors();

    // reseting rtl
    ltrFn()

    // reseting dark theme
    lightFn()

    // resetting theme primary
    document.querySelector("#switcher-primary").checked = false;
    document.querySelector("#switcher-primary1").checked = false;
    document.querySelector("#switcher-primary2").checked = false;
    document.querySelector("#switcher-primary3").checked = false;
    document.querySelector("#switcher-primary4").checked = false;

}

function checkOptions() {

    // dark
    if (localStorage.getItem('velvetdarktheme')) {
        document.querySelector('#switcher-dark-theme').checked = true;
    }

    //RTL
    if (localStorage.getItem('velvetrtl')) {
        document.querySelector('#switcher-rtl').checked = true;
    }
}

// chart colors
let myVarVal, primaryRGB
function updateColors() {
    'use strict'
    primaryRGB = getComputedStyle(document.documentElement).getPropertyValue('--primary-rgb').trim();
}
updateColors()

function localStorageBackup() {
    if (localStorage.primaryRGB) {
        if (document.querySelector('.theme-container-primary')) {
            document.querySelector('.theme-container-primary').value = localStorage.primaryRGB;
        }
        document.querySelector('html').style.setProperty('--primary-rgb', localStorage.primaryRGB);
    }
    if (localStorage.velvetdarktheme) {
        let html = document.querySelector('html');
        html.setAttribute('data-theme-mode', 'dark');
    }

    if (localStorage.velvetrtl) {
        let html = document.querySelector('html');
        html.setAttribute('dir', 'rtl');
    }
    if (localStorage.velvetlayout) {
        let html = document.querySelector('html');
        let layoutValue = localStorage.getItem('velvetlayout');
        html.setAttribute('data-nav-layout', 'horizontal');
    }

    if (localStorage.primaryRGB ) {
        if (localStorage.primaryRGB == "13, 73, 159") {
            document.querySelector("#switcher-primary").checked = true;
        }
        if (localStorage.primaryRGB == "0, 128, 172") {
        document.querySelector("#switcher-primary1").checked = true;
        }
        if (localStorage.primaryRGB == "100, 48, 124") {
        document.querySelector("#switcher-primary2").checked = true;
        }
        if (localStorage.primaryRGB == "5, 154, 114") {
        document.querySelector("#switcher-primary3").checked = true;
        }
        if (localStorage.primaryRGB == "177, 90, 17") {
        document.querySelector("#switcher-primary4").checked = true;
        }
    }
}

// for menu target scroll on click
window.addEventListener("scroll", reveal);
function reveal() {
    var reveals = document.querySelectorAll(".reveal");

    for (var i = 0; i < reveals.length; i++) {
        var windowHeight = window.innerHeight;
        var cardTop = reveals[i].getBoundingClientRect().top;
        var cardRevealPoint = 150;

        //   console.log('condition', windowHeight - cardRevealPoint)

        if (cardTop < windowHeight - cardRevealPoint) {
            reveals[i].classList.add("active");
        } else {
            reveals[i].classList.remove("active");
        }
    }
}
reveal();
const pageLink = document.querySelectorAll(".side-menu__item");
pageLink.forEach((elem) => {
    if (elem != 'javascript:void(0);' && elem !== "#") {
        elem.addEventListener("click", (e) => {
            e.preventDefault();
            document.querySelector(elem.getAttribute("href"))?.scrollIntoView({
                behavior: "smooth",
                offsetTop: 1 - 60,
            });
        });
    }
});
// section menu active
function onScroll(event) {
    const sections = document.querySelectorAll(".side-menu__item");
    const scrollPos =
      window.pageYOffset ||
      document.documentElement.scrollTop ||
      document.body.scrollTop;
  
    sections.forEach((elem) => {
      const val = elem.getAttribute("href");
      let refElement;
      if (val != "javascript:void(0);" && val !== "#") {
        refElement = document.querySelector(val);
      }
      const scrollTopMinus = scrollPos + 73;
      if (
        refElement?.offsetTop <= scrollTopMinus &&
        refElement?.offsetTop + refElement.offsetHeight > scrollTopMinus
      ) {
        if (elem.parentElement.parentElement.classList.contains("child1")) {
          elem.parentElement.parentElement.parentElement.children[0].classList.add(
            "active"
          );
        }
        elem.classList.add("active");
        if (elem.closest(".child1")?.previousElementSibling) {
          elem.closest(".child1").previousElementSibling.classList.add("active");
        }
      } else {
        elem.classList.remove("active");
      }
    });
}
window.document.addEventListener("scroll", onScroll);
// for menu target scroll on click

// for testimonials
var swiper = new Swiper(".pagination-dynamic", {
    pagination: {
        el: ".swiper-pagination",
        dynamicBullets: true,
        clickable: true,
    },
    slidesPerView: 1,
    loop: true,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false
    },
    breakpoints: {
        768: {
          slidesPerView: 2,
          spaceBetween: 40,
        },
        1024: {
          slidesPerView: 2,
          spaceBetween: 50,
        },
        1400: {
          slidesPerView: 4,
          spaceBetween: 50,
        },
      },
});

/* back to top */
const scrollToTop = document.querySelector(".scrollToTop");
const $rootElement = document.documentElement;
const $body = document.body;
window.onscroll = () => {
    const scrollTop = window.scrollY || window.pageYOffset;
    const clientHt = $rootElement.scrollHeight - $rootElement.clientHeight;
    if (window.scrollY > 100) {
        scrollToTop.style.display = "flex";
    } else {
        scrollToTop.style.display = "none";
    }
};
scrollToTop.onclick = () => {
    window.scrollTo(0, 0);
};
/* back to top */