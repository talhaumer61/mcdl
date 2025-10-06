"use strict";
    
let mainContent;
(function () {
  let html = document.querySelector("html");
  mainContent = document.querySelector(".main-content");

  if (document.querySelector("#switcher-canvas")) {
    localStorageBackup2();
    switcherClick();
    checkOptions();
    setTimeout(() => {
      checkOptions();
    }, 1000);
  }
})();

function switcherClick() {
  let ltrBtn,
    rtlBtn,
    verticalBtn,
    horiBtn,
    lightBtn,
    darkBtn,
    boxedBtn,
    fullwidthBtn,
    scrollMenuBtn,
    fixedMenuBtn,
    scrollHeaderBtn,
    fixedHeaderBtn,
    roundedHeaderBtn,
    lightMenuBtn,
    darkMenuBtn,
    darkHeaderBtn,
    colorHeaderBtn,
    gradientHeaderBtn,
    transparentHeaderBtn,
    defaultLightHeaderBtn,
    defaultDarkHeaderBtn,
    defaultColorHeaderBtn,
    defaultGradientHeaderBtn,
    defaultTransparentHeaderBtn,
    regular,
    classic,
    defaultBtn,
    closedBtn,
    iconTextBtn,
    detachedBtn,
    overlayBtn,
    doubleBtn,
    resetBtn,
    menuClickBtn,
    menuHoverBtn,
    iconClickBtn,
    iconHoverBtn,
    primaryDefaultColor1Btn,
    primaryDefaultColor2Btn,
    primaryDefaultColor3Btn,
    primaryDefaultColor4Btn,
    primaryDefaultColor5Btn,
    bgDefaultColor1Btn,
    bgDefaultColor2Btn,
    bgDefaultColor3Btn,
    bgDefaultColor4Btn,
    bgDefaultColor5Btn,
    bgImage1Btn,
    bgImage2Btn,
    bgImage3Btn,
    bgImage4Btn,
    bgImage5Btn,
    ResetAll,
    loaderEnable,
    loaderDisable;
  let html = document.querySelector("html");
  lightBtn = document.querySelector("#switcher-light-theme");
  darkBtn = document.querySelector("#switcher-dark-theme");
  ltrBtn = document.querySelector("#switcher-ltr");
  rtlBtn = document.querySelector("#switcher-rtl");
  verticalBtn = document.querySelector("#switcher-vertical");
  horiBtn = document.querySelector("#switcher-horizontal");
  boxedBtn = document.querySelector("#switcher-boxed");
  fullwidthBtn = document.querySelector("#switcher-full-width");
  fixedMenuBtn = document.querySelector("#switcher-menu-fixed");
  scrollMenuBtn = document.querySelector("#switcher-menu-scroll");
  fixedHeaderBtn = document.querySelector("#switcher-header-fixed");
  scrollHeaderBtn = document.querySelector("#switcher-header-scroll");
  roundedHeaderBtn = document.querySelector("#switcher-header-rounded");
  darkHeaderBtn = document.querySelector("#switcher-header-dark");
  colorHeaderBtn = document.querySelector("#switcher-header-primary");
  gradientHeaderBtn = document.querySelector("#switcher-header-gradient");
  transparentHeaderBtn = document.querySelector("#switcher-header-transparent");
  defaultLightHeaderBtn = document.querySelector("#switcher-default-header-light");
  defaultDarkHeaderBtn = document.querySelector("#switcher-default-header-dark");
  defaultColorHeaderBtn = document.querySelector("#switcher-default-header-primary");
  defaultGradientHeaderBtn = document.querySelector("#switcher-default-header-gradient");
  defaultTransparentHeaderBtn = document.querySelector("#switcher-default-header-transparent");
  lightMenuBtn = document.querySelector("#switcher-menu-light");
  darkMenuBtn = document.querySelector("#switcher-menu-dark");
  regular = document.querySelector("#switcher-regular");
  classic = document.querySelector("#switcher-classic");
  defaultBtn = document.querySelector("#switcher-default-menu");
  menuClickBtn = document.querySelector("#switcher-menu-click");
  menuHoverBtn = document.querySelector("#switcher-menu-hover");
  iconClickBtn = document.querySelector("#switcher-icon-click");
  iconHoverBtn = document.querySelector("#switcher-icon-hover");
  closedBtn = document.querySelector("#switcher-closed-menu");
  iconTextBtn = document.querySelector("#switcher-icontext-menu");
  overlayBtn = document.querySelector("#switcher-icon-overlay");
  doubleBtn = document.querySelector("#switcher-double-menu");
  detachedBtn = document.querySelector("#switcher-detached");
  resetBtn = document.querySelector("#resetbtn");
  primaryDefaultColor1Btn = document.querySelector("#switcher-primary");
  primaryDefaultColor2Btn = document.querySelector("#switcher-primary1");
  primaryDefaultColor3Btn = document.querySelector("#switcher-primary2");
  primaryDefaultColor4Btn = document.querySelector("#switcher-primary3");
  primaryDefaultColor5Btn = document.querySelector("#switcher-primary4");
  bgDefaultColor1Btn = document.querySelector("#switcher-background");
  bgDefaultColor2Btn = document.querySelector("#switcher-background1");
  bgDefaultColor3Btn = document.querySelector("#switcher-background2");
  bgDefaultColor4Btn = document.querySelector("#switcher-background3");
  bgDefaultColor5Btn = document.querySelector("#switcher-background4");
  bgImage1Btn = document.querySelector("#switcher-bg-img");
  bgImage2Btn = document.querySelector("#switcher-bg-img1");
  bgImage3Btn = document.querySelector("#switcher-bg-img2");
  bgImage4Btn = document.querySelector("#switcher-bg-img3");
  bgImage5Btn = document.querySelector("#switcher-bg-img4");
  ResetAll = document.querySelector("#reset-all");
  loaderEnable = document.querySelector('#switcher-loader-enable');
  loaderDisable = document.querySelector('#switcher-loader-disable');

  // primary theme
  let primaryColor1Var = primaryDefaultColor1Btn.addEventListener(
    "click",
    () => {
      localStorage.setItem("primaryRGB", "13, 73, 159");
      html.style.setProperty("--primary-rgb", `13, 73, 159`);
      updateColors();
    }
  );
  let primaryColor2Var = primaryDefaultColor2Btn.addEventListener(
    "click",
    () => {
      localStorage.setItem("primaryRGB", "0, 128, 172");
      html.style.setProperty("--primary-rgb", `0, 128, 172`);
      updateColors();
    }
  );
  let primaryColor3Var = primaryDefaultColor3Btn.addEventListener(
    "click",
    () => {
      localStorage.setItem("primaryRGB", "100, 48, 124");
      html.style.setProperty("--primary-rgb", ` 100, 48, 124`);
      updateColors();
    }
  );
  let primaryColor4Var = primaryDefaultColor4Btn.addEventListener(
    "click",
    () => {
      localStorage.setItem("primaryRGB", "5, 154, 114");
      html.style.setProperty("--primary-rgb", `5, 154, 114`);
      updateColors();
    }
  );
  let primaryColor5Var = primaryDefaultColor5Btn.addEventListener(
    "click",
    () => {
      localStorage.setItem("primaryRGB", "177, 90, 17");
      html.style.setProperty("--primary-rgb", `177, 90, 17`);
      updateColors();
    }
  );

  // Background theme
  let backgroundColor1Var = bgDefaultColor1Btn.addEventListener("click", () => {
    localStorage.setItem("bodyBgRGB", "20, 30, 96");
    localStorage.setItem("bodyBgRGB2", `${20 + 14} ${30 + 14} ${96 + 14}`);
    localStorage.setItem("bodylightRGB", "25, 38, 101");
    localStorage.removeItem("velvetMenu");
    localStorage.removeItem("velvetHeader");
    localStorage.removeItem("velvetDefaultHeader");
    html.setAttribute("data-theme-mode", "dark");
    html.setAttribute("data-menu-styles", "dark");
    html.setAttribute("data-header-styles", "dark");
    document.querySelector("html").style.setProperty("--body-bg-rgb", localStorage.bodyBgRGB);
    document.querySelector("html").style.setProperty("--body-bg-rgb2", localStorage.bodyBgRGB2);
    document.querySelector("html").style.setProperty("--light-rgb", "25, 38, 101");
    document.querySelector("html").style.setProperty("--form-control-bg", "rgb(25, 38, 101)");
    document.querySelector("html").style.setProperty("--input-border", "rgba(255,255,255,0.1)");
    html.removeAttribute("data-default-header-styles");
    document.querySelector("#switcher-dark-theme").checked = true;
    document.querySelector("#switcher-menu-dark").checked = true;
    document.querySelector("#switcher-header-dark").checked = true;
  });
  let backgroundColor2Var = bgDefaultColor2Btn.addEventListener("click", () => {
    localStorage.setItem("bodyBgRGB", "8, 78, 115");
    localStorage.setItem("bodyBgRGB2", `${8 + 14} ${78 + 14} ${115 + 14}`);
    localStorage.setItem("bodylightRGB", "13, 86, 120");
    localStorage.removeItem("velvetMenu");
    localStorage.removeItem("velvetHeader");
    localStorage.removeItem("velvetDefaultHeader");
    html.setAttribute("data-theme-mode", "dark");
    html.setAttribute("data-menu-styles", "dark");
    html.setAttribute("data-header-styles", "dark");
    document.querySelector("html").style.setProperty("--body-bg-rgb", localStorage.bodyBgRGB);
    document.querySelector("html").style.setProperty("--body-bg-rgb2", localStorage.bodyBgRGB2);
    document.querySelector("html").style.setProperty("--light-rgb", "13, 86, 120");
    document.querySelector("html").style.setProperty("--form-control-bg", "rgb(13, 86, 120)");
    document.querySelector("html").style.setProperty("--input-border", "rgba(255,255,255,0.1)");
    html.removeAttribute("data-default-header-styles");
    document.querySelector("#switcher-dark-theme").checked = true;
    document.querySelector("#switcher-menu-dark").checked = true;
    document.querySelector("#switcher-header-dark").checked = true;
  });
  let backgroundColor3Var = bgDefaultColor3Btn.addEventListener("click", () => {
    localStorage.setItem("bodyBgRGB", "90, 37, 135");
    localStorage.setItem("bodyBgRGB2", `${90 + 14} ${37 + 14} ${135 + 14}`);
    localStorage.setItem("bodylightRGB", "95, 45, 140");
    localStorage.removeItem("velvetMenu");
    localStorage.removeItem("velvetHeader");
    localStorage.removeItem("velvetDefaultHeader");
    html.setAttribute("data-theme-mode", "dark");
    html.setAttribute("data-menu-styles", "dark");
    html.setAttribute("data-header-styles", "dark");
    document.querySelector("html").style.setProperty("--body-bg-rgb", localStorage.bodyBgRGB);
    document.querySelector("html").style.setProperty("--body-bg-rgb2", localStorage.bodyBgRGB2);
    document.querySelector("html").style.setProperty("--light-rgb", "95, 45, 140");
    document.querySelector("html").style.setProperty("--form-control-bg", "rgb(95, 45, 140)");
    document.querySelector("html").style.setProperty("--input-border", "rgba(255,255,255,0.1)");
    html.removeAttribute("data-default-header-styles");
    document.querySelector("#switcher-dark-theme").checked = true;
    document.querySelector("#switcher-menu-dark").checked = true;
    document.querySelector("#switcher-header-dark").checked = true;
  });
  let backgroundColor4Var = bgDefaultColor4Btn.addEventListener("click", () => {
    localStorage.setItem("bodyBgRGB", "24, 101, 51");
    localStorage.setItem("bodyBgRGB2", `${24 + 14} ${101 + 14} ${51 + 14}`);
    localStorage.setItem("bodylightRGB", "29, 109, 56");
    localStorage.removeItem("velvetMenu");
    localStorage.removeItem("velvetHeader");
    localStorage.removeItem("velvetDefaultHeader");
    html.setAttribute("data-theme-mode", "dark");
    html.setAttribute("data-menu-styles", "dark");
    html.setAttribute("data-header-styles", "dark");
    document.querySelector("html").style.setProperty("--body-bg-rgb", localStorage.bodyBgRGB);
    document.querySelector("html").style.setProperty("--body-bg-rgb2", localStorage.bodyBgRGB2);
    document.querySelector("html").style.setProperty("--light-rgb", "29, 109, 56");
    document.querySelector("html").style.setProperty("--form-control-bg", "rgb(29, 109, 56)");
    document.querySelector("html").style.setProperty("--input-border", "rgba(255,255,255,0.1)");
    html.removeAttribute("data-default-header-styles");
    document.querySelector("#switcher-dark-theme").checked = true;
    document.querySelector("#switcher-menu-dark").checked = true;
    document.querySelector("#switcher-header-dark").checked = true;
  });
  let backgroundColor5Var = bgDefaultColor5Btn.addEventListener("click", () => {
    localStorage.setItem("bodyBgRGB", "120, 66, 20");
    localStorage.setItem("bodyBgRGB2", `${120 + 14} ${66 + 14} ${20 + 14}`);
    localStorage.setItem("bodylightRGB", "125, 74, 25");
    localStorage.removeItem("velvetMenu");
    localStorage.removeItem("velvetHeader");
    localStorage.removeItem("velvetDefaultHeader");
    html.setAttribute("data-theme-mode", "dark");
    html.setAttribute("data-menu-styles", "dark");
    html.setAttribute("data-header-styles", "dark");
    document.querySelector("html").style.setProperty("--body-bg-rgb", localStorage.bodyBgRGB);
    document.querySelector("html").style.setProperty("--body-bg-rgb2", localStorage.bodyBgRGB2);
    document.querySelector("html").style.setProperty("--light-rgb", "125, 74, 25");
    document.querySelector("html").style.setProperty("--form-control-bg", "rgb(125, 74, 25)");
    document.querySelector("html").style.setProperty("--input-border", "rgba(255,255,255,0.1)");
    html.removeAttribute("data-default-header-styles");
    document.querySelector("#switcher-dark-theme").checked = true;
    document.querySelector("#switcher-menu-dark").checked = true;
    document.querySelector("#switcher-header-dark").checked = true;
  });

  // Bg image
  let bgImg1Var = bgImage1Btn.addEventListener("click", () => {
    html.setAttribute("data-bg-img", "bgimg1");
    localStorage.setItem("bgimg", "bgimg1");
  });
  let bgImg2Var = bgImage2Btn.addEventListener("click", () => {
    html.setAttribute("data-bg-img", "bgimg2");
    localStorage.setItem("bgimg", "bgimg2");
  });
  let bgImg3Var = bgImage3Btn.addEventListener("click", () => {
    html.setAttribute("data-bg-img", "bgimg3");
    localStorage.setItem("bgimg", "bgimg3");
  });
  let bgImg4Var = bgImage4Btn.addEventListener("click", () => {
    html.setAttribute("data-bg-img", "bgimg4");
    localStorage.setItem("bgimg", "bgimg4");
  });
  let bgImg5Var = bgImage5Btn.addEventListener("click", () => {
    html.setAttribute("data-bg-img", "bgimg5");
    localStorage.setItem("bgimg", "bgimg5");
  });

  /* Light Layout Start */
  let lightThemeVar = lightBtn.addEventListener("click", () => {
    lightFn();
    localStorage.setItem("velvetHeader", "gradient");
    localStorage.removeItem("bodylightRGB");
    localStorage.removeItem("bodyBgRGB");
    localStorage.removeItem("velvetMenu");
    localStorage.removeItem("velvetDefaultHeader");
    let html = document.querySelector('html');
    html.removeAttribute('data-default-header-styles');
  });
  /* Light Layout End */

  /* Dark Layout Start */
  let darkThemeVar = darkBtn.addEventListener("click", () => {
    darkFn();
    localStorage.setItem("velvetMenu", "dark");
    localStorage.setItem("velvetHeader", "gradient");
    localStorage.removeItem("velvetDefaultHeader");
    let html = document.querySelector('html');
    html.removeAttribute('data-default-header-styles');
  });
  /* Dark Layout End */

  /* Light Menu Start */
  let lightMenuVar = lightMenuBtn.addEventListener("click", () => {
    html.setAttribute("data-menu-styles", "light");
    localStorage.setItem("velvetMenu", "light");
  });
  /* Light Menu End */

  /* Dark Menu Start */
  let darkMenuVar = darkMenuBtn.addEventListener("click", () => {
    html.setAttribute("data-menu-styles", "dark");
    localStorage.setItem("velvetMenu", "dark");
  });
  /* Dark Menu End */

  /* Color Header Start */
  let colorHeaderVar = colorHeaderBtn.addEventListener("click", () => {
    html.setAttribute("data-header-styles", "color");
    localStorage.setItem("velvetHeader", "color");
    html.removeAttribute("data-default-header-styles");
  });
  /* Color Header End */

  /* Dark Header Start */
  let darkHeaderVar = darkHeaderBtn.addEventListener("click", () => {
    html.setAttribute("data-header-styles", "dark");
    localStorage.setItem("velvetHeader", "dark");
    html.removeAttribute("data-default-header-styles");
  });
  /* Dark Header End */

  /* Gradient Header Start */
  let gradientHeaderVar = gradientHeaderBtn.addEventListener("click", () => {
    html.setAttribute("data-header-styles", "gradient");
    localStorage.setItem("velvetHeader", "gradient");
    html.removeAttribute("data-default-header-styles");
  });
  /* Gradient Header End */

  /* Transparent Header Start */
  let transparentHeaderVar = transparentHeaderBtn.addEventListener(
    "click",
    () => {
      html.setAttribute("data-header-styles", "transparent");
      localStorage.setItem("velvetHeader", "transparent");
      html.removeAttribute("data-default-header-styles");
    }
  );
  /* Transparent Header End */

  /* Default Light Header Start */
  let defaultLightHeaderVar =  defaultLightHeaderBtn.addEventListener("click",() => {
      html.setAttribute("data-default-header-styles", "light");
      html.removeAttribute("data-header-styles");
      localStorage.setItem("velvetDefaultHeader", "light");
    }
  );
  /* Default Light Header End */

  /* Default Color Header Start */
  let defaultColorHeaderVar = defaultColorHeaderBtn.addEventListener("click", () => {
    html.setAttribute("data-default-header-styles", "color");
    html.removeAttribute("data-header-styles");
    localStorage.setItem("velvetDefaultHeader", "color");
  });
  /* Default Color Header End */

  /* Default Dark Header Start */
  let defaultDarkHeaderVar =  defaultDarkHeaderBtn.addEventListener("click", () => {
    html.setAttribute("data-default-header-styles", "dark");
    html.removeAttribute("data-header-styles");
    localStorage.setItem("velvetDefaultHeader", "dark");
  });
  /* Default Dark Header End */

  /* Default sGradient Header Start */
  let defaultGradientHeaderVar =  defaultGradientHeaderBtn.addEventListener("click", () => {
    html.setAttribute("data-default-header-styles", "gradient");
    html.removeAttribute("data-header-styles");
    localStorage.setItem("velvetDefaultHeader", "gradient");
  });
  /* Default Gradient Header End */

  /* Default Transparent Header Start */
  let defaultTransparentHeaderVar =  defaultTransparentHeaderBtn.addEventListener("click", () => {
      html.setAttribute("data-default-header-styles", "transparent");
      html.removeAttribute("data-header-styles");
      localStorage.setItem("velvetDefaultHeader", "transparent");
    }
  );
  /* Default Transparent Header End */

  /* Full Width Layout Start */
  let fullwidthVar = fullwidthBtn.addEventListener("click", () => {
    html.setAttribute("data-width", "fullwidth");
    localStorage.setItem("velvetfullwidth", true);
    localStorage.removeItem("velvetboxed");
  });
  /* Full Width Layout End */

  /* Boxed Layout Start */
  let boxedVar = boxedBtn.addEventListener("click", () => {
    html.setAttribute("data-width", "boxed");
    html.removeAttribute("data-header-position", "rounded"); 
    localStorage.setItem("velvetboxed", true);
    localStorage.removeItem("velvetfullwidth");
    checkHoriMenu();
  });
  /* Boxed Layout End */

  /* Regular page style Start */
  let shadowVar = regular.addEventListener("click", () => {
    html.setAttribute("data-page-style", "regular");
    localStorage.setItem("velvetregular", true);
    localStorage.removeItem("velvetclassic");
  });
  /* Regular page style End */

  /* Classic page style Start */
  let noShadowVar = classic.addEventListener("click", () => {
    html.setAttribute("data-page-style", "classic");
    localStorage.setItem("velvetclassic", true);
    localStorage.removeItem("velvetregular");
  });
  /* Classic page style End */

  /* Header-Position Styles Start */
  let fixedHeaderVar = fixedHeaderBtn.addEventListener("click", () => {
    html.setAttribute("data-header-position", "fixed");
    localStorage.setItem("velvetheaderfixed", true);
    localStorage.removeItem("velvetheaderscrollable");
    localStorage.removeItem("velvetheaderrounded");
  });

  let roundedHeaderVar = roundedHeaderBtn.addEventListener("click", () => {
    html.setAttribute("data-header-position", "rounded");
    localStorage.setItem("velvetheaderrounded", true);
    localStorage.removeItem("velvetheaderscrollable");
    localStorage.removeItem("velvetheaderfixed");
  });

  let scrollHeaderVar = scrollHeaderBtn.addEventListener("click", () => {
    html.setAttribute("data-header-position", "scrollable");
    localStorage.setItem("velvetheaderscrollable", true);
    localStorage.removeItem("velvetheaderfixed");
    localStorage.removeItem("velvetheaderrounded");
  });
  /* Header-Position Styles End */

  /* Menu-Position Styles Start */
  let fixedMenuVar = fixedMenuBtn.addEventListener("click", () => {
    html.setAttribute("data-menu-position", "fixed");
    localStorage.setItem("velvetmenufixed", true);
    localStorage.removeItem("velvetmenuscrollable");
  });

  let scrollMenuVar = scrollMenuBtn.addEventListener("click", () => {
    html.setAttribute("data-menu-position", "scrollable");
    localStorage.setItem("velvetmenuscrollable", true);
    localStorage.removeItem("velvetmenufixed");
  });
  /* Menu-Position Styles End */

  /* Default Sidemenu Start */
  let defaultVar = defaultBtn.addEventListener("click", () => {
    html.setAttribute("data-vertical-style", "default");
    html.setAttribute("data-nav-layout", "vertical");
    toggleSidemenu();
    localStorage.removeItem("velvetverticalstyles");
    localStorage.setItem("velvetverticalstyles", "default");
    document.querySelectorAll(".main-menu>li.open").forEach((ele) => {
      if (!ele.classList.contains("active")) {
        ele.classList.remove("open");
        ele.querySelector("ul").style.display = "none";
      }
    });
  });
  /* Default Sidemenu End */

  /* Closed Sidemenu Start */
  let closedVar = closedBtn.addEventListener("click", () => {
    closedSidemenuFn();
    localStorage.setItem("velvetverticalstyles", "closed");
    document.querySelectorAll(".main-menu>li.open").forEach((ele) => {
      if (!ele.classList.contains("active")) {
        ele.classList.remove("open");
        ele.querySelector("ul").style.display = "none";
      }
    });
  });
  /* Closed Sidemenu End */

  /* Hover Submenu Start */
  let detachedVar = detachedBtn.addEventListener("click", () => {
    detachedFn();
    localStorage.setItem("velvetverticalstyles", "detached");
    html.removeAttribute("data-nav-style", "");
  });
  /* Hover Submenu End */

  /* Icon Text Sidemenu Start */
  let iconTextVar = iconTextBtn.addEventListener("click", () => {
    iconTextFn();
    localStorage.setItem("velvetverticalstyles", "icontext");
    document.querySelectorAll(".main-menu>li.open").forEach((ele) => {
      if (!ele.classList.contains("active")) {
        ele.classList.remove("open");
        ele.querySelector("ul").style.display = "none";
      }
    });
  });
  /* Icon Text Sidemenu End */

  /* Icon Overlay Sidemenu Start */
  let overlayVar = overlayBtn.addEventListener("click", () => {
    iconOverayFn();
    localStorage.setItem("velvetverticalstyles", "overlay");
    document.querySelectorAll(".main-menu>li.open").forEach((ele) => {
      if (!ele.classList.contains("active")) {
        ele.classList.remove("open");
        ele.querySelector("ul").style.display = "none";
      }
    });
  });
  /* Icon Overlay Sidemenu End */

  /* doublemenu Sidemenu Start */
  let doubleVar = doubleBtn.addEventListener("click", () => {
    doubletFn();
    localStorage.setItem("velvetverticalstyles", "doublemenu");
    document.querySelectorAll(".main-menu>li.open").forEach((ele) => {
      if (!ele.classList.contains("active")) {
        ele.classList.remove("open");
        ele.querySelector("ul").style.display = "none";
      }
    });
  });
  /* doublemenu Sidemenu End */

  /* Menu Click Sidemenu Start */
  let menuClickVar = menuClickBtn.addEventListener("click", () => {
    html.removeAttribute("data-vertical-style");
    menuClickFn();
    localStorage.setItem("velvetnavstyles", "menu-click");
    localStorage.removeItem("velvetverticalstyles");
    document.querySelectorAll(".main-menu>li.open").forEach((ele) => {
      if (!ele.classList.contains("active")) {
        ele.classList.remove("open");
        ele.querySelector("ul").style.display = "none";
      }
    });
    if (
      document.querySelector("html").getAttribute("data-nav-layout") ==
      "horizontal"
    ) {
      document.querySelector(".main-menu").style.marginLeft = "0px";
      document.querySelector(".main-menu").style.marginRight = "0px";
      ResizeMenu();
    }
  });
  /* Menu Click Sidemenu End */

  /* Menu Hover Sidemenu Start */
  let menuhoverVar = menuHoverBtn.addEventListener("click", () => {
    html.removeAttribute("data-vertical-style");
    menuhoverFn();
    localStorage.setItem("velvetnavstyles", "menu-hover");
    localStorage.removeItem("velvetverticalstyles");
    document.querySelectorAll(".main-menu>li.open").forEach((ele) => {
      if (!ele.classList.contains("active")) {
        ele.classList.remove("open");
        ele.querySelector("ul").style.display = "none";
      }
    });

    if (
      document.querySelector("html").getAttribute("data-nav-layout") ==
      "horizontal"
    ) {
      document.querySelector(".main-menu").style.marginLeft = "0px";
      document.querySelector(".main-menu").style.marginRight = "0px";
      ResizeMenu();
    }
  });
  /* Menu Hover Sidemenu End */

  /* icon Click Sidemenu Start */
  let iconClickVar = iconClickBtn.addEventListener("click", () => {
    html.removeAttribute("data-vertical-style");
    iconClickFn();
    document.querySelectorAll(".main-menu>li.open").forEach((ele) => {
      if (!ele.classList.contains("active")) {
        ele.classList.remove("open");
        ele.querySelector("ul").style.display = "none";
      }
    });
    localStorage.setItem("velvetnavstyles", "icon-click");
    localStorage.removeItem("velvetverticalstyles");

    if (
      document.querySelector("html").getAttribute("data-nav-layout") ==
      "horizontal"
    ) {
      document.querySelector(".main-menu").style.marginLeft = "0px";
      document.querySelector(".main-menu").style.marginRight = "0px";
      ResizeMenu();
      document.querySelector("#slide-left").classList.add("d-none");
    }
  });
  /* icon Click Sidemenu End */

  /* icon hover Sidemenu Start */
  let iconhoverVar = iconHoverBtn.addEventListener("click", () => {
    html.removeAttribute("data-vertical-style");
    iconHoverFn();
    document.querySelectorAll(".main-menu>li.open").forEach((ele) => {
      if (!ele.classList.contains("active")) {
        ele.classList.remove("open");
        ele.querySelector("ul").style.display = "none";
      }
    });
    localStorage.setItem("velvetnavstyles", "icon-hover");
    localStorage.removeItem("velvetverticalstyles");

    if (
      document.querySelector("html").getAttribute("data-nav-layout") ==
      "horizontal"
    ) {
      document.querySelector(".main-menu").style.marginLeft = "0px";
      document.querySelector(".main-menu").style.marginRight = "0px";
      ResizeMenu();
      document.querySelector("#slide-left").classList.add("d-none");
    }
  });
  /* icon hover Sidemenu End */

  /* Sidemenu start*/
  let verticalVar = verticalBtn.addEventListener("click", () => {
    let mainContent = document.querySelector(".main-content");
    // local storage
    localStorage.removeItem("velvetlayout");
    localStorage.setItem("velvetverticalstyles", "default");
    verticalFn();
    setNavActive();
    mainContent.removeEventListener("click", clearNavDropdown);

    //
    document.querySelector(".main-menu").style.marginLeft = "0px";
    document.querySelector(".main-menu").style.marginRight = "0px";

    document.querySelectorAll(".slide").forEach((element) => {
      if (
        element.classList.contains("open") &&
        !element.classList.contains("active")
      ) {
        element.querySelector("ul").style.display = "none";
      }
    });
  });
  /* Sidemenu end */

  /* horizontal start*/
  let horiVar = horiBtn.addEventListener("click", () => {
    let mainContent = document.querySelector(".main-content");
    html.removeAttribute("data-vertical-style");
    //    local storage
    localStorage.setItem("velvetlayout", "horizontal");
    localStorage.removeItem("velvetverticalstyles");

    horizontalClickFn();
    clearNavDropdown();
    mainContent.addEventListener("click", clearNavDropdown);
  });
  /* horizontal end*/

  /* rtl start */
  let rtlVar = rtlBtn.addEventListener("click", () => {
    localStorage.setItem("velvetrtl", true);
    localStorage.removeItem("velvetltr");
    rtlFn();
    if (document.querySelector(".noUi-target")) {
      document.querySelectorAll(".noUi-origin").forEach((e) => {
        e.classList.add("transform-none");
      });
    }
  });
  /* rtl end */

  /* ltr start */
  let ltrVar = ltrBtn.addEventListener("click", () => {
    //    local storage
    localStorage.setItem("velvetltr", true);
    localStorage.removeItem("velvetrtl");
    ltrFn();
    if (document.querySelector(".noUi-target")) {
      document.querySelectorAll(".noUi-origin").forEach((e) => {
        e.classList.remove("transform-none");
      });
    }
  });
  /* ltr end */

  // reset all start
  let resetVar = ResetAll.addEventListener("click", () => {
    ResetAllFn();
    setNavActive();
    document.querySelector("html").setAttribute("data-menu-styles", "dark");
    document.querySelector("#switcher-menu-dark").checked = true;
    document.querySelectorAll(".slide").forEach((element) => {
      if (
        element.classList.contains("open") &&
        !element.classList.contains("active")
      ) {
        element.querySelector("ul").style.display = "none";
      }
    });
  });
  // reset all end

  /* loader start */
  loaderEnable.onclick = ()=>{
      document.querySelector("html").setAttribute("loader","enable");
      localStorage.setItem("loaderEnable","true")
  }

  loaderDisable.onclick = ()=>{
      document.querySelector("html").setAttribute("loader","disable");
      localStorage.setItem("loaderEnable","false")
  }
  /* loader end */
}

function ltrFn() {
  let html = document.querySelector("html");
  if (!document.querySelector("#style").href.includes("bootstrap.min.css")) {
    document
      .querySelector("#style")
      ?.setAttribute("href", "assets/libs/bootstrap/css/bootstrap.min.css");
  }
  html.setAttribute("dir", "ltr");
  document.querySelector("#switcher-ltr").checked = true;
  checkOptions();
}

function rtlFn() {
  let html = document.querySelector("html");
  html.setAttribute("dir", "rtl");
  document
    .querySelector("#style")
    ?.setAttribute(
      "href",
      "assets/libs/bootstrap/css/bootstrap.rtl.min.css"
    );
  checkOptions();
}

function lightFn() {
  let html = document.querySelector("html");
  html.setAttribute("data-theme-mode", "light");
  html.setAttribute("data-header-styles", "gradient");
  html.setAttribute("data-menu-styles", "dark");
  if(!localStorage.getItem('primaryRGB')){
    html.setAttribute('style','')
  }
  document.querySelector("#switcher-light-theme").checked = true;
  document.querySelector("#switcher-menu-dark").checked = true;
  document.querySelector("#switcher-header-gradient").checked = true;
  updateColors()
  localStorage.removeItem("velvetdarktheme");
  localStorage.removeItem("velvetbgColor");
  localStorage.removeItem("velvetheaderbg");
  localStorage.removeItem("velvetbgwhite");
  localStorage.removeItem("velvetmenubg");
  localStorage.removeItem("velvetDefaultHeader");
  checkOptions();
  html.style.removeProperty("--body-bg-rgb");
  html.style.removeProperty("--body-bg-rgb2");
  html.style.removeProperty("--light-rgb");
  html.style.removeProperty("--form-control-bg");
  html.style.removeProperty("--input-border");

  document.querySelector("#switcher-menu-dark").checked = true;
  document.querySelector("#switcher-header-gradient").checked = true;

  // resetting theme background
  document.querySelector("#switcher-background").checked = false;
  document.querySelector("#switcher-background1").checked = false;
  document.querySelector("#switcher-background2").checked = false;
  document.querySelector("#switcher-background3").checked = false;
  document.querySelector("#switcher-background4").checked = false;
}

function darkFn() {
  let html = document.querySelector("html");
  html.setAttribute("data-theme-mode", "dark");
  html.setAttribute("data-header-styles", "gradient");
  html.setAttribute("data-menu-styles", "dark");
  if(!localStorage.getItem('primaryRGB')){
    html.setAttribute('style','')
  }   
  document.querySelector("#switcher-menu-dark").checked = true;
  document.querySelector("#switcher-header-gradient").checked = true;
  document.querySelector("html").style.removeProperty("--body-bg-rgb");
  document.querySelector("html").style.removeProperty("--body-bg-rgb2");
  document.querySelector("html").style.removeProperty("--light-rgb");
  document.querySelector("html").style.removeProperty("--form-control-bg");
  document.querySelector("html").style.removeProperty("--input-border");
  updateColors()
  localStorage.setItem("velvetdarktheme", true);
  localStorage.removeItem("velvetlighttheme");
  localStorage.removeItem("velvetbgColor");
  localStorage.removeItem("velvetheaderbg");
  localStorage.removeItem("velvetbgwhite");
  localStorage.removeItem("velvetmenubg");
  localStorage.removeItem("velvetDefaultHeader");
  checkOptions();
  document.querySelector("#switcher-menu-dark").checked = true;
  document.querySelector("#switcher-header-gradient").checked = true;

  // resetting theme background
  document.querySelector("#switcher-background").checked = false;
  document.querySelector("#switcher-background1").checked = false;
  document.querySelector("#switcher-background2").checked = false;
  document.querySelector("#switcher-background3").checked = false;
  document.querySelector("#switcher-background4").checked = false;
  
}

function verticalFn() {
  let html = document.querySelector("html");
  html.setAttribute("data-nav-layout", "vertical");
  html.setAttribute('data-vertical-style', 'overlay');
  html.removeAttribute("data-nav-style");
  localStorage.removeItem("velvetnavstyles");
  html.removeAttribute('data-toggled');
  document.querySelector("#switcher-vertical").checked = true;
  document.querySelector("#switcher-menu-click").checked = false;
  document.querySelector("#switcher-menu-hover").checked = false;
  document.querySelector("#switcher-icon-click").checked = false;
  document.querySelector("#switcher-icon-hover").checked = false;
  checkOptions();
}

function horizontalClickFn() {
  document.querySelector("#switcher-horizontal").checked = true;
  document.querySelector("#switcher-menu-click").checked = true;
  document.querySelector("#switcher-header-gradient").checked = true;
  let html = document.querySelector("html");
  html.setAttribute("data-nav-layout", "horizontal");
  html.removeAttribute("data-vertical-style");
  if (!html.getAttribute("data-nav-style")) {
    html.setAttribute("data-nav-style", "menu-click");
  }
  if(!localStorage.velvetMenu && !localStorage.bodylightRGB){
    html.setAttribute("data-menu-styles","light")
    document.querySelector('#switcher-menu-light').checked = true;
    checkOptions();
  }
  checkOptions();
  checkHoriMenu();
}

function ResetAllFn() {
  let html = document.querySelector("html");
  if (localStorage.getItem("velvetlayout") == "horizontal") {
    document.querySelector(".main-menu").style.display = "block";
  }
  checkOptions();

  // clearing localstorage
  localStorage.clear();

  // reseting to light
  lightFn();

  //To reset the light-rgb
  document.querySelector("html").removeAttribute("style");

  // clearing attibutes
  // removing header, menu, pageStyle & boxed
  html.removeAttribute("data-nav-style");
  html.removeAttribute("data-menu-position");
  html.removeAttribute("data-header-position");
  html.removeAttribute("data-width");
  html.removeAttribute("data-page-style");
  html.removeAttribute("data-default-header-styles");

  // removing theme styles
  html.removeAttribute("data-bg-img");

  // clear primary & bg color
  html.style.removeProperty(`--primary-rgb`);
  html.style.removeProperty(`--body-bg-rgb`);
  html.style.removeProperty(`--body-bg-rgb2`);

  // reseting to ltr
  ltrFn();

  // reseting to vertical
  verticalFn();
  mainContent.removeEventListener("click", clearNavDropdown);

  // reseting page style
  document.querySelector("#switcher-classic").checked = false;
  document.querySelector("#switcher-regular").checked = true;

  // reseting layout width styles
  document.querySelector("#switcher-full-width").checked = true;
  document.querySelector("#switcher-boxed").checked = false;

  // reseting menu position styles
  document.querySelector("#switcher-menu-fixed").checked = true;
  document.querySelector("#switcher-menu-scroll").checked = false;

  // reseting header position styles
  document.querySelector("#switcher-header-fixed").checked = true;
  document.querySelector("#switcher-header-rounded").checked = false;
  document.querySelector("#switcher-header-scroll").checked = false;

  // reseting sidemenu layout styles
  document.querySelector("#switcher-default-menu").checked = true;
  document.querySelector("#switcher-closed-menu").checked = false;
  document.querySelector("#switcher-icontext-menu").checked = false;
  document.querySelector("#switcher-icon-overlay").checked = false;
  document.querySelector("#switcher-detached").checked = false;
  document.querySelector("#switcher-double-menu").checked = false;

  // resetting theme primary
  document.querySelector("#switcher-primary").checked = false;
  document.querySelector("#switcher-primary1").checked = false;
  document.querySelector("#switcher-primary2").checked = false;
  document.querySelector("#switcher-primary3").checked = false;
  document.querySelector("#switcher-primary4").checked = false;

  // resetting theme background
  document.querySelector("#switcher-background").checked = false;
  document.querySelector("#switcher-background1").checked = false;
  document.querySelector("#switcher-background2").checked = false;
  document.querySelector("#switcher-background3").checked = false;
  document.querySelector("#switcher-background4").checked = false;

  // reseting chart colors
  updateColors();
  
  html.setAttribute("data-vertical-style", "overlay");

  // to reset hrizontal menu scroll
  document.querySelector(".main-menu").style.marginLeft = "0px";
  document.querySelector(".main-menu").style.marginRight = "0px";
}

function checkOptions() {
  if (document.querySelector("html").getAttribute("data-vertical-style") === "detached") {
    document.querySelector("#switcher-detached").checked = true;
  }

  // dark
  if (localStorage.getItem("velvetdarktheme")) {
    document.querySelector("#switcher-dark-theme").checked = true;
  }

  // horizontal
  if (localStorage.getItem("velvetlayout") === "horizontal") {
    document.querySelector("#switcher-horizontal").checked = true;
    document.querySelector("#switcher-menu-click").checked = true;
  } else {
    document.querySelector("#switcher-vertical").checked = true;
  }

  //RTL
  if (localStorage.getItem("velvetrtl")) {
    document.querySelector("#switcher-rtl").checked = true;
  } else {
    document.querySelector("#switcher-ltr").checked = true;
  }

  // color header
  if (localStorage.getItem("velvetHeader") === "color") {
    document.querySelector("#switcher-header-primary").checked = true;
  }

  // gradient header
  if (localStorage.getItem("velvetHeader") === "gradient") {
    document.querySelector("#switcher-header-gradient").checked = true;
  }

  // dark header
  if (localStorage.getItem("velvetHeader") === "dark") {
    document.querySelector("#switcher-header-dark").checked = true;
  }
  // transparent header
  if (localStorage.getItem("velvetHeader") === "transparent") {
    document.querySelector("#switcher-header-transparent").checked = true;
  }

  // light header
  if (localStorage.getItem("velvetDefaultHeader") === "light") {
    document.querySelector("#switcher-default-header-light").checked = true;
  }

  // color header
  if (localStorage.getItem("velvetDefaultHeader") === "color") {
    document.querySelector("#switcher-default-header-primary").checked = true;
  }

  // gradient header
  if (localStorage.getItem("velvetDefaultHeader") === "gradient") {
    document.querySelector("#switcher-default-header-gradient").checked = true;
  }

  // dark header
  if (localStorage.getItem("velvetDefaultHeader") === "dark") {
    document.querySelector("#switcher-default-header-dark").checked = true;
  }
  // transparent header
  if (localStorage.getItem("velvetDefaultHeader") === "transparent") {
    document.querySelector("#switcher-default-header-transparent").checked = true;
  }

  // light menu
  if (localStorage.getItem("velvetMenu") === "light") {
    document.querySelector("#switcher-menu-light").checked = true;
  }

  // dark menu
  if (localStorage.getItem("velvetMenu") === "dark") {
    document.querySelector("#switcher-menu-dark").checked = true;
  }

  //boxed
  if (localStorage.getItem("velvetboxed")) {
    document.querySelector("#switcher-boxed").checked = true;
  }

  //scrollable
  if (localStorage.getItem("velvetheaderscrollable")) {
    document.querySelector("#switcher-header-scroll").checked = true;
  }
  if (localStorage.getItem("velvetmenuscrollable")) {
    document.querySelector("#switcher-menu-scroll").checked = true;
  }

  //fixed
  if (localStorage.getItem("velvetheaderfixed")) {
    document.querySelector("#switcher-header-fixed").checked = true;
  }
  if (localStorage.getItem("velvetheaderscrollable")) {
    document.querySelector("#switcher-header-scroll").checked = true;
  }
  if (localStorage.getItem("velvetheaderrounded")) {
    document.querySelector("#switcher-header-rounded").checked = true;
  }

  //classic
  if (localStorage.getItem("velvetclassic")) {
    document.querySelector("#switcher-classic").checked = true;
  }

  // sidemenu layout style
  if (localStorage.velvetverticalstyles) {
    let verticalStyles = localStorage.getItem("velvetverticalstyles");
    switch (verticalStyles) {
      case "default":
        document.querySelector("#switcher-default-menu").checked = true;
        break;
      case "closed":
        document.querySelector("#switcher-closed-menu").checked = true;
        break;
      case "icontext":
        document.querySelector("#switcher-icontext-menu").checked = true;
        break;
      case "overlay":
        document.querySelector("#switcher-icon-overlay").checked = true;
        break;
      case "detached":
        document.querySelector("#switcher-detached").checked = true;
        break;
      case "doublemenu":
        document.querySelector("#switcher-double-menu").checked = true;
        break;
      default:
        document.querySelector("#switcher-default-menu").checked = true;
        break;
    }
  }
  // navigation menu style
  if (localStorage.velvetnavstyles) {
    let navStyles = localStorage.getItem("velvetnavstyles");
    switch (navStyles) {
      case "menu-click":
        document.querySelector("#switcher-menu-click").checked = true;
        break;
      case "menu-hover":
        document.querySelector("#switcher-menu-hover").checked = true;
        break;
      case "icon-click":
        document.querySelector("#switcher-icon-click").checked = true;
        break;
      case "icon-hover":
        document.querySelector("#switcher-icon-hover").checked = true;
        break;
    }
  }

  // loader
  if(localStorage.loaderEnable != "true"){
      document.querySelector("#switcher-loader-disable").checked = true
  }
}

// chart colors
let myVarVal, primaryRGB;
function updateColors() {
  "use strict";
  primaryRGB = getComputedStyle(document.documentElement)
    .getPropertyValue("--primary-rgb")
    .trim();

  //get variable
  myVarVal = localStorage.getItem("primaryRGB") || primaryRGB;

  //index
  if (document.querySelector("#avgsales") !== null) {
    index1();
  }
  if (document.querySelector("#earnings") !== null) {
    earnings();
  }
  if (document.querySelector("#sparkline8") !== null) {
    sparkline8();
  }

  //index2
  if (document.querySelector("#marketCap") !== null) {
    marketCap();
  }
  if (document.querySelector("#crm-total-customers") !== null) {
    crmtotalCustomers();
  } 
  
  //index3
  if (document.querySelector("#applicantStats") !== null) {
    applicantStats();
  }
  if (document.querySelector("#careerPageStats") !== null) {
    careerPageStats();
  }

  //index4
  if (document.querySelector("#crm-statistics") !== null) {
    crmStatistics();
  }
  if (document.querySelector("#dealsSource") !== null) {
    dealsSource();
  }

  //index5
  if (document.querySelector("#projectstatistics") !== null) {
    projectstatistics();
  }
  if (document.querySelector("#salesDonut") !== null) {
    salesDonut();
  }
  
  //index6
  if (document.querySelector("#analytics-users") !== null) {
    analyticsusers();
  }
  if (document.querySelector("#audienceReport") !== null) {
    audienceReport();
  }
  if (document.querySelector("#sessionsByDevice") !== null) {
    sessionsByDevice();
  }
  if (document.querySelector("#popTrades") !== null) {
    popTrades();
  }

  //index7
  if (document.querySelector("#projectAnalysis") !== null) {
    projectAnalysis();
  }

  //index8
  if (document.querySelector("#nft-balance-chart") !== null) {
    nftBalane();
  }
  if (document.querySelector("#nft-statistics") !== null) {
    nftStatistics();
  }

  //index9
  if (document.querySelector("#hrmstatistics") !== null) {
    hrmstatistics();
  }

  //index10
  if (document.querySelector("#personal-balance") !== null) {
    personalbalance();
  }
  if (document.querySelector("#balance") !== null) {
    balance();
  }

  //index11
  if (document.querySelector("#stockCap") !== null) {
    stockCap();
  }

  //index12
  if (document.querySelector("#CourseStatistics") !== null) {
    CourseStatistics();
  }
  if (document.querySelector("#payoutsReport") !== null) {
    payoutsReport();
  }
  
  //widgets
  if (document.querySelector("#views") !== null) {
    pageviews();
  }

  //file-manager
  if (document.querySelector("#filestore") !== null) {
    filestore();
  }
  
}

if (document.querySelector("#switcher-canvas")) {

  //switcher color pickers
  const pickrContainerPrimary = document.querySelector('.pickr-container-primary');
  const themeContainerPrimary = document.querySelector('.theme-container-primary');
  const pickrContainerBackground = document.querySelector('.pickr-container-background');
  const themeContainerBackground = document.querySelector('.theme-container-background');

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

  /* for theme background */
  const nanoThemes1 = [
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
  const nanoButtons1 = [];
  let nanoPickr1 = null;
  for (const [theme, config] of nanoThemes) {
      const button = document.createElement('button');
      button.innerHTML = theme;
      nanoButtons1.push(button);

      button.addEventListener('click', () => {
          const el = document.createElement('p');
          pickrContainerBackground.appendChild(el);

          /* Delete previous instance */
          if (nanoPickr1) {
              nanoPickr1.destroyAndRemove();
          }

          /* Apply active class */
          for (const btn of nanoButtons) {
              btn.classList[btn === button ? 'add' : 'remove']('active');
          }

          /* Create fresh instance */
          nanoPickr1 = new Pickr(Object.assign({
              el,
              theme,
              default: '#8e54e9'
          }, config));

          /* Set events */
          nanoPickr1.on('changestop', (source, instance) => {
              let color = instance.getColor().toRGBA();
              let html = document.querySelector('html');
              html.style.setProperty('--body-bg-rgb', `${color[0]}, ${color[1]}, ${color[2]}`);
              document.querySelector('html').style.setProperty('--light-rgb', `${color[0] + 14}, ${color[1] + 14}, ${color[2] + 14}`);
              document.querySelector('html').style.setProperty('--form-control-bg', `rgb(${color[0] + 14}, ${color[1] + 14}, ${color[2] + 14})`);
              localStorage.removeItem("bgtheme");
              updateColors();
              html.setAttribute('data-theme-mode', 'dark');
              html.setAttribute('data-menu-styles', 'dark');
              html.setAttribute('data-header-styles', 'dark');
              document.querySelector('#switcher-dark-theme').checked = true;
              localStorage.setItem('bodyBgRGB', `${color[0]}, ${color[1]}, ${color[2]}`);
              localStorage.setItem('bodylightRGB', `${color[0] + 14}, ${color[1] + 14}, ${color[2] + 14}`);
          });
      });
      themeContainerBackground.appendChild(button);
  }
  nanoButtons1[0].click();
  /* for theme background */

}

updateColors();

function localStorageBackup2() {
  if (localStorage.bodyBgRGB || localStorage.bodylightRGB) {
    document.querySelector("#switcher-dark-theme").checked = true;
    document.querySelector("#switcher-menu-dark").checked = true;
    document.querySelector("#switcher-header-dark").checked = true;
  }

  if (localStorage.bodyBgRGB && localStorage.bodylightRGB) {
    if (localStorage.bodyBgRGB == "20, 30, 96") {
      document.querySelector("#switcher-background").checked = true;
    }
    if (localStorage.bodyBgRGB == "8, 78, 115") {
      document.querySelector("#switcher-background1").checked = true;
    }
    if (localStorage.bodyBgRGB == "90, 37, 135") {
      document.querySelector("#switcher-background2").checked = true;
    }
    if (localStorage.bodyBgRGB == "24, 101, 51") {
      document.querySelector("#switcher-background3").checked = true;
    }
    if (localStorage.bodyBgRGB == "120, 66, 20") {
      document.querySelector("#switcher-background4").checked = true;
    }
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

  if(localStorage.loaderEnable == "true"){
      document.querySelector("#switcher-loader-enable").checked = true
  }
    
}