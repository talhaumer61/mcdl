(function () {
  "use strict";

  var myElement1 = document.getElementById("files-main-nav");
  new SimpleBar(myElement1, { autoHide: true });

  var myElement2 = document.getElementById("file-folders-container");
  new SimpleBar(myElement2, { autoHide: true });

  /* filepond */
  FilePond.registerPlugin(
    FilePondPluginImagePreview,
    FilePondPluginImageExifOrientation,
    FilePondPluginFileValidateSize,
    FilePondPluginFileEncode,
    FilePondPluginImageEdit,
    FilePondPluginFileValidateType,
    FilePondPluginImageCrop,
    FilePondPluginImageResize,
    FilePondPluginImageTransform
  );

  /* multiple upload */
  const MultipleElement = document.querySelector(".multiple-filepond");
  FilePond.create(MultipleElement);

  document.querySelectorAll(".files-type").forEach((element) => {
    element.onclick = () => {
      if (window.screen.width <= 575) {
        document.querySelector(".file-manager-folders").classList.add("open");
        document
          .querySelector(".file-manager-navigation")
          .classList.add("close");
      }
    };
  });
  document.querySelector("#folders-close-btn").onclick = ()=>{
      document.querySelector(".file-manager-navigation").classList.remove("close")
      document.querySelector(".file-manager-folders").classList.remove("open")
  }

  window.addEventListener("resize", () => {
    if (window.screen.width > 576) {
      document
        .querySelector(".file-manager-navigation")
        .classList.remove("close");
    }
  });
})();